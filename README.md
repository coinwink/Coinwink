[https://coinwink.com](https://coinwink.com)

# Coinwink

Coinwink is an automated web application for creating and receiving [cryptocurrency price alerts](https://coinwink.com/) for Bitcoin, Ethereum... (1500+ crypto assets) via e-mail and SMS, and for managing crypto-currency portfolio.

The application gets price and coin data from [coinmarketcap.com](http://coinmarketcap.com/) API.

Application stack: JavaScript, JQuery, PHP, MySQL, minimal Wordpress back-end.

This repository provides the source code for the application. See installation instructions below.
<br>
<br>
## Installation instructions

**Initial setup**

First do a clean install of Wordpress.

Then upload all files and folders from this repository to your Wordpress installation directory. This will place custom Coinwink theme and Coinwink login user accounts plugin in their proper locations.

Edit database login details in `coinwink_auth_sql.php` file and then open `coinwink_create_db.php` file in your browser (run it once). This will create database tables.

To be able to send emails, edit your mail settings in `coinwink_auth_email.php` and `coinwink_auth_email_functions.php` files.

In Wordpress admin, first activate the Coinwink theme. Then create a new empty home page and set it to use "Coinwink - Home" template. Set this newly created page as the default homepage in "Settings->Reading". 

To be able to use Coinwink with accounts, in your Wordpress admin create two additional pages with permalinks `/account/` and `/changepass/` with "Coinwink - Account" and "Coinwink - Changepass" templates. In `/account/` page add the following shortcode: `[custom-register-form]`. In Wordpress "Settings->General" check "Anyone can register". Then activate "Coinwink Login" plugin. 

Open `backend_email.php` in your browser to get initial data for the app. After that, you can open and start using your newly installed Coinwink app.
<br>
<br>

**Cron jobs**

PHP files starting with `backend_` are scripts for checking prices and sending alerts.

`backend_email.php` in particular also gets coin data as JSON and puts it into the local MySQL database - this helps to avoid too many direct calls to the API. During the Coinwink page load, the page takes this JSON from MySQL and presents it as price information for each coin, and also creates option values for the coins drop-down list.

The `backend_` PHP scripts need to run every few minutes. On the production version the cron for these scripts is set to */3 minutes. Keep in mind that coinmarketcap.com API updates every 5 minutes.
<br>
<br>

**Additional notes**

For filterable drop-down select the [Select2](https://select2.github.io/) is being used.

For downloading the new coin logos and for updating the existing ones, a separate script was created and is available at this [cryptocurrency-logos](https://github.com/dziungles/cryptocurrency-logos) repository.
