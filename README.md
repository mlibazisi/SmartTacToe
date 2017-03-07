SmartTacToe For Slack
=======================
SmartTacToe is a stateless, quasi-sentient, optimal-play predictive m,n,k-game for [Slack](https://slack.com)

- STATELESS: It does not use databases, sessions, or cookies to store
  the state of the game and players.
- OPTIMAL-PLAY PREDICTIVE: It uses a version of the [MiniMax](https://en.wikipedia.org/wiki/Minimax)
  Game Theory Algorithm to silently predict what the next best move should be.
- QUASI-SENTIENT: Using its predictive algorithm, it reacts to moves made by
  players using sentient emoji. If the player makes a move similar to what the algorithm
  predicts as optimal, then a positive emotion is displayed by an emoji, otherwise a
  negative emotion is expressed. It's 'quasi' because it doesn't actually feel anything, but
  acts like it does.
  HINT: When playing the game, look to the left of the game responses and you will see the emoji responses!
- M,N,K Game: It's the classic Tic Tac Toe 3,3,3-game

## Pre-Requisites to Installation

To install SmartTacToe, you will need a web server, e.g. [Apache](https://httpd.apache.org/),
which meets the following minimum configurations:

- [PHP](http://php.net/) >= 5.3.9
- Rewrite module enabled
- [SSL](https://en.wikipedia.org/wiki/Transport_Layer_Security) enabled

You may need to have root access to your server, so that you can change some file
permissions, as well as configure the root directory.

Finally, you will need to [create a Slack App](https://api.slack.com/slack-apps), and
call it SmartTacToe. There are many great tutorials online on how to do this. Once your
App is created, quickly familiarize yourself with the Slack API documentation
so you know where to access the following:

- Client Id
- Client Secret
- Verification Token
- OAuth Access Token

## Installation

Download SmartTacToe onto your web server document root

```bash
# Clone source from git
git clone https://github.com/mlibazisi/SmartTacToe.git
```

Next, change your old document root from:

```bash
DocumentRoot Old/Document/Root
```

to

```bash
DocumentRoot /Old/Document/Root/SmartTacToe/web
```
Don't forget to do the same thing for

```bash
<Directory /var/www/html/>
```

Next, navigate to the new document root, and then
into the SmartTacToe configuration directory

```php
cd config
```

Create a parameters configuration file

 ```bash
touch parameters.yml
 ```

Now open the parameters.yml file and paste the following:

```php
slack_api:
  oauth_access_url: 'REPLACE_ME_WITH_OAUTH_ACCESS_URL'
  client_id: 'REPLACE_ME_WITH_CLIENT_ID'
  client_secret: 'REPLACE_ME_WITH_CLIENT_SECRET'
  command: '/ttt'
  web_hook: 'REPLACE_ME_WITH_WEBHOOK_URL'
  token: 'REPLACE_ME_VERIFICATION_TOKEN'
  access_token: 'REPLACE_ME_WITH_ACCESS_TOKEN'
  post_message_method: 'https://slack.com/api/chat.postMessage'
  delete_message_method: 'https://slack.com/api/chat.delete'
  search_messages: 'https://slack.com/api/search.messages'
  app_name: 'REPLACE_ME_WITH_APP_NAME'
game_timeout: 10
```

In the same parameters file, replace each of the configuration values with the
appropriate value. Only replace the ones with the "REPLACE_ME_*" text.

Now go to the Slack API page, and enable interactive messages.

Next, configure the interactive message Request URL to point to:

- Interactive Message request url should point to
 ```bash
{https://replace_with_you_website.com}/interact
 ```
Navigate to the Slash command configuration page:

- Under Command, enter:
 ```bash
/ttt
 ```

- Under Request URL, enter:
 ```bash
{https://replace_with_you_website.com}/command
 ```

- Under Short Description, enter:
 ```bash
SmartTacToe
 ```
Before you leave this page, make sure you check the box
that says 'Escape channels, users, and links sent to your app'

Next, navigate to Permission Scopes and add the following scores:
- commands
- chat:write:bot
- chat:write:user
- search:read

Finally, [add the App to slack to your team](https://get.slack.help/hc/en-us/articles/202035138-Add-an-app-to-your-team)

You may now begin playing the game by going to your team on Slack, choosing any channel you want,
and typing the command:

 ```bash
/ttt
 ```

The command will give you instructions on how to play the game!