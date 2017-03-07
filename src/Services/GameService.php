<?php
/*
 * This file is part of the SmartTacToe package (https://github.com/mlibazisi/SmartTacToe)
 *
 * Copyright (c) 2017 Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Services;

use Constants\ConfigConstants;
use Constants\GameConstants;
use Constants\HelperConstants;
use Constants\MessageConstants;
use Constants\ServiceConstants;
use Constants\ViewConstants;
use Exceptions\ServiceException;
use Interfaces\GameInterface;

/**
 * The game server
 *
 * @author Mlibazisi Prince Mabandla <mlibazisi@gmail.com>
 */
class GameService implements GameInterface
{

    /**
     * A collection of sentient emojis
     *
     * @var array
     */
    private $_emoji_bank = [
        'positive'   => [
            ':wink:',
            ':smiley:',
            ':grinning:',
            ':blush:',
            ':sunglasses:',
            ':relieved:',
            ':smile:'
        ],
        'negative' => [
            ':unamused:',
            ':confused:',
            ':grimacing:',
            ':flushed:',
            ':disappointed:',
            ':anguished:',
            ':pensive:'
        ]
    ];

    /**
     * Service and parameter container
     *
     * @var ContainerService
     */
    private $_container;

    /**
     * Instantiate GameService and inject the service container
     *
     * @param ContainerService $container The service container
     */
    public function __construct( ContainerService $container )
    {

        $this->_container = $container;

    }

    /**
     * Posts help options to a user
     *
     * @return mixed
     */
    public function help()
    {

        $help_text = $this->_render(
            ViewConstants::HELP_MESSAGE
        );

        return $this->_message( $help_text );

    }

    /**
     * Posts an error message to a channel
     *
     * @param string    $error_message The error message
     * @param bool      $replace_original  Whether to replace the original message or not
     * @return array
     */
    public function error( $error_message, $replace_original = FALSE )
    {

        $help_text = $this->_render(
            ViewConstants::ERROR_MESSAGE, [
                'error' => $error_message
            ]
        );

        return $this->_message( $help_text, $replace_original );

    }

    /**
     * Decline a game invitation
     *
     * @param array $challenger The user who initiated the challenge
     * @param array $opponent   The challenged user
     *
     * @return array
     */
    public function decline( $challenger, $opponent )
    {

        $opponent_handle    = "<@{$opponent['id']}|{$opponent['name']}>";
        $challenger_handle  = "<@{$challenger['id']}|{$challenger['name']}>";
        $response           = [
            "response_type"     => 'in_channel',
            "mrkdwn"            => TRUE,
            "delete_original"   => TRUE
        ];

        $response['text'] = $this->_render(
            ViewConstants::DECLINE, [
            'challenger'    => $challenger_handle,
            'opponent'      => $opponent_handle
        ] );

        return $response;

    }

    /**
     * Delete any challenges that have not been responded to
     *
     * @param string    $channel_name   The current channel name
     * @param string    $channel_id     The current channel id
     * @return GameService
     */
    public function overridePendingChallenges( $channel_name, $channel_id )
    {

        $challenges = $this->_container
            ->get( ServiceConstants::SEARCH )
            ->find(
                $channel_name,
                GameConstants::CHALLENGE_TEXT
            );

        if ( !empty( $challenges ) ) {

            $params = [
                'as_users'  => TRUE,
                'channel'   => $channel_id
            ];

            $functions = $this->_container
                ->get( HelperConstants::HELPER_FUNCTIONS );

            foreach ( $challenges as  $challenge ) {

                if ( $functions->strpos( $challenge['text'], GameConstants::CHALLENGE_TEXT ) ) {
                    $params[ 'ts' ] = $challenge['ts'];
                    $this->_delete( $params );
                }

            }

        }

        return $this;

    }

    /**
     * Begin a game
     *
     * @param   array     $game_state     The game state
     * @param   string    $response_url   The response url
     * @return  bool True on success, false otherwise
     */
    public function begin( array $game_state, $response_url )
    {

        $response                       = $this->_compileInteractiveBoard( $game_state, TRUE );
        $response['delete_original']    = TRUE;

        return $this->_sendPost( $response_url, $response );

    }

    /**
     * Delete a message from a channel
     *
     * @param array $params The deletion parameters
     * @return bool True on success, false otherwise
     */
    public function delete( $params ) {

        return $this->_delete( $params );

    }

