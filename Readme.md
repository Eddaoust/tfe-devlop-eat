# Devlop Eat

**This app helps the company to know the progress of several construction projects**

## Requirements

* Symfony 3.4 (LTS)
* PHP 7.0.8 Or higher
* MySQL 5.7
* NodeJS 6.4 Or higher
* NPM 1.15 Or higher
* [Puppeteer](https://github.com/GoogleChrome/puppeteer "Link to Puppeteer github repository")

## Installation

* Create a local directory
```
$ mkdir devlop-eat
```
* Clone the github repository
```
$ git clone https://github.com/Eddaoust/tfe-devlop-eat.git
```
* Go to the local directory
```
$ cd tfe-devlop-eat
```
* Install project dependancies
```
$ php composer.phar install
```
* Database Setup

    Edit the line 18 of .env file to setup your database login & password
* Create the database
```
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
```
* Load fixtures
```
$ php bin/console doctrine:fixtures:load --no-interaction
```
* Install node modules
```
$ npm install
```
* Launch the app
```
$ php bin/console server:run
```

## Notes

* Update Diagrams with PlantUML: 
```
$ cd /doc/diagrams
$ java -jar plantuml.jar database.txt
```
* Add crontab to generate pdf 
```
$ * * * * * php PATH_TO_BIN/CONSOLE app:generate-pdf
```