# Todo List

# Summary

This Todo List project can run entirely on the docker system (database, http server, backend and frontend), and the application logic is implemented by the Vue.js and the Laravel.

# How to run it

## Install Docker Engine

[Docker official docs](https://docs.docker.com/engine/install/)

## Clone this project

`git clone git@github.com:coosigma/todo_list.git`

## File system permission

If you use Linux as the host OS system of docker, you need to change the permissions of the shared directory:

```Shell
sudo chmod -R 777 todo_list
```

## Build docker images and containers

```Shell
cd todo_list
docker-compose up
```

## Install dependencies

Sending commands below when the docker containers are running:

```Shell
# front-end
docker exec -it node-frontend npm install -C /home/node/app
# back-end
docker exec -it php-xdebug composer install -d /var/www/html
```

## Database Migration, Seeding and Unit Test

Sending commands below when the docker containers are running:

```Shell
# Migration
docker exec -it php-xdebug /var/www/html/artisan migrate
# Seeding
docker exec -it php-xdebug /var/www/html/artisan db:seed
# Unit test
docker exec -it php-xdebug /var/www/html/vendor/bin/phpunit
```

## Visit the application

Open your browser and type in `127.0.0.1`

## Typical operations

You can register your own account, log in to the account, and "CRUD" your todos. In addition, you can also see the distribution of your unfinished todos in the past 60 minutes through a line chart.
