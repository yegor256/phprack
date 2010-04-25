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
            $this->_log($e);
            $this->markTestIncomplete();
        }
    }

    /**
     * @expectedException Exception
     */
    public function testFactoryWithInvalidUrl()
    {
        phpRack_Adapters_Url::factory('http://');
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

    /**
     * @expectedException Exception
     */
    public function testFactoryWithInvalidOptions()
    {
        $options = array(
            'invalidOption' => false
        );
        phpRack_Adapters_Url::factory('http://www.google.com', $options);
    }
}
