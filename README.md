Slap Registration model
======================= 

Introduction
------------
This is the multi purpose Registration system. Built for use with the Digital
River orders and expanded for other uses. Check if user is already registered and
updates order information iin database.

Unit Testing.
-------------
Instead of unit tests calling the application, the APPLICATION_ENV is set up to allow 
modules to use test data to enable debugging with ease. The request data is replaced
with either a file input or xml string, suitable for the request at hand.


Virtual Host
------------
Afterwards, set up a virtual host to point to the public/ directory of the
project and you should be ready to go!
