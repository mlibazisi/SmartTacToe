SmartTacToe For Slack
=======================
SmartTacToe is a stateless, quasi-sentient, optimal-play predictive m,n,k-game for [Slack](https://slack.com)

Quick points about this implementation
- **Stateless**: No Databases, sessions, cookies, etc
- **Sentient**: The game watches your moves and 'emotionally' responds based on how well you play
- **No PHP Framework Used**: Built from scratch, specifically for this game, without a framework
- **Rapid Prototype**: Rapidly prototyped over a short span of about 8 days

For more information on the points above, see the short discussion section at the end of this document.

## Pre-Requisites to Installation

To play SmartTacToe, you will need a web server, e.g. [Apache](https://httpd.apache.org/),
which meets the following minimum configurations:

- [PHP](http://php.net/) >= 5.3.9
- Rewrite module enabled
- [SSL](https://en.wikipedia.org/wiki/Transport_Layer_Security) enabled
- [Composer](https://getcomposer.org) installed and ready to go
- [Curl](http://php.net/manual/en/book.curl.php) is installed and enabled

You will need to have root access to your server, so that you can change the document root directory.

## Installation onto web server

*The following tutorial assumes you're running Apache on Ubuntu*

**Step 1.** Download SmartTacToe into your web directory

```bash
# Go to web root directory
cd /var/www/

# Clone source from git
git clone https://github.com/mlibazisi/SmartTacToe.git
```

**Step 2.** Navigate to the SmartTacToe home directory and run composer install

```bash
# Go to SmartTacToe home directory
cd SmartTacToe

# Run composer install
composer install
```

**Step 3.** Change your document root to point to SmartTacToe's web directory. *This prevents out core SmartTacToe
files from being exposed to the web*

 ```bash
 sudo vi /etc/apache2/sites-available/000-default-le-ssl.conf
 ```
 Now edit your DocumentRoot to look like this:

 ```bash
 DocumentRoot /var/www/SmartTacToe/web
 ```

Don't close the file yet! We need it for step 4!

**Step 4.** Enable mod rewrite

While `default-le-ssl.conf` is still open, add the following to the bottom of it

```php
    <Directory "/var/www/html">
        AllowOverride All
    </Directory>
```

Now you can close and save it!

**Step 5.** Finish mod rewrite enabling and restart apache

```bash
# Finish mod rewrite enabling
sudo a2enmod rewrite

# Restart apache
sudo service apache2 restart
```

**Step 6.** Make sure your log file is writable! If you don't have a `temp/logs` directory, now is a good
time to create one, and then give it the permissions bellow:

```bash
chmod -R 0644 /temp/logs
```

 Lets make sure we're good! Grab a browser and navigate to your url (**https**://your-url.something)! You should see a HelloWorld welcome message!
 If everything looks good, you're ready to create a Slack App

## Creating and Configuring the Slack App

**Step 1.** Got to your browser and visit the [Slack Api Page](https://api.slack.com)

**Step 2.** Create an app and name it `SmartTacToe`
- Click 'Start Building'
- A popup will come up. Enter the App name as *SmartTacToe*, then select a Development Team and click *Create App*

**Step 3.** Click `Interactive Messages`
- Then click `Enable Interactive Messages`
- Then enter the `Request URL` as follows and save

```bash
{https://replace_with_you_website.com}/interact
```

**Step 4.** From the menu options, Click `Slash Commands`
- Then click `Create New Command`
- For the command, enter

```bash
/ttt
```

- For the Request URL, enter

 ```bash
{https://replace_with_you_website.com}/command
 ```

- Click the checkbox titled **Escape channels, users, and links sent to your app**

You can now click save and go to the next step!

**Step 5.** Click `OAuth & Permissions`
- For the `Redirect URLs` enter

 ```bash
{https://replace_with_you_website.com}/auth
 ```
- Click add then save!

**Step 6.** Scroll down to `Permission Scopes` and enter
- chat:write:bot
- search:read

Then click `save changes`!

**Step 7.** Scroll up and click `Install App to Team`

Great! Now keep your browser open (don't logout of Slack). We're now going
to configure our web server to be friends with the Slack App we just created

## Configuring SmartTacToe on the web server

**Step 1.** Navigate to SmartTacToe's home directory (the one that's a level above the document root you configured earlier)
- From the home directory, move into the configuration directory as shown bellow:

 ```bash
cd config
 ```
**Step 2.** Create a parameters.yml file. *We manually create it and never put it on git, because it has sensitive information.*

 ```bash
touch parameters.yml
 ```

**Step 3.** Open the file (parameters.yml) and add the following:

```php
slack_api:
  oauth_access_url: 'https://slack.com/api/oauth.access?client_id=%s&client_secret=%s&code=%s'
  client_id: 'REPLACE_ME_WITH_CLIENT_ID'
  client_secret: 'REPLACE_ME_WITH_CLIENT_SECRET'
  command: '/ttt'
  token: 'REPLACE_ME_VERIFICATION_TOKEN'
  access_token: 'REPLACE_ME_WITH_ACCESS_TOKEN'
  post_message_method: 'https://slack.com/api/chat.postMessage'
  delete_message_method: 'https://slack.com/api/chat.delete'
  search_messages: 'https://slack.com/api/search.messages'
  app_name: 'REPLACE_ME_WITH_APP_NAME'
game_timeout: 10
```

**Step 4.** While parameters.yml is still open, use the configuration values from the Slack App
settings page to replace the values above as indicated. You can find the appropriate values
from the `Basic Information` page and `OAuth & Permissions` page, both of which are in the
settings area of your App.

You can now save and close the parameters.yml file!

**That's it! SmartTacToe is ready to go. Have fun!**

You may now begin playing SmartTacToe by going to your team on Slack, choosing any channel you want,
and typing the command:

 ```bash
/ttt
 ```

The command above will give you instructions on how to play the game!

**Don't forget to watch the emoji reactions on the left side of the Slack messages while playing a game!**

## Discussion

- STATELESS: I deliberately chose not to use any databases, sessions, or cookies to store
  the state of the game and players. I wanted to see how far I
  could hack the Slack API. But this came with a trade-off. Since SmartTacToe relies on the Slack Api
  to determine the state of the game, it can sometimes miss a very recent state update because
  of the lag time in which it takes the Slack Api to make new changes visible over Api calls. This
  only becomes apparent when running commands like `/ttt status` on a game that has just been created. It may take a
  few seconds for that command to "see" the newly created game.

- OPTIMAL-PLAY PREDICTIVE: I used the [MiniMax](https://en.wikipedia.org/wiki/Minimax)
  Game Theory Algorithm to silently predict what the next best move should be. I used miniMax because quick
  research showed me that its the staple algorithm for m,n,k-game games like Tic Tac Toe.
  Given enough time, I would have improved the efficiency of the algorithm using Alpha Beta pruning, as well
  as the 'smartness' and 'accuracy' of the minMax algorithm version I implemented

- QUASI-SENTIENT: I thought it would be fun for the game to "watch" its players
  playing and react to the moves they chose! If a player makes a move similar to what the algorithm
  predicts as optimal, then a positive emotion is displayed as an emoji, otherwise a
  negative emotion is expressed. It's 'quasi' because it doesn't actually feel anything, but
  acts like it does.

- This implementation does not use any PHP framework. Everything (except the two Vendor packages installed via composer) has been
  coded from scratch specifically for this App.

## Future Work

- If I had time, I would have loved to improved the way emotions are expressed. For example, the game can determine if
  a player's performance is improving or deteriorating, and thus respond appropriately.
- Improve the efficiency of the miniMax algorithm by using Alpha Beta pruning
- Improve the 'smartness' of the miniMax algorithm by using search depth to determine the best moves
- It would have been nice, just for fun, to have some sort of benchmark to compare how scalable a stateless approach is compared to a datastore driven one
- Logging over channels, with more insightful message details, to make the logs easily searchable and monitored

## Caveats

Because this tool was built in such a short time, on a slim schedule, there are a few tradeoffs that I had to unfortunately make (but I am fully aware of)

- I am lacking big time on the unit tests and integration tests. This should ideally have a high coverage before even being committed to the repo!
- The implementation of some of the routines and algorithms (such as the miniMax) could have been more efficient. What you will
  see are more of 'rapid prototypes'.
- The exception class naming conventions could have been a little more specific
- Some of the method names could have been more descriptive, for example `GameService::end()` could have been `GameServer::endGame()`
- The commits should have been smaller in size, as opposed to large chunks! This is makes merging easier, among other things


