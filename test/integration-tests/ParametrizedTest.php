<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class ParametrizedTest extends PhpRack_Test
{
    protected function _init()
    {
        $this->setAjaxOptions(
            array(
                'tags' => glob(PHPRACK_PATH . '/*.php'),
            )
        );
    }
    public function testFiles($tag = null)
    {
        if ($tag) {
            $this->assert->disc->file->cat($tag);
            return;
        }
        $this->_log("Click one of the tags...");
    }
}