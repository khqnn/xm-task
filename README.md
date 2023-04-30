# CodeIgniter 4 Application Starter


## Installation & updates
- install php
- install composer
- run command `composer update` https://codeigniter.com/user_guide/installation/installing_composer.html
- to run the test  `vendor/bin/phpunit` https://codeigniter.com/user_guide/testing/overview.html
- to run the project `php spark serve` https://codeigniter.com/user_guide/installation/running.html


## Setup

standard env for ci4 project can be used from the project with along with following environment variables  
- API_KEY  
- HISTORICAL_DATA_URL  
- SYMBOLS_URL  


## Server Requirements

PHP version 7.4 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
