# How to use
Hi! To see the example of this API you will need to follow the next steps:
 - First install depencies:
``` bash
composer install
```
 - Next you will have to update the .env file or create a copy .env.local with your database info.
``` env
DATABASE_URL="mysql://dbUser:dbPassword@localhost:dbPort/dbName?serverVersion=8&charset=utf8mb4"
```
 - Important replace *dbUser*,*dbPassword*,*dbPort* and *dbName* for your own database data.
 - Next you will be avaible to create the database with the next command.
``` bash
php bin/console doctrine:database:create
```
 - Now you can use the follow command to import the first three elements of the Star Wars api.
``` bash
php bin/console starwars import
```
¡Now the importation is done!

## Check the process

You can up the server to check the endpoint homepage with the data for characters in database.
``` bash
symfony serve
```
And in the page ```/homepage``` you can look a json with the saved data.

<!--
# History

## Command creation
We make the command, following the symfony documentation.
``` bash
php bin/console make:command
```

## Creation of the table
We make the entities, following the symfony documentation.
``` bash
php bin/console make:entity
```
-->