    /**
     * Draws out the current state of the game
     *
     * @param array $state The game state
     *
     * @return string
     */
    public function drawBoard( array $state  ) {

        $board_index    = 0;
        $board          = '';

        for ( $i = 0; $i < 3; $i++ ) {

            $board .= ">";

            for ( $z = 0; $z < 3; $z++ ) {

                $marker = isset( $state['board'][ $board_index ] )
                    ? $state['board'][ $board_index ]
                    : GameConstants::BLANK_MARKER;

                $board .=  ":{$marker}:";
                $board_index++;

            }

            $board .= "\n";

        }

        return $board;

    }

    /**
     * Submit a play
     *
     * @param string    $me             The current user
     * @param array     $state          The ngame state
     * @param string    $channel_id     The id of the channel
     * @param string    $response_url   The url to respond to
     * @return bool Returns true on completion of a key step
     */
    public function play( $me, array $state, $channel_id, $response_url )
    {

        $player         = $this->_getCurrentPlayer( $state );
        $played_moves   = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS )
            ->count( $state['board'] );

        if ( $played_moves > 0 ) {

            $optimal_play = $this->_container
                ->get( ServiceConstants::OPTIMAL_PLAY )
                ->predict(
                    $state['board'],
                    $player['marker'],
                    $this
                );

        } else {
            $optimal_play = [ 'score' => 0, 'move'  => $state['play'] ];
        }

        $this->_playMove( $state, $player );

        if ( $this->isWinningMove( $state['board'], $state['play'] ) ) {
            return $this->_showWinningBoard( $state, $me, $response_url );
        }

        if ( $this->isEnd( $state['board'] ) ) {
            return $this->_showEndOfGameBoard( $state, $me, $response_url );
        }

        $this->_showCurrentPlay( $state, $optimal_play, $response_url );

        $this->_showInteractiveBoard( $state, $channel_id, $response_url );

