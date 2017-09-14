louvre
======

A Symfony project created on September 1, 2017, 3:15 pm.

This project is part of an online course to get a certification as
a Web Project Manager.

To install this application, the following are required :
* A web server running Apache 
* PHP 5.5.9 at minima (version 7 is recommended)
* A MySQL database
* composer should be installed on the machine you are
going to use for this application. (see https://getcomposer.org/)


INSTALLATION :
* Create a folder on your server to receive the application.
* Download the all the files from Github into the folder on
your server.
* Once finished, run composer install to get all the dependencies
of this application. Wait for it to finish.
* Open the file parameters.yml and enter your database name, username
and password as well as the details for your mailer (see with your host
provider);

DATABASE SETUP :

On your root folder run the following commands :
* php bin/console doctrine:database:create (this will create the
database)
* php bin/console doctrine:migrations:migrate Answer "y" at the 
question (this will create your tables in the database)
* php bin/console app:load-data (this will load basic data in the
database)

Your web site is now up and running.




