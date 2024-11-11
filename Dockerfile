# The base image which include PHP 8.0 and Apache
FROM php:8.0-apache

# The working dir inside the container where website files will be stored
WORKDIR /var/www/html

# This update the package manager inside the contiainer, making sure that it is up to date.
RUN apt update -y && apt upgrade -y

# InstallsDoc and enables mysqli extension which allows PHP to connect to MySQL databases
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli