# Task Application

### Technologies:
<p align="center">
    <a href="#">
        <img src="https://img.shields.io/badge/-PHP-f5f5f5?style=for-the-badge&amp;labelColor=grey&amp;logo=PHP&amp;logoColor=white" alt="PHP" style="max-width:100%;">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/-Laravel-red?style=for-the-badge&amp;labelColor=red&amp;logo=LARAVEL&amp;logoColor=white" alt="laravel" style="max-width:100%;">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/-MYSQL-075b9a?style=for-the-badge&amp;labelColor=black&amp;logo=Mysql&amp;logoColor=white" alt="MYSQL" style="max-width:100%;">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/-Docker-61dafb?style=for-the-badge&amp;labelColor=black&amp;logo=docker&amp;logoColor=61dafb" alt="docker" style="max-width:100%;">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/-CSS3-0099cc?style=for-the-badge&amp;labelColor=grey&amp;logo=CSS3&amp;logoColor=0099cc" alt="CSS" style="max-width:100%;">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/-JAVASCRIPT-yellow?style=for-the-badge&amp;labelColor=3c3c3c&amp;logo=JAVASCRIPT&amp;logoColor=yellow" alt="docker" style="max-width:100%;">
    </a>
</p>

### Getting started:
1. Fork this Repository
1. change the current directory to project path
   ex: ```cd tasks```
1. make the database folder ```mkdir mysql```
1. ``` docker-compose build && docker-compose up -d ```
   <p style="background-color:#f8d7da;padding:2px 10px;font-size:13px;margin:10px;color:#721c24;"><span style="font-weight:bolder">alert:</span> if there is a server running in your machine, you should stop it or change port 80 in docker-compose.yml to another port(8000)</p>
1. install dependencies with composer ```cd src && composer install```, if you are in a production server and composer is not installed, you can install the dependencies from docker environment ``` docker-compose exec php /bin/sh``` then, ```composer install```
1. run ``` docker-compose exec php php /var/www/html/artisan migrate --seed```

<p style="background-color:#d1ecf1;padding:2px 10px;font-size:13px;margin:10px;color:#0c5460;"><span style="font-weight:bolder">Info:</span> if you want only the Laravel project, copy the  <b><i> /src </i></b> folder to wherever you want and  make database with name <b><i>store</b></i>, then generate key
<b>php artisan key:generate</b>, then <b><i>php artisan serve</i></p>
