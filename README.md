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

**Step 3.** Change your document root to point to SmartTacToe's web directory. *This prevents the core SmartTacToe
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
    <Directory "/var/www/SmartTacToe/web">
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
- chat:write:user
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
  the state of the game and players. Using a database would have made the exercise a little too straight-forward, so
  I wanted to challenge myself! I also wanted to see how far I could hack/bend the Slack API.

- OPTIMAL-PLAY PREDICTIVE: I used the [MiniMax](https://en.wikipedia.org/wiki/Minimax)
  Game Theory Algorithm to silently predict what the next best move should be. I used miniMax because quick
  research showed me that its the staple algorithm for m,n,k-game games like Tic Tac Toe.

- QUASI-SENTIENT: I thought it would be fun for the game to "watch" its players
  playing and react to the moves they chose! This reaction is displayed on the left hand side of the Slack message
  after every play in the form of an emoji.

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
- The commits should have been smaller in size, as opposed to large chunks! This makes merging easier, among other things
- Because this App is stateless, the **slash commands** may sometimes appear not to work, but this is because of a lag
  in the Api's updating its state for Api calls. usually these commands will work after a few seconds. For example, if
  you challenge a user `/ttt challenge @user` and then the user accepts the challenge, running the
  command `/ttt status` immediately, will claim that there is currently not game in session, yet this will not be the case if you run
  the same command a few seconds later. It becomes obvious that the `stateless` approach may not give the best user experience
  for a production App. But its great as an "academic" excercise to learn more about how the Slack Api works.

## So how does it work?

- The states are stored in the interactive `message button` value fields.
- The app enforces rules such as `only on game per channel` by doing a quick search of the channel messages.
  For efficiency, it only searches messages in the perticular channel, that were posted by it, and that match a very
  specific criteria. Only the most recent message matching the criteria is searched for
- To avoid multiple states being stored on the channel, everytime a play makes a move, the previous message
  is deleted using the `delete_original` flag.
- The boards you see on the channel showing the player history do not have any state. They are just renderings. Only the
  interactive boards (the one's with interactive buttons) have state, and there can be only one board at any give time.
- If a game is inactive for a while (about 10mins), then it will be automatically rendered stale and deleted. This deletion
  is triggered by commands such as `/ttt status` and `/ttt challenge @user`
- To avoid clutter and confusion, only one active challenge is allowed. When someone creates a new challenge `/ttt challenge @user`
  then any preceeding challenge requests that are still pending are automatically deleted!
- The sentient aspect is driven by the miniMax algorithm. The App predicts what the best move should be (before the player makes the move)
  and then it compares its prediction with the move made by the player. Well, the computer is usually the better player, so if the
  user makes a different move, then the game posts an emoji with a negative emotion. The opposite is true.
