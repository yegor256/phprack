<?php
/**
 * @version $Id$
 */

/**
 * @see AbstractTest
 */
require_once 'AbstractTest.php';

/**
 * @see phpRack_Adapters_Url
 */
require_once PHPRACK_PATH . '/Adapters/Url.php';

class Adapters_UrlTest extends AbstractTest
{
    public function testWeCanCreateUrlAndCheckItsContent()
    {
        $url = phpRack_Adapters_Url::factory('http://www.google.com');
        $accessible = $url->isAccessible();
        if (!$accessible) {
            $this->markTestIncomplete();
        }
        try {
            $url->getContent();
        } catch (Exception $e) {
            $this->_log(get_class($e) . ': ' . $e->getMessage());
            $this->markTestIncomplete();
        }
    }

    public function testFactoryWithInvalidUrl()
    {
        try {
            phpRack_Adapters_Url::factory('http://');
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
        }
    }

    public function testFactoryWithoutHttpInUrl()
    {
        $urlAdapter = phpRack_Adapters_Url::factory('www.google.com');
        $this->assertTrue($urlAdapter instanceof phpRack_Adapters_Url);
    }

    public function testFactoryWithPathAndQuery()
    {
        $urlAdapter = phpRack_Adapters_Url::factory('http://www.google.pl/webhp?hl=en');
        $this->assertTrue($urlAdapter instanceof phpRack_Adapters_Url);
    }
}
