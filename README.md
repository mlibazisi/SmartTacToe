SmartTacToe For Slack
=======================
SmartTacToe is a stateless, quasi-sentient, optimal-play predictive m,n,k-game for [Slack](https://slack.com)

Quick points about this implementation
- **Stateless**: No Databases, sessions, cookies, etc
- **Sentient**: The game watches your moves and 'emotionally' responds based on how well you play
- **No PHP Framework Used**: Built from scratch, specifically for this game, without a framework
- **Rapid Prototype**: Rapidly prototyped over a short span of about 8 days

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
