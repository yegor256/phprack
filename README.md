<img src="http://img.phprack.com/logo.png" style="width: 137px; height: 36px;"/>

[![Managed by Zerocracy](http://www.zerocracy.com/badge.svg)](http://www.zerocracy.com)
[![DevOps By Rultor.com](http://www.rultor.com/b/yegor256/phprack)](http://www.rultor.com/p/yegor256/phprack)

[![Build Status](https://travis-ci.org/yegor256/phprack.svg?branch=master)](https://travis-ci.org/yegor256/phprack)

Read about phpRack in ​[php|Architect June 2010](http://www.phparch.com/magazine/2010/june/):
"Integration Testing with phpRack Framework".

**phpRack** is a light framework for automation of integration tests. By
integration tests we mean software modules that should be executed in the
production environment, in order to validate that said environment is configured
as expected. For example your product is a web2.0 application which depends on
proper configuration of PHP, Apache, MySQL and availability of YouTube, Flickr
and GoogleMaps API's. Your product is properly tested with unit tests (obviously
you're using stubs for said services and components). When the product is
deployed to the production environment, you want to be sure that the services
you need are configured and available. If they are not — you want to get a
notification about it before your end-users. And you want to get a detailed
notification.

<img src="http://img.phprack.com/diagram.png" style="width: 530px; height: 243px;"/>

This is when phpRack is mandatory. You shall add phpRack to your project, and
write a number of tests. All these tests will be executed when requested and
will produce a detailed report, both only and by email. It will save you a lot
of time during deployment and later, during maintenance of your product.

## Quick Start

To start using phpRack you should do three operations:

 * Upload phpRack library to your server
 * Create `phprack.php` file in your `public_html` directory
 * Create PHP integration tests in your rack-tests directory

Let's do them one by one:

### Upload phpRack library

Download [ZIP archive](https://github.com/yegor256/phprack/archive/master.zip)
of phpRach and unpack it to `public_html/phpRack` or some other directory on
your production server.

### Create `phprack.php`

You should create `phprack.php` in your project's public directory
(see [full reference](https://github.com/yegor256/phprack/wiki/Bootstrap)), e.g.:

```php
<?php
// this param is mandatory, others are optional
$phpRackConfig = array(
    'dir' => '../rack-tests',
);
// absolute path to the bootstrap script on your server
include '../library/phpRack/bootstrap.php';
```

### Create integration tests

Write integration tests in the directory rack-tests, each one has to extend the
class `PhpRack_Test` (see
[full list of assertions](https://github.com/yegor256/phprack/wiki/Assertions)).
For example, file `MyTest.php`:

```php
<?php
class MyTest extends phpRack_Test
{
    public function testPhpVersionIsCorrect()
    {
        $this->assert->php->version
            ->atLeast('5.2');
    }
    public function testPhpExtensionsExist()
    {
        $this->assert->php->extensions
            ->isLoaded('xsl')
            ->isLoaded('simplexml')
            ->isLoaded('fileinfo');
    }
}
```

Go to this URL: `http://your-website-url/phprack.php` and enjoy. Try this link
to see what you're going to see on your site:
​[http://www.phprack.com/phprack.php](http://www.phprack.com/phprack.php).

## How to contribute?

First, fork our repository and clone it to your local machine and install
[Vagrant](http://www.vagrantup.com/). Then, run:

> vagrant up

A virtual machine with pre-installed requisites will be ready in a few
minutes. Login to it and go to `/vagrant` directory:

> vagrant ssh

Then, in the virtual machine run:

> phing

All tests should pass. If you see any problems, please submit an
new issue to us.

After you make your changes don't forget to run `phing` again to make
sure you didn't break anything. When ready, submit a pull request.

DISCLAIMER: THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/tpc2/phprack/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

