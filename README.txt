DISCLAIMER:
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

CHAPTER 1. TESTING

	In order to test phpRack in the development environment you need
	to install and configure:

	    PHP 5.2 (http://www.php.net)
	    xdebug (http://www.xdebug.org)
	    Phing (http://www.phing.info)
	    PHPUnit (http://www.phpunit.de/)
	    PHPCS (http://pear.php.net/package/PHP_CodeSniffer)
	    PHPMD (http://www.phpmd.org)
	    jslint (http://www.jslint.com)
	    xmllint (http://xmlsoft.org/xmllint.html)
		jscoverage (http://siliconforks.com/jscoverage/)

	In order to run phpRack in a web browser locally you should configure
	your Apache HTTP Server with the following instructions (we assume that you checked
	out phpRack source code from Subversion to /code/phpRack):

	    <Directory /code/phpRack/test/test-web-front>
	        Allow from all
	    </Directory>
	    Alias /phpRack "/code/phpRack/test/test-web-front"

	When it's done (don't forget to restart Apache) you can use this URL:

	    http://localhost/phpRack/phprack.php

	In order to validate JavaScript code correctness and test coverage
	you should run "phing jscoverage" and then open this URL:

	    http://localhost/phpRack/QUnit.php

	To see code coverage click "Coverage report" and see the report in a newly
	opened window.

CHAPTER 2. CONTACT US

	If any questions, don't hesitate to submit tickets to
	http://trac.fazend.com/phpRack.

