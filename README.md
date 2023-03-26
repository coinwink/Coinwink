## Coiwnink V2 ##

In this repository, you will find the source code of the <a href="https://coinwink.com" target="_blank">Coinwink</a> app version 2, which is built using Laravel and Vue.js.

The previous V1 version of Coinwink was developed using WordPress and jQuery, and it was live from 2016 to 2022. You can find the first version archived under the Releases section. While the Coinwink V1 was optimized and fast, it was difficult to maintain and develop new features. As a result, the app was migrated to Laravel and Vue.js to improve maintainability and the speed of development. 

The new Coinwink V2 includes some code from the V1 that was retained for its optimization and has been transferred unchanged from the previous framework to the new one. However, the overall architecture of the app is now based on Laravel for the backend and Vue.js for the frontend, which allows for easier maintenance and development of new features.

#### Quickstart ####

- Clone this repository.
- Run `composer install` and `npm install` commands.
- Rename `.env.example` file to `.env` and update it with your credentials. You need to provide at least the `DB_DATABASE` value for the app to work.
- Update `coinwink_auth_sql.php` file in `public` folder with your database credentials.
- Run the `php artisan migrate` command to create database tables.
- Generate your application encryption key by running the `php artisan key:generate` command.
- For local environment, set up a virtual host with its path pointing to Laravel's `public` folder. Then update the virtual host URL in the `webpack.mix` file as the proxy value.
- Copy contents from the <a href="https://github.com/coinwink/cryptocurrency-logos" target="_blank">cryptocurrency-logos</a> repository to `/public/img` folder to have the following structure `/public/img/coins`.
- For hot reload, run the `npm run watch` command.
- For a production build, run the `npm run build` command.

#### More info ####

To create a new user, first update the `.env` file with your mail server credentials, and then use the user sign up form.

To use all app's features, you need to upgrade your user to Premium by manually editing the database. First, open the `cw_settings` table and edit your user: set `subs` column to `1`, and enter any amount of SMS credits in the `sms` column. Then create a new record in the `cw_subs` table with your user id, set `status` column to `active` and `plan` column to `premium`. 

To get new market price data from CoinMarketCap (for alerts, portfolio, and watchlist), register for the [Free CMC API plan](https://coinmarketcap.com/api/pricing/), and then update the `coinwink_auth_cmc.php` file with your credentials. To fetch new data from CMC, run `cron_data_cmc.php` script.

To trigger and send crypto alerts by email, SMS, and Telegram, run the following scripts:
* `cron_alerts_email_cur.php` - Email price alerts
* `cron_alerts_email_per.php` - Email percentage alerts
* `cron_alerts_sms_cur.php` - SMS price alerts
* `cron_alerts_sms_per.php` - SMS percentage alerts
* `cron_alerts_tg_cur.php` - Telegram price alerts
* `cron_alerts_tg_per.php` - Telegram percentage alerts
* `cron_alerts_portfolio.php` - Portfolio alerts

To receive SMS alerts, sign up on [Twilio](https://www.twilio.com/), get your API keys, and update the `coinwink_auth_sms.php` file. Then run the above mentioned SMS scrips to trigger and send SMS alerts.

For currency rates conversion, two services are utilized. For more info, see the `cron_data_cur_rates.php` file.

`/img/coins` folder is for cryptocurrency logos. Our script to update/fetch new logos can be found [here](https://github.com/coinwink/cryptocurrency-logos).

To migrate from WordPress users, the [mikemclin/laravel-wp-password](https://github.com/mikemclin/laravel-wp-password) tool is being used. When an old WordPress user tries to log in, the user is automatically moved to Laravel's authentication system by adding the user to the database `users` table with the user's password rehashed for Laravel.

The database migrations were generated from the existing database with the help of the [kitloong/laravel-migrations-generator](https://github.com/kitloong/laravel-migrations-generator) tool.

#### License ####

Coinwink's source code is available for personal and non-commercial use only. For more details, see the [LICENSE](https://github.com/coinwink/Coinwink/blob/master/LICENSE).

#### Contribution ####

You are welcome to report issues, provide your feedback, or suggest ideas and improvements. For similar communication, use the [issue tracker](https://github.com/coinwink/Coinwink/issues) or contact us by email.

#### Screenshots ####

<img src="https://coinwink.com/img/screenshots/2023/01-coinwink-features.png?v=001"><br><br>
<img src="https://coinwink.com/img/screenshots/2023/02-coinwink-mobile.png?v=001"><br><br>
<img src="https://coinwink.com/img/screenshots/2023/03-coinwink-portfolio.png?v=001"><br><br>
<img src="https://coinwink.com/img/screenshots/2023/04-coinwink-watchlist.png?v=001"><br><br>
<img src="https://coinwink.com/img/screenshots/2023/05-coinwink-new-telegram-alert.png?v=001"><br><br>
<img src="https://coinwink.com/img/screenshots/2023/06-coinwink-mobile-matrix.png?v=001"><br>