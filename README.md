# How to use
Hi! To use this API you will need to:
 - Composer install
 - Create env file with your database
 - php bin/console doctrine:database:create
 - php bin/console starwars import


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