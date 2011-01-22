cuke4php
========

This project implements the cucumber wire protocol for PHP projects.
Information about the wire protocol: http://wiki.github.com/aslakhellesoy/cucumber/wire-protocol

Using this protocol it is possible to directly interact with PHP code at any level without the need for a web server.  To accomplish this, when cucumber is running against a directory containing feature files and it cannot resolve a particular step definition, it will ask a known wire server (as defined in a .wire file) to interpret and run those steps.

Install
-------
To install Cuke4Php, follow these steps:

* git clone git://github.com/olbrich/cuke4php.git
* add or make sure that cuke4php is in your path
* ensure that the 'cuke4php' bash script is executable ('chmod +x cuke4php' on *nix systems)

Usage
-----
* run 'cuke4php path/to/features' from the command line
* make sure your cucumber features has a 'Cuke4Php.wire' file containing the appropriate information (see cuke4php/features/step_definitions for an example).
* invoke cucumber from your project, it should talk to the Cuke4Php server when it can't find a native ruby step definition

Note: you will need to restart the Cuke4Php server to pick up any changes.

Roadmap
-------
Things coming soon:
  * packaging into a ruby gem to simplify installation
  * a simplified way to start the Cuke4Php server, run features, and then terminate the server for a single run
  * a way to dynamically assign the port the Cuke4Php server uses, which will allow running multiple concurrent Cuke4Php servers

Dependencies
------------
* Cucumber (see http://cukes.info)
* PHPUnit (see http://www.phpunit.de/)

Goals
-----
This project utilizes PHPUnit because it has a robust set of assertions, has good mocking, and is widely used.  This will facilitate adoption by developers who are already familiar with it.

This project was developed against the 5.2.x versions of PHP, to ensure compatibility with older PHP projects.

Support:
	Support for this project was provided by iContact, inc.  (http://www.icontact.com)
