<?php
/**
 * phpRack: Integration Testing Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available 
 * through the world-wide-web at this URL: http://www.phprack.com/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@phprack.com so we can send you a copy immediately.
 *
 * @copyright Copyright (c) phpRack.com
 * @version $Id$
 * @category phpRack
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * Simple package, for simple assertions
 *
 * @package Tests
 */
class phpRack_Package_Simple extends phpRack_Package
{
    
    /**
     * Just fail the test
     *
     * @param string Message to show
     * @return $this
     */
    public function fail($msg) 
    {
        $this->_failure($msg);
        return $this;
    }

    /**
     * Is it true?
     *
     * @param mixed Variable to check
     * @return $this
     */
    public function isTrue($var) 
    {
        if ($var) {
            $this->_success('Variable is TRUE, success');
        } else {
            $this->_failure('Failed to assert that variable is TRUE');
        }
            
        return $this;
    }
        
}
