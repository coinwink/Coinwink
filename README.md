[https://coinwink.com](https://coinwink.com)

# Coinwink

Coinwink is an automated web application for creating and receiving crypto-currency (Bitcoin, Ethereum...) price alerts via e-mail and SMS. It can run on a standard shared hosting web server.

The application gets price and coin data from [coinmarketcap.com](http://coinmarketcap.com/) API.

Application stack: JavaScript, JQuery, PHP, MySQL, minimal Wordpress back-end.

This repository provides the source code for the application. See installation instructions below.
<br>
<br>
## Installation instructions

First do a clean install of Wordpress.

Then upload all files and folders from this repository to your Wordpress installation directory. This will place custom Coinwink theme and Coinwink login user accounts plugin in their proper locations.

Edit database login details in coinwink_auth_sql.php file and then open coinwink_create_db.php file in your browser (run it once). This will create database tables.

To be able to send emails, edit your mail settings in coinwink_auth_email.php and coinwink_auth_email_functions.php files.

In Wordpress admin, first activate the Coinwink theme. Then create a new empty home page and set it to use "Coinwink - Home" template. Set this newly created page as the default homepage in Settings->Reading. 

To be able to use Coinwink with accounts, in your Wordpress admin create two additional pages with permalinks /account/ and /changepass/ with "Coinwink - Account" and "Coinwink - Changepass" templates. In /account/ page add the following shortcode: "[custom-register-form]" (without quotes). In Wordpress Settings->General check "Anyone can register". Then activate Coinwink Login plugin. 

Open backend_email.php in your browser to get initial data for the app. After that, you can open and start using your newly installed Coinwink app.

Note: If you are running your Coinwink instance in a subfolder, e.g. domain.com/coinwink, then increase the link[3] number in template-home.php on line 903. In this 'domain.com/coinwink' example, the number should be link[4]. Then you will be able to see the coin data in the drop-down list.

PHP files starting with "backend_" are scripts for checking prices and sending alerts.

backend_email.php in particular also gets coin data as JSON and puts it into the local MySQL database - this helps to avoid too many direct calls to the API. During the Coinwink page load, the page takes this JSON from MySQL and presents it as price information for each coin, and also creates option values for the coins drop-down list.

The "backend_" php scripts need to run each few minutes. On the production version the cron for these scripts is set to */3 minutes. Keep in mind that coinmarketcap.com API updates every 5 minutes.

To be able to see the favicon, extract all files from the favicon.zip file to the same home directory and then edit img paths in your theme's header.php file.

For web spam protection Coinwink is using Captcha by BestWebSoft plugin. Do not use any version of this plugin that is higher than 4.3.0. Captcha protection is enabled only when creating alerts without the Coinwink account.

For filterable drop-down select the [Select2](https://select2.github.io/) is being used.

For downloading the new coin logos and for updating the existing ones, a separate script was created and is available at this [cryptocurrency-logos](https://github.com/dziungles/cryptocurrency-logos) repository.
