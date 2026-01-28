# Web Health Checking Framework

[![DevOps By Rultor.com](http://www.rultor.com/b/yegor256/phprack)](http://www.rultor.com/p/yegor256/phprack)

[![PDD status](http://www.0pdd.com/svg?name=yegor256/phprack)](http://www.0pdd.com/p?name=yegor256/phprack)
[![Hits-of-Code](https://hitsofcode.com/github/yegor256/phprack)](https://hitsofcode.com/view/github/yegor256/phprack)

Read about phpRack in [php|Architect June 2010][phpArchitect]:
  _Integration Testing with phpRack Framework_.

**phpRack** is a lightweight framework for integration test automation.
Integration tests are checks that run in the production environment
  to validate that it is configured as expected.
For example, your product is a web application that depends on
  PHP, Apache, MySQL, and the availability of YouTube, Flickr,
  and Google Maps APIs.
Your product is tested with unit tests
  that use stubs for these services and components.
When the product is deployed to production,
  you want to be sure that the services you need are configured and available.
If they are not, you want a detailed notification before your end-users notice.

This is where phpRack helps.
Add phpRack to your project and write a few tests.
All these tests will be executed when requested
  and will produce a detailed report, both online and by email.
It will save you a lot of time during deployment and later,
  during maintenance of your product.

To start using phpRack, follow these three steps:

* Upload phpRack library to your server
* Create `phprack.php` file in your `public_html` directory
* Create PHP integration tests in your rack-tests directory

Let's do them one by one:

Download [ZIP archive](https://github.com/yegor256/phprack/archive/master.zip)
  of phpRack and unpack it to `public_html/phpRack` or some other directory on
  your production server.

Create `phprack.php` in your project's public directory
  (see [full reference][ref]), e.g.:

```php
<?php
// this param is mandatory, others are optional
$phpRackConfig = array(
    'dir' => '../rack-tests',
);
// absolute path to the bootstrap script on your server
include '../library/phpRack/bootstrap.php';
```

Write integration tests in the `rack-tests` directory. Each test must extend
`phpRack_Test` (see
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

Go to this URL: `http://your-website-url/phprack.php` and enjoy.
Try this link to see what you're going to see on your site:
  [http://www.phprack.com/phprack.php](http://www.phprack.com/phprack.php).

## How to contribute?

Fork the repository, clone it to your local machine, and install dependencies:

```bash
composer install
```

Then run the build:

```bash
./vendor/bin/phing
```

All tests should pass.
If you see any problems, please submit a new issue.

After making your changes, run `phing` again to make sure you didn't break anything.
When ready, submit a pull request.

[phpArchitect]: http://www.phparch.com/magazine/2010/june/
[ref]: https://github.com/yegor256/phprack/wiki/Bootstrap
