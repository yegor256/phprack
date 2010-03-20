<?php
/**
 * @version $Id$
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class PhpConfigurationIsValidTest extends PhpRack_Test
{
    
    public function testPhpLint()
    {
        $options = array(
            'extensions' => 'php,phtml',
            'exclude' => '/sample*/',
        );
        // lint validation of all files in the directory
        $this->assert->php
            ->lint('../test/phpRack/Package/Php/_files/php', $options);
    }
    
}
