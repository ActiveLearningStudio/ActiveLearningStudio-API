
Tsugi PHP Sample Module
=======================

This is a component of the [Tsugi PHP Project](https://github.com/tsugitools/tsugi).

There are two ways to use the Tsugi library/framework:

* You can build a "Tsugi Module" from scratch following all of the
Tsugi style guidance, using the Tsugi browser environment, and
making full use of the Tsugi framework - this repository contains
a basic "Tsugi Module" you can use as a starting point.

* You can use Tsugi more like a library and add it to an existing
application.   We also have starting code for
[Using Tsugi With an Existing Application](https://github.com/tsugiproject/tsugi-php-standalone)

Both of these approaches depend on the 
[Tsugi Devloper/Admin Console](https://github.com/tsugiproject/tsugi)
for database configuration, setup, developer test harness, 
CASA support, Content Item Support, etc.

The idea is that when you are starting a new Tsugi Module, you download 
the code for this application as your starting point and then edit from there.
It is probably a bad idea to fork this repository as you don't really want to 
track updates to this sample code.

Simple Installation
-------------------

In the simple installation scenario, you have installed and configured 
Tsugi to a folder like:

    htdocs/tsugi

Since Tsugi can be configured to discover PHP tools in lots of folders, you
could check this code out into one of several places:

    htdocs/tsugi/mod/tsugi-php-module
    htdocs/tsugi/tsugi-php-module
    htdocs/tsugi-php-module  (suggested)
    htdocs/php-intro/tools/tsugi-php-module

Once you have checked this code out, you need to make a config.php that
simply includes the `config.php` from the Tsugi directory. There is 
already a `config.php` that paints to the Tsugi configuration if you 
are in the suggested location of a peer folder.

    <?php 
    require_once "../tsugi/config.php";

You will also need to inform Tsugi to search the new tool's folder
for files like `index.php`, `register.php`, and `database.php`.
To do this, edite the `$CFG->tool_folders` parameter in the 
Tsugi `config.php` file to include the relative path to this tool.

    $CFG->tool_folders = array("admin", "mod", ... ,
         "../tsugi-php-module");

Once you have connected this tool to a Tsugi install as described above,
you can use the Admin/Database Upgrade feature to create / maintain database
tables for these tools.  You can also use the Developer mode of that Tsugi to
test launch this tool.   The LTI 2.0 support, CASA Support, and Content Item
support for the controlling Tsugi will know about this tool.

Launching and Testing This Code
-------------------------------

To launch the tools (assuming installed as described above) go to:

    http://localhost/tsugi/dev.php
    http://localhost:8888/tsugi/dev.php  (for MAMP)

And all these tools should show up in the drop-down for easy testing
and launching.

Tsugi Developer List
--------------------

Once you start developing Tsugi Applications or Modules, you should join the Tsugi
Developers list so you can get announcements when things change.

    https://groups.google.com/a/apereo.org/forum/#!forum/tsugi-dev

Once you have joined, you can send mail to tsugi-dev@apereo.org

Advanced Installation
---------------------

If you are going to install this tool in a web server that does not
already have an installed copy of Tsugi, it is a bit trickier.  There
is no automatic connection between Tsugi developer tools and Tsugi admin 
tools won't know about this tool.

To run this Tsugi Module as standalone, you should read and adapt the installation
steps in the
[Advanced Installation](https://github.com/tsugiproject/tsugi-php-standalone#advanced-installation)
section of the
[Using Tsugi With an Existing Application](https://github.com/tsugiproject/tsugi-php-standalone)
respository.