        return TRUE;

    }

    /**
     * Retrieves the current game
     *
     * The current game is defined as the last message
     * in the channel history that was posted by this bot,
     * and matches certain search criteria in its text
     *
     * @param string    $channel_name       The current channel name
     * @param string    $channel_id         The current channel id
     * @return array
     */
    public function currentGame( $channel_name, $channel_id )
    {

        $raw_game   = $this->_container
            ->get( ServiceConstants::SEARCH )
            ->find( $channel_name, GameConstants::ACTIVE_GAME_TEXT, 1 );

        $functions   = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        if ( empty( $raw_game[0]['attachments'][0]['actions'][0]['value'] )
            || !$functions->strpos( $raw_game[0]['text'], GameConstants::ACTIVE_GAME_TEXT ) ) {
            return [];
        }

        $raw_game           = $raw_game[0];
        $elapsed_minutes    = ( $functions->time() - (int)$raw_game['ts'] ) / 60;
        $game_state         = $functions->jsonDecode( $raw_game['attachments'][0]['actions'][0]['value'], TRUE );
        $game               = $game_state['game'];
        $game['ts']         = $raw_game['ts'];
        $max_minutes        = $this->_container
            ->getParameter( GameConstants::GAME_TIMEOUT );

        if ( $elapsed_minutes > $max_minutes ) {

            $is_deleted = $this->delete( [
                'as_user'   => TRUE,
                'ts'        => $raw_game['ts'],
                'channel'   => $channel_id
            ] );

            if ( $is_deleted ) {

                $recipients = [];

                foreach ( $game['players'] as $player ) {
                    $recipients[] = '@' . $player['name'];
                }

                $message = $this->_render(
                    ViewConstants::TIME_OUT, [
                        'timeout' => $max_minutes
                    ]
                );

                $this->_sendDirectMessage( $message, $recipients );

                return [];

            }

        }

        return $game;

    }

    /**
     * End any active game
     *
     * @param array     $me             The current user's info
     * @param string    $channel_name   The name of the channel
     * @param string    $channel_id     The id of the channel
     * @return mixed
     */
    public function end( $me, $channel_name, $channel_id ) {

        $game = $this->currentGame( $channel_name, $channel_id );

        if ( !$game ) {

            return $this->error(
                'There is currently no active game to end.'
            );

        }

        $can_end  = FALSE;
        $opponent = [];

        foreach ( $game['players'] as $player ) {

            if ( $player['id'] == $me['id'] ) {
                $can_end = TRUE;
            } else {
                $opponent = $player;
            }

        }

        if ( !$can_end ) {

            return $this->error(
                'Only current players can end a game!'
            );

        }

        $params = [
            'as_user'   => TRUE,
            'ts'        => $game['ts'],
            'channel'   => $channel_id
        ];

        $is_deleted = $this->_delete( $params );

        if ( !$is_deleted ) {

            return $this->error(
                'Failed to end game!'
            );

        }

        $recipients = [
            '@' . $opponent['name']
        ];

        $message = $this->_render(
            ViewConstants::GAME_ENDED, [
                'opponent' => $opponent['name']
            ]
        );

        $this->_sendDirectMessage( $message, $recipients );

        $help_text = $this->_render(
            ViewConstants::GENERIC_MESSAGE, [
                'text' => "You've ended the game. Bye bye!\n"
            ]
        );

        return $this->_message( $help_text );

    }

    /**
     * View any active game
     *
     * @param string    $channel_name   The name of the channel
     * @param string    $channel_id     The id of the channel
     * @return mixed
     */
    public function status( $channel_name, $channel_id ) {

        $game = $this->currentGame( $channel_name, $channel_id );

        if ( !$game ) {

            return $this->error(
                'There is currently no active game to view'
            );

        }

        $board = $this->_render(
            ViewConstants::GAME_STATUS, [
                'board'             => $this->drawBoard( $game ),
                'player1'           => $game['players'][0]['name'],
                'player1_marker'    => $game['players'][0]['marker'],
                'player2'           => $game['players'][1]['name'],
                'player2_marker'    => $game['players'][1]['marker']
            ]
        );

        return [
            'text'              => $board,
            'mrkdwn'            => TRUE,
            'delete_original'   => TRUE,
            'response_type'     => 'ephemeral'
        ];

    }

    /**
     * Determine if a game has ended
     *
     * @param array $board The game board
     * @return bool True if ended, false otherwise
     */
    public function isEnd( array $board ) {

        $total_plays = 0;

        foreach ( $board as $index => $marker ) {

            if ( $marker == GameConstants::O_MARKER
                || $marker == GameConstants::X_MARKER ) {
                $total_plays++;
            }

        }

        return ( $total_plays == 9 );

    }

    /**
     * Challenge an opponent
     *
     * @param string    $challenger The one challenging
     * @param string    $opponent   The one being challenged
     * @return array
     */
    public function challenge( $challenger, $opponent, $channel_id ) {

        $new_game_state     = $this->_initializeGameState( $challenger, $opponent );
        $opponent_handle    = "<@{$opponent['id']}|{$opponent['name']}>";
        $text               = "{$opponent_handle} you've been challenged!";
        $text               .= "\n_Wanna play SmartTacToe against {$challenger['name']}?_";
        $functions          = $this->_container->get(
            HelperConstants::HELPER_FUNCTIONS
        );

        $accepted_value = [
            'status'        => GameConstants::ACCEPTED_ACTION,
            'opponent'      => $opponent,
            'challenger'    => [],
            'game'          => $new_game_state

        ];

        $declined_value = [
            'status'        => GameConstants::DECLINED_ACTION,
            'opponent'      => $opponent,
            'challenger'    => $challenger,
            'game'          => []

        ];

        $fallback    = $opponent_handle . ' you\'ve been challenged by ' . $challenger['name'];
        $attachments = [
            [
                "fallback"          => $fallback,
                'callback_id'       => $functions->getUniqueId(),
                'attachment_type'   => 'default',
                'actions' => [
                    [
                        'name'  => 'accept',
                        'text'  => 'Accept',
                        'type'  => 'button',
                        'value' => $functions->jsonEncode( $accepted_value ),
                    ],
                    [
                        'name'  => 'decline',
                        'text'  => 'Decline',
                        'type'  => 'button',
                        'value' => $functions->jsonEncode( $declined_value )
                    ]
                ],
            ],
        ];

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $params = [
            'text'          => $text,
            'attachments'   => $functions->jsonEncode( $attachments ),
            'channel'       => $channel_id
        ];

        $this->_postMessageMethod( $params );

        return TRUE;

    }

    /**
     * Determine if a move results in a win
     *
     * @param array    $linear_board  A one dimensional array of the board
     * @param string   $play          The ngame state
     * @return bool True if the result is a win, false otherwise
     */
    public function isWinningMove( array $linear_board, $play ) {

        $board = [];

        if ( empty( $linear_board ) ) {
            return FALSE;
        }

        foreach ( $linear_board as $position => $marker ) {

            $x = (int)($position / 3);
            $y = $position % 3;
            $board[ $x ][ $y ] = $marker;

        }

        $x_play     = (int)($play / 3);
        $y_play     = $play % 3;
        $x_matches  = 0;
        $y_matches  = 0;

        for( $i = 0; $i < 3; $i++ ) {

            if ( isset( $board[ $i ][ $y_play ] )
                && $board[ $i ][ $y_play ] == $board[ $x_play ][ $y_play ] ) {
                $x_matches++;
            }

            if ( isset( $board[ $x_play ][ $i ] )
                && $board[ $x_play ][ $i ] == $board[ $x_play ][ $y_play ] ) {
                $y_matches++;
            }

        }

        if ( ( $x_matches == 3 )
            || ( $y_matches == 3 ) ) {
            return TRUE;
        }

        $f_matches = 0;
        $b_matches = 0;
        $backwards = 2;

        for( $forward = 0; $forward < 3; $forward++ ) {

            if ( isset( $board[ $forward ][ $forward ] )
                && $board[ $forward ][ $forward ] == $board[ $x_play ][ $y_play ] ) {
                $f_matches++;
            }

            if ( isset( $board[ $forward ][ $backwards ] )
                && $board[ $forward ][ $backwards ] == $board[ $x_play ][ $y_play ] ) {
                $b_matches++;
            }

            $backwards--;

        }

        return ( ( $f_matches == 3 )
            || ( $b_matches == 3 ) );

    }

    /**
     * Delete a message from Slack
     *
     * @param array $params Array of parameters, such as message timestamp
     * @return bool True on success, false otherwise
     */
    private function _delete( array $params )
    {

        $url = $this->_container
            ->getParameter( ConfigConstants::DELETE_MESSAGE_METHOD );

        $params['token'] = $this->_container
            ->getParameter( ConfigConstants::ACCESS_TOKEN );

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $url .= '?' . $functions->httpBuildQuery( $params );

        return $this->_sendGet( $url );

    }

    /**
     * Change the state of the board by playing move
     *
     * @param array $state  The game state
     * @param array $me     The current player
     *
     * @return void
     */
    private function _playMove( array &$state, array $me ) {

        $state['board'][ $state['play'] ] = $me['marker'];

    }

    /**
     * Render the final board at the end of a game when players have a draw
     *
     * @param array     $state          The game state
     * @param array     $me             The current player
     * @param string    $response_url   The url to submit the request to
     *
     * @return bool
     */
    private function _showEndOfGameBoard( array $state, array $me, $response_url ) {

        $response_text = $this->_render(
            ViewConstants::DRAW, [
                'board'     => $this->drawBoard( $state ),
                'player1'   => "<@{$me['id']}|{$me['name']}>",
                'player2'   => "<@{$state['players'][1]['id']}|{$state['players'][1]['name']}>"
            ]
        );

        return $this->_sendPost( $response_url, [
            'text'             => $response_text,
            "mrkdwn"           => TRUE,
            "delete_original"  => TRUE,
            "response_type"    => "in_channel"
        ] );

    }

    /**
     * Render the final board at the end of a game when one player wins
     *
     * @param array     $state          The game state
     * @param array     $me             The current player
     * @param string    $response_url   The url to submit the request to
     *
     * @return bool
     */
    private function _showWinningBoard( array $state, array $me, $response_url ) {

        $response_text = $this->_render(
            ViewConstants::WIN, [
                'board'     => $this->drawBoard( $state ),
                'winner'    => "<@{$me['id']}|{$me['name']}>",
                'loser'     => "<@{$state['players'][1]['id']}|{$state['players'][1]['name']}>"
            ]
        );

        return $this->_sendPost( $response_url, [
            'text'             => $response_text,
            "mrkdwn"           => TRUE,
            "delete_original"  => TRUE,
            "response_type"    => "in_channel"
        ] );

    }

    /**
     * Display the 'clickable' board so the next player can play
     *
     * @param array     $state          The game state
     * @param array     $channel_id     The channel to submit the board to
     * @param string    $response_url   The url to submit the request to
     *
     * @return bool
     */
    private function _showInteractiveBoard( array $state, $channel_id, $response_url ) {

        $buffer                         = $state['players'][0];
        $state['players'][0]            = $state['players'][1];
        $state['players'][1]            = $buffer;
        $response                       = $this->_compileInteractiveBoard( $state );
        $response['channel']            = $channel_id;
        $response['delete_original']    = TRUE;

        $this->_sendPost( $response_url, $response );

    }

    /**
     * Render the board showing the play that has just been made
     *
     * @param array     $state          The game state
     * @param array     $optimal_play   The predicted optimal play
     * @param string    $response_url   The url to submit the request to
     *
     * @return bool
     */
    private function _showCurrentPlay( array $state, $optimal_play, $response_url ) {

        $sentient_emoji     = $this->_getSentientEmoji( $optimal_play, $state['play'] );
        $current_player     = $state['players'][0]['name'];
        $current_marker     = $state['players'][0]['marker'];
        $next_player        = $state['players'][1]['name'];
        $next_marker        = $state['players'][1]['marker'];
        $board_position     = 1 + (int)$state['play'];

        $response_text      = $this->_render(
            ViewConstants::CURRENT_PLAY, [
                'board'             => $this->drawBoard( $state ),
                'sentient_emoji'    => $sentient_emoji,
                'current_player'    => $current_player,
                'current_marker'    => $current_marker,
                'board_position'    => $board_position,
                'next_player'       => $next_player,
                'next_marker'       => $next_marker
            ]
        );

        return $this->_sendPost( $response_url, [
            'text'             => $response_text,
            "mrkdwn"           => TRUE,
            "delete_original"  => TRUE,
            "response_type"    => "in_channel"
        ] );

    }

    /**
     * Get an emoji reaction to the current play
     *
     * The "emotional" reaction of the emoji is based on
     * whether the player's move is the same as the one the
     * computer predicted to be the optimal move
     *
     * @param string $optimal_play  The predicted optimal play
     * @param string $actual_play   The actual play made by the player
     * @return string the emoji
     */
    private function _getSentientEmoji( $optimal_play, $actual_play )
    {

        $polarity   = ( $optimal_play['move'] == $actual_play )
            ? 'positive'
            : 'negative';

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        return $this->_emoji_bank[ $polarity ][ $functions->rand( 0, 6 ) ];

    }

    /**
     * Get the current player
     *
     * By convention, the current player is the
     * first player in the player array list
     *
     * @param array $state The game state
     * @return array
     */
    private function _getCurrentPlayer( array $state ) {

        return $state['players'][0];

    }

    /**
     * Draws out the interactive board
     *
     * @param array     $state              The game state
     * @param bool      $delete_original    Whether to delete the message that invoked this action
     * @return array
     */
    private function _compileInteractiveBoard( $state, $delete_original = FALSE ) {

        $player         = $this->_getCurrentPlayer( $state );
        $player_handle  = "<@{$player['id']}|{$player['name']}>";
        $player_marker  = $player['marker'];
        $attachments    = [];
        $count          = 0;

        $functions      = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $callback_id    = $functions->uniqid();
        $button_value   = [
            'status'        => GameConstants::PLAYED_ACTION,
            'opponent'      => [],
            'challenger'    => [],
            'game'          => []

        ];

        for ( $i = 0; $i < 3; $i++ ) {

            $actions = [];

            for ( $z = 0; $z < 3; $z++ ) {

                $text = isset( $state['board'][ $count ] )
                    ? $state['board'][ $count ]
                    : GameConstants::BLANK_MARKER;

                $new_state              = $state;
                $new_state['play']      = $count;
                $button_value['game']   = $new_state;
                $actions[]              = [
                    'name'  => 'button' . $count,
                    'text'  => ":$text:",
                    'type'  => 'button',
                    'value' => $functions->jsonEncode( $button_value )
                ];

                $count++;

            }

            $fallback       = $player_handle . ' it\'s your turn as ' . $player_marker;
            $attachments[]  = [
                'fallback'          => $fallback,
                'callback_id'       => $callback_id,
                'color'             => '#3AA3E3',
                'attachment_type'   => 'default',
                'actions'           => $actions
            ];

        }

        $board_title = $this->_render(
            ViewConstants::BOARD_TITLE, [
                'player_handle' => $player_handle,
                'player_marker' => $player_marker
            ]
        );

        return [
            'text'              => $board_title,
            "delete_original"   => $delete_original,
            "response_type"     => "in_channel",
            'attachments'       => $attachments
        ];

    }

    /**
     * Create a new game state
     *
     * @param array    $challenger  The challenger
     * @param array    $opponent    The opponent
     * @return array
     */
    private function _initializeGameState( array $challenger, array $opponent ) {

        $player_markers = [
            GameConstants::O_MARKER,
            GameConstants::X_MARKER
        ];

        $functions = $this->_container->get(
            HelperConstants::HELPER_FUNCTIONS
        );

        $functions->shuffle( $player_markers );

        $state  = [
            'players' => [
                [
                    'name'      => $challenger['name'],
                    'id'        => $challenger['id'],
                    'score'     => 0,
                    'marker'    => $functions->arrayShift( $player_markers )
                ],
                [
                    'name'      => $opponent['name'],
                    'id'        => $opponent['id'],
                    'score'     => 0,
                    'marker'    => $functions->arrayShift( $player_markers )
                ]
            ],
            'status'    => GameConstants::CHALLENGED_STATUS,
            'play'      => NULL,
            'board'     => []
        ];

        $functions->shuffle( $state['players'] );

        return $state;

    }

    /**
     * Send a POST request to the API Server
     *
     * @param string    $url    The url to submit to
     * @param array     $data   The data to submit
     *
     * @return bool True on success, false otherwise
     */
    private function _sendPost( $url, array $data )
    {

        $api_response = $this->_container
            ->get( ServiceConstants::HTTP_CLIENT )
            ->jsonPost( $url, $data );

        if ( !isset( $api_response['ok'] )
            || $api_response['ok'] != TRUE ) {

            $functions = $this->_container
                ->get( HelperConstants::HELPER_FUNCTIONS );

            if ( $api_response ) {

                $message    = $functions->jsonEncode( $api_response );
                $logger     = $this->_container
                    ->get( ServiceConstants::LOG );
                $logger->log( $message );

            }

            return FALSE;

        }

        return TRUE;

    }

    /**
     * Send a GET request to the API Server
     *
     * @param string $url The url to submit to
     *
     * @return bool True on success, false otherwise
     */
    private function _sendGet( $url )
    {

        $api_response = $this->_container
            ->get( ServiceConstants::HTTP_CLIENT )
            ->get( $url );

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $api_response = $functions->jsonDecode( $api_response, TRUE );

        if ( !isset( $api_response['ok'] )
            || $api_response['ok'] != TRUE ) {

            if ( $api_response ) {

                $message = $functions->jsonEncode( $api_response );
                $logger  = $this->_container
                    ->get( ServiceConstants::LOG );
                $logger->log( $message );

            }

            return FALSE;

        }

        return TRUE;

    }

    /**
     * Send a direct message to a user
     *
     * @param string    $message    The message
     * @param array     $recipients The recipients [ "@username", ... ]
     * @return void
     */
    private function _sendDirectMessage( $message, array $recipients ) {

        $params = [
            'text'      => $message,
            'as_user'   => FALSE
        ];

        foreach ( $recipients as $recipient ) {
            $params['channel'] = $recipient;
            $this->_postMessageMethod( $params );
        }

    }

    /**
     * Displays a message
     *
     * @param string    $text              The message to post
     * @param bool      $replace_original  Whether to replace the original message or not
     * @return mixed
     */
    private function _message( $text, $replace_original = FALSE )
    {

        return [
            'text'              => $text,
            'response_type'     => 'ephemeral',
            'replace_original'  => $replace_original,
            'mrkdwn'            => TRUE
        ];

    }

    /**
     * Render a view
     *
     * @param string    $view_path   The path to the view
     * @param array     $vars        Variables to be passed to the element
     * @return string   The rendered view
     * @throws ServiceException
     */
    private function _render( $view_path, array $vars = [] )
    {

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $file = $functions->realpath( WEB_ROOT . '/../' . $functions->ltrim( $view_path, '/' ) );

        if ( !$file ) {

            $message    = 'GameService::_render could not find file: ' . $file;
            $logger     = $this->_container->get( ServiceConstants::LOG );
            $logger->log( $message );

            throw new ServiceException( $message );

        }

        if ( $vars ) {
            extract( $vars, EXTR_SKIP );
        }

        ob_start();

        require $file;

        return ob_get_clean();

    }

    /**
     * Submit a request using the postMessage method
     *
     * @param array $params The query string parameters
     * @return bool
     */
    private function _postMessageMethod( array $params = [] )
    {

        $url = $this->_container
            ->getParameter( ConfigConstants::POST_MESSAGE_METHOD );

        $params['token'] = $this->_container
            ->getParameter( ConfigConstants::ACCESS_TOKEN );

        $functions = $this->_container
            ->get( HelperConstants::HELPER_FUNCTIONS );

        $url .= '?' . $functions->httpBuildQuery( $params );

        return $this->_sendGet( $url );

    }

}