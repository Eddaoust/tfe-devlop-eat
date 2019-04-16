# Devlop Eat

**This app helps the company to know the progress of several construction projects**

## Requirements

* Symfony 3.4 (LTS)
* PHP 5.5.9 Or higher
* MySQL 
* [Wkhtmltopdf](https://wkhtmltopdf.org/ "Link to WkHtmlToPdf website")

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
* Launch the app
```
$ php bin/console server:run
```

* Update Diagrams with PlantUML: 
```
$ cd /doc/diagrams
$ java -jar plantuml.jar database.txt
```