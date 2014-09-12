ignite.php
======

A simple PHP script that deletes unwanted files and folders then installs [Composer](https://getcomposer.org/) (a dependency manager for PHP) and [Doctrine](http://www.doctrine-project.org/) (an object-relational mapper for PHP 5.3.3+) for CodeIgniter 3 be ready for writing web applications.

Instructions
============
1. Clone the latest version of CodeIgniter (in the develop branch).

  ```git clone https://github.com/EllisLab/CodeIgniter.git```
  
2. Clone this repository.

  ```git clone https://github.com/rougin/ignite.php.git```
  
  After cloning, copy the **ignite** file to the **CodeIgniter** directory.
3. Open the cloned **CodeIgniter** directory then run **ignite.php** from PHP CLI.

  ```php ignite.php```

TODO
====

* Convert ignite as a single class.
* Allow users to set up their required libraries via the command line
* Add a code generator for CodeIgniter (maybe in a seperated repository)