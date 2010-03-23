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

class Adatpers_UrlTest extends AbstractTest
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
    
}
