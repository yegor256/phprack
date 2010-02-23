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
 * PHP related assertions
 *
 * @package Tests
 */
class phpRack_Package_Php_Extensions extends phpRack_Package
{

    /**
     * Given extension is loaded?
     *
     * @param string Name of the extension to check
     * @return $this
     */
    public function isLoaded($name) 
    {
        if (extension_loaded($name)) {
            $this->_success("Extension '{$name}' is loaded");
        } else {
            $this->_failure("Extension '{$name}' is NOT loaded: extension_loaded('{$name}') returned FALSE");
        }
            
        return $this;
    }
        
}
