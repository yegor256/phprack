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
 * @version $Id: Extensions.php 25 2010-02-20 09:30:13Z yegor256@yahoo.com $
 * @category phpRack
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * finfo to validate
 *
 * @package Tests
 */
class phpRack_Package_Php_Extensions_Finfo extends phpRack_Package
{

    /**
     * Extension is properly configured?
     *
     * @return $this
     */
    public function isAlive() 
    {
        if (!extension_loaded('finfo')) {
            $this->_failure("Extension 'finfo' is NOT loaded, we can't validate it any further");
            return $this;
        }
        
        $finfo = new finfo(FILEINFO_MIME, '/usr/share/misc/magic');
        if (!$finfo) {
            $this->_failure("finfo() failed to load");
            return $this;
        }
        
        $type = $finfo->file(__FILE__);
        if (strpos($type, 'text/plain') !== 0) {
            $this->_failure("finfo() failed to load");
            return $this;
        }
            
        $this->_success("Extension 'finfo' is configured properly");
        return $this;
    }
        
}
