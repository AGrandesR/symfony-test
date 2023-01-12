# Symfony tutorial

First of all we have two options:
``` bash
symfony new appName # To create a easy api with symfony
symfony new appName --webapp # To create a webpage (more files and folders)
```

For database, we change the **Database_URL** in the env file.
``` env
DATABASE_URL=DATABASE_URL="mysql://user:password@localhost:port/dbname?serverVersion=8&charset=utf8mb4"
```

## Doctrine
To create database only need to use php commands:
```
php bin/console doctrine:database:create
```

To create tables:

```
php bin/console make:entity
```
After use the command, you will have to indicate the name of the entity (the database table) and the properties (each of the field of the table)

When you finish to create the table, you can make the changes in the database with:
``` bash
php bin/console doctrine:schema:update --force
```

### User entity
You can create a user entity in symfony easy with:
``` bash
php bin/console make:user 
```

### Associations
https://symfony.com/doc/current/doctrine/associations.html


## Controllers

To create a controller in symfony you only need to use the next command:
```
php bin/console make:controller
```