Rserve-php
==========

php5 client for Rserve http://www.rforge.net/Rserve/ (a TCP/IP server for R statistical software)

NEW:
-----
New version of this project with new features, namespace is available on 2.0 branch of this project. It will be released soon.
For a new project prefer this new branch. 

Maturity
-----------------------

The library provide several way to parse R structures ($parser parameter in evalString)

- native php array : you get only the results in nested php array (without R attributes). It's a good way to handle simple R results.
This feature is in beta and has unit tests

- wrapped native php array : you get the results in a simple object (RNative) with results as php array and you can access to R object attributes.

- Debug : full description of the Rserve protocol results

- REXP: All R structures are wrapped in an REXP_* class
This feature is in alpha: not very documented, tests in progress. Only low level R structures are handled (data.frame is a GenericVector, etc..)

Tests
-----

You can run tests using phpunit

* Create a file config.php in the "tests" directory (copy config.php.sample)
* define the constant RSERVE_HOST with the address of your Rserve server (custom port not supported yet)
* run tests
  . phpunit tests\ParserNativeTest.php
  . phpunit tests\SessionTest.php
  . phpunit tests\REXPTest.php
* define the constants RSERVE_PORT, RSERVE_USER, RSERVE_PASS to config.php (along with RSERVE_HOST)
* run test
  . phpunit tests\LoginTest.php


Usage
---------

The use of the library is simple

1. create an instance of Rserve_Connection

  $cnx = new Rserve_Connection('myserverhost');

2. Send R commands and get the results as Php array

  $result = $cnx->evalString('x ="Hello world !"; x');
  
  // Get results as a REXP object tree 
  $result = $cnx->evalString('x="Toto is my Hero"', Rserve_Connection::PARSER_REXP);
  
  // Get as wrapped native array (object with array behaviour and attributes)
  $result = $cnx->evalString('x="Toto is my Hero"', Rserve_Connection::PARSER_NATIVE_WRAPPED);
 
This will produce a php array containing R results (using native array parser). 
Others parsers could be used by using $parser parameters (@see Rserve_Connection)


Using Login Authorization
-------------------------
Usage is the same as the vanilla usage, except for the constructor
   $cnx = new Rserve_Connection('myserverhost',serverport,array('username'=>username,'password'=>password))


Async Mode
-----------

Several functions allow to use connection in async mode

* getSocket() to get the socket an set some options
* setAsync() allow to set the async mode
* getResults($parser) : get and parse the results after a call to evalString() in async mode

Files Description 
-------------------

* Connection.php : main class Rserve_Connection, you only need to manipulate an instance of this class (evalString method for now)
* helpers.php : helpers function librairies 
* Parser.php : Parser class used to Parse Rserve binary packets to php structures (native array or REXP children)
* RNative.php : an array wrapper used to catch attributes (experimental, usefull ?)
* REXP/*.php : R expression classes

Contacts
--------
Clément Turbelin, clement.turbelin@gmail.com
http://www.sentiweb.fr
Université Pierre et Marie Curie - Paris 6, France
