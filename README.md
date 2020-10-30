<img src="https://coinwink.com/img/coinwink-logo-horizontal-git.png" width="160">

#### Crypto Alerts, Watchlist and Portfolio Tracking App ####

[Coinwink](https://coinwink.com) was first built as a Wordpress web-app in 2016, and even now (2020) it continues to run on the same stack.

While it is tempting to switch to Laravel + Vue, the current Wordpress + jQuery stack runs great. It is fast, simple and secure, easy to maintain and update.

Only the user accounts (more than 30k) are on Wordpress, and the app uses WP MySQL queries. The backend scripts, responsible for alerts delivery, are independent PHP files.

The front-end is a custom-made web-app layout, based on jQuery.

Right now the whole app code is very monolithic. In the future, the idea is to make the code more modular, and maybe start using Wordpress plugins/templates system for optional and/or community developed features and themes.

There is also an idea to make Coinwink front-end without jQuery. The modern JavaScript is already great and getting better with each year, so in the future Coinwink's frontend can run on vanilla JS.

This repository includes all of the latest Coinwink app's source code, including the Matrix theme. The payments module and the captcha plugin are not part of this repository because they are not required for individual Coinwink use.

<br>

****Quickstart****

- Make a fresh Wordpress install.
- Git clone or manually copy all files from this repository to your Wordpress installation folder.
- Update the theme's `header.php` file: edit the `coinwinkEnv` variable and set the `homePath` variable to your Wordpress location.
- Update `auth_sql.php` file with your database login details.
- First of all, in Wordpress "Settings->General" check "Anyone can register".
- Only then activate Coinwink login plugin.
- Finally, activate Coinwink theme.

Done!

Open your Wordpress site in your browser. You should see the Coinwink app.

The order of the Quickstart steps is important. Otherwise, you will lock yourself out of Wordpress admin panel. On Coinwink theme's activation, it automatically creates the required pages and sets them to use the correct templates. Additionally, it creates database tables and adds an example CoinMarketCap dataset, and example currency rates. It also promotes the admin user to Premium.

<br>

****More info****

To get new market price data from CoinMarketCap (for alerts, portfolio, and watchlist), register for the [Free CMC API plan](https://coinmarketcap.com/api/pricing/), and then update your `auth_cmc.php` and `auth_sql.php` files with your credentials.

To fetch new data from CMC, run `cron_data_cmc.php` script.

For new users to receive account activation emails, configure your email settings in `auth_email_functions.php` php file.

To trigger and send alerts by email and SMS, run the following scripts:
* `cron_alerts_email_cur.php` - Email price alerts
* `cron_alerts_email_per.php` - Email percentage alerts
* `cron_alerts_sms_cur.php` - SMS price alerts
* `cron_alerts_sms_per.php` - SMS percentage alerts
* `cron_alerts_portfolio.php` - Portfolio alerts

To set the above scripts on auto, use cron jobs.

To receive SMS alerts, sign up on [Twilio](https://www.twilio.com/referral/UOxUVG) (click the link to get 10$ free), get your API keys, and update the `auth_twilio.php` file. Then run the above mentioned SMS scrips to activate and send SMS alerts.

For currency rates conversion, two services are utilized. More info: `cron_data_cur_rates.php`.

`/img/coins` folder is for cryptocurrency logos. Our script to update/fetch new logos can be found [here](https://github.com/giekaton/cryptocurrency-logos).

<br>

****Screenshots****

<img src="https://coinwink.com/img/screenshots/screenshot-navigation.png?v=001" width="800"><br><br>
<img src="https://coinwink.com/img/screenshots/screenshot-manage-alerts.png?v=001" width="800"><br><br>
<img src="https://coinwink.com/img/screenshots/screenshot-watchlist.png?v=001" width="800"><br><br>
<img src="https://coinwink.com/img/screenshots/screenshot-portfolio.png?v=001" width="800"><br><br>
<img src="https://coinwink.com/img/screenshots/screenshot-mobile-experience.png?v=002" width="800"><br><br>
<img src="https://coinwink.com/img/screenshots/coinwink-matrix-theme-crypto-alert-view.png?v=001" width="800"><br><br>
<img src="https://coinwink.com/img/screenshots/coinwink-matrix-theme-portfolio-view.png?v=001" width="800"><br><br>
<img src="https://coinwink.com/img/screenshots/coinwink-matrix-theme-watchlist-view.png?v=001" width="800"><br><br>
<img src="https://coinwink.com/img/screenshots/screenshot-coinwink-matrix-mobile-views-2.png?v=001" width="800"><br><br>


****Development and Contribution****

Coinwink's source code available in this repository is for personal use, also for review, educational, and in general - for non-commercial purposes. Intellectual property assets are protected by law.

For your personal needs, you are allowed to run Coinwink app in your own development environment and to use it as much as you need, without any limits.

You are welcome to report issues, send us your feedback or suggest ideas and improvements. For similar communication, use the issue tracker or contact us by email.

You can find more information about the latest Coinwink developments on our blog:<br>
[New Updates (Aug, 2020)](https://coinwink.com/blog/new-updates-aug-2020)<br>
[Coinwink Matrix theme (July, 2020)](https://coinwink.com/blog/coinwink-matrix)<br>
[New updates and features (June, 2020)](https://coinwink.com/blog/new-features-and-updates)<br>