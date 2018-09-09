[![Build
Status](https://travis-ci.org/Atrylon/NamekBankProApi.svg?branch=master)](https://travis-ci.org/Atrylon/NamekBankProApi)
#Namek Bank Pro API

**Symfony course projet :**

A Symfony API for a bank to manage her users with their company and creditcards.

##Getting Started

###Installing

First you will need a database :
You can start with a clean database. For this, you have to create a 
new DB, rename the **.env.dist** file in **.env** then update the database URL in the **.env file** and finally use : ``php bin/console d:s:u -force``

As we use fixtures, you've to use ```php bin/console hautelook:fixtures:load --purge-with-truncate``` to generate masters, companies
and creditcards required for the tests.


You have to run the ``composer install`` command.

The next thing to do is to launch the server : ``php bin/console server:run``

You can now use function which are in the Controllers (get all, get one, post, put, delete, ...) !

###Commands

Two commands are available is this project:
* You can create an admin while using ``php bin/console app:create-admin EMAIL FIRSTNAME LASTNAME``
* You can count the number of creditcards with the following command : ``php bin/console app:user-count-creditcards``

###Built With
* [Symfony 4](https://symfony.com/4) - The Web framework used
* [PHPUnit](https://phpunit.de/) - The PHP Testing Framework
* [Alice Bundle](https://github.com/nelmio/alice) - A bundle to create fake data
* [Faker](https://github.com/fzaninotto/Faker) - PHP library to generate fake data
* [PhpMyAdmin](https://www.phpmyadmin.net/) - The software for the Database
* [Postman](https://www.getpostman.com/) - API Development Environment

##Author
**Berenger Desgardin** - *Initial work* - [Atrylon](https://gihub.com/Atrylon)
