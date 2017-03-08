SmartTacToe For Slack
=======================
SmartTacToe is a stateless, quasi-sentient, optimal-play predictive m,n,k-game for [Slack](https://slack.com)

- STATELESS: I deliberately chose not to use any databases, sessions, or cookies to store
  the state of the game and players. I decided to do this because I wanted to see how far I
  could hack the Slack API.
- OPTIMAL-PLAY PREDICTIVE: I used the [MiniMax](https://en.wikipedia.org/wiki/Minimax)
  Game Theory Algorithm to silently predict what the next best move should be. I used miniMax because quick
  research showed me that its the staple algorithm for m,n,k-game games like Tic Tac Toe.
  Given enough time, I would have improved the efficiency of the algorithm using Alpha Beta pruning, as well
  as the 'smartness' and 'accuracy' of the minMax algorithm version I implemented
- QUASI-SENTIENT: I thought it would be fun for the game to "watch" its players
  playing and react to the moves they chose! Using its predictive algorithm, the game reacts to moves made by
  players using sentient emoji. If the player makes a move similar to what the algorithm
  predicts as optimal, then a positive emotion is displayed by an emoji, otherwise a
  negative emotion is expressed. It's 'quasi' because it doesn't actually feel anything, but
  acts like it does.
  HINT: When playing the game, look to the left of the game message titles and you will see the emoji responses!
- This implementation does not use any framework. Everything (except the two Vendor packages installed via composer) has been
  coded from scratch specifically for this App.

## Pre-Requisites to Installation

To install SmartTacToe, you will need a web server, e.g. [Apache](https://httpd.apache.org/),
which meets the following minimum configurations:

- [PHP](http://php.net/) >= 5.3.9
- Rewrite module enabled
- [SSL](https://en.wikipedia.org/wiki/Transport_Layer_Security) enabled
- [Composer](https://getcomposer.org) installed

You will need to have root access to your server, so that you can configure the root directory.

Finally, you will need to [create a Slack App](https://api.slack.com/slack-apps). You can
call it SmartTacToe if you like. There are many great tutorials online on how to create a slack App. Once your
App is created, quickly familiarize yourself with the Slack API documentation
so you know where to access the following:

- Client Id
- Client Secret
- Verification Token
- OAuth Access Token

We will come back and configure the Slack App once SmartTacToe is setup on our web server.

## Installation onto our web server

Please be advised that the following tutorial assumes you're using Apache as your
web server. You may install SmartTacToe on any webserver you like that meets the
minimum requirements specified above.

Step 1. Download SmartTacToe onto your web server's document root

```bash
# Clone source from git to web root directory
git clone https://github.com/mlibazisi/SmartTacToe.git
```

Step 2. Change your web document root to point to SmartTacToe's web directory:
 ```bash
 [old document root]/SmartTacToe/web
 ```
This is because we don't want any of the App's core files to be exposed to the web! Since
there are many variations of how document roots are configured, please use a resource like
[google](http://google.com) or [Stackoverflow](http://stackoverflow.com/) if you need help with this step.

Step 3. Next, navigate to the SmartTacToe's home directory, and then
run composer install. The SmartTacToe home directory is the
directory one level above the new web root directory you just configured

```bash
composer install
```

Step 4. While still in SmartTacToe's home directory, change directory
into the configuration directory

```bash
cd config
```

Step 5. Create a parameters configuration file

 ```bash
touch parameters.yml
 ```
We create this manually because we don't want to store our parameters in a public
place like a git repository

Step 6. Now open the parameters.yml file and paste the following:

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

Before you leave this file, quickly navigate to your Slack App settings page and get
the Client Id and Cient Secret, then enter them into the appropriate sections of "REPLACE_ME_*".

You may now save and close the file.

We will now head over to the Slack Api website and finish configuring our App. We will come back
and enter the rest of the App's configuration values to this parameters file later.

## Configuring your Slack App

Go back to [Slack](https://api.slack.com/) and navigate to the settings page of your App. Then follow
the steps bellow:

Step 1. Click on 'Interactive Messages', then enable interactive messages and enter the following Request URL:

 ```bash
{https://replace_with_you_website.com}/interact
 ```

Step 3. Click on 'Slash Commands'. Click 'Create New Command' and enter the following:

- For the Command, enter:

 ```bash
/ttt
 ```
- For the Request URL enter:

 ```bash
{https://replace_with_you_website.com}/command
 ```
- Enter a short description of your choice. "SmartTacToe rocks!" is an excellent choice!

- Check the box that says `Escape channels, users, and links sent to your app`

You can then click save!

Step 4. Now click on 'OAuth & Permissions'

- For the 'Redirect URLs' add

 ```bash
{https://replace_with_you_website.com}/auth
 ```

- For 'Permission Scopes' add `commands chat:write:bot chat:write:user search:read`

Step 5. [add the App to slack to your team](https://get.slack.help/hc/en-us/articles/202035138-Add-an-app-to-your-team)

We're done configuring our App!

## Configuring the Slack App on our web server

Now go back to your config/parameters.yml and replace each of the configuration values with the appropriate value.
Only replace the ones with the "REPLACE_ME_*" place holder text. You will find all these
configuration values in your [Slack App](https://api.slack.com/apps)

You may now begin playing the game by going to your team on Slack, choosing any channel you want,
and typing the command:

 ```bash
/ttt
 ```

The command will give you instructions on how to play the game!