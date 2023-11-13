## Overview

* [Installation](#installation)
* [Environment](#environment)
* [Laravel](#laravel)
    * [Artisan](#artisan)
    * [File storage](#file-storage)
* [Makefile](#makefile)
* [Aliases](#aliases)
* [Database](#database)
* [Redis](#redis)
* [Mailhog](#mailhog)
* [Logs](#logs)
* [Running commands](#running-commands-from-containers)
* [List of important commands](#list-of-important-commands)

## Installation
**1. Make sure you have [Docker ](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04) and [Docker Compose](https://docs.docker.com/compose/install) installed**

**2. Run the installation script (it may take up to 10 minutes)**
```
make install
```
on Windows:
```
make install-win
```

**3. That's it.**

Open [http://localhost:8080](http://localhost:8080) url in your browser.

#### Manual installation
If you do not have available the make utility or you just want to install the project manually, you can go through the installation process running the following commands:

**Build and up docker containers (It may take up to 10 minutes)**
```
docker-compose up -d --build
```

**Install composer dependencies:**
```
docker-compose exec php composer install
```

**Set up laravel permissions**
```
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
```

**Restart the node container**
```
docker-compose restart node
```

## Environment
To up all containers, run the command:
```
# Make command
make up

# Full command
docker-compose up -d
```

To shut down all containers, run the command:
```
# Make command
make down

# Full command
docker-compose down
```


#### Artisan
Artisan commands can be used like this
```
docker-compose exec php php artisan migrate
```

If you want to generate a new controller or any laravel class, all commands should be executed from the current user like this, which grants all needed file permissions
```
docker-compose exec --user "$(id -u):$(id -g)" php php artisan make:controller HomeController
```

However, to make the workflow a bit simpler, there is the _aliases.sh_ file, which allows to do the same work in this way
```
artisan make:controller HomeController
```
[More about aliases.sh](#Aliases)

#### File storage
Nginx will proxy all requests with the `/storage` path prefix to the Laravel storage, so you can easily access it.
Just make sure you run the `artisan storage:link` command (Runs automatically during the `make install` process).

## Makefile
There are a lot of useful make commands you can use.
All of them you should run from the project directory where `Makefile` is located.

Examples:
```
# Up docker containers
make up

# Apply the migrations
make db-migrate

# Run tests
make test

# Down docker containers
make down
```

Feel free to explore it and add your commands if you need them.

## Aliases
Also, there is the _aliases.sh_ file which you can apply with command:
```
source aliases.sh
```
_Note that you should always run this command when you open the new terminal instance._

It helps to execute commands from different containers a bit simpler:

For example, instead of
```
docker-compose exec php php artisan migrate
```

You can use the alias `from`:
```
from php php artisan migrate
```

But the big power is `artisan` alias

If you want to generate a new controller or any Laravel class, all commands should be executed from the current user, which grants all needed file permissions
```
docker-compose exec --user "$(id -u):$(id -g)" php php artisan make:model Post
```

The `artisan` alias allows to do the same like this:
```
artisan make:model Post
```

## Database
If you want to connect to the MySQL database from an external tool use the following parameters
```
HOST: localhost
PORT: 33061
DB: app
USER: app
PASSWORD: app
```

## Redis
To connect to redis cli, use the command:
```
docker-compose exec redis redis-cli
```

If you want to connect with external GUI tool, use the port ```54321```

## Mailhog
If you want to check how all sent mail look, just go to [http://localhost:8026](http://localhost:8026).
It is the test mail catcher tool for SMTP testing. All sent mails will be stored here..

## Logs
All **_nginx_** logs are available inside the _docker/nginx/logs_ directory.

All **_supervisor_** logs are available inside the _docker/supervisor/logs_ directory.

To view docker containers logs, use the command:
```
# All containers
docker-compose logs

# Concrete container
docker-compose logs <container>
```

## Running commands from containers
You can run commands from inside containers' cli. To enter into the container run the following command:
```
# PHP
docker-compose exec php bash

# NODE
docker-compose exec node /bin/sh
```

## List of important commands
**Start/stop the container**
```
make up
make down
make restart
```
**Status**
```
make s
```
**List all logs**
```
make logs
```
**Live node log**
```
make logs-n
```
**Composer**
```
make composer-install
make composer-update
```
**NPM**
```
make npm-update
```
**Restart node**
```
make rn
```
**Build**
```
make build
```
OR
```
make build-no-cache
```
**Container cli**
```
make php
make node
```
**DB Migrations**
```
make migrate
make rollback
make db-seed
make db-fresh
```
**Permissions**
```
make perm
```
**Apply aliases**
Have to run in every terminal instances
```
source aliases.sh
```
**Artisan commands**
With aliases
```
artisan make:controller HomeController
```
Without aliases
```
docker-compose exec --user "$(id -u):$(id -g)" php php artisan make:controller HomeController
```
