In order to test phpRack in development environment you need
to install and configure:

    php 5.2
    xdebug
    phing
    phpunit
    phpcs
    phpmd
    jslint
    xmllint
    
In order to run phpRack in a web browswer locally you should configure
your apache with the following instructions (we assume that you checked
out phpRack source code to /code/phpRack):

    <Directory /code/phpRack/test/test-web-front>
        Allow from all
    </Directory>
    Alias /phpRack "/code/phpRack/test/test-web-front"

When it's done (don't forget to restart apache) you can use these
URLs:

    http://localhost/phpRack/phprack.php
    http://localhost/phpRack/QUnit.php
    
If any questions, don't hesitate to submit tickets to
http://www.phpRack.com.