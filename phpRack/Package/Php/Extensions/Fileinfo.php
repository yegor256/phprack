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
 * fileinfo to validate
 *
 * @package Tests
 */
class phpRack_Package_Php_Extensions_Fileinfo extends phpRack_Package
{

    /**
     * Extension is properly configured?
     *
     * @return $this
     */
    public function isAlive() 
    {
        if (!extension_loaded('fileinfo')) {
            $this->_failure("Extension 'fileinfo' is NOT loaded, we can't validate it any further");
            return $this;
        }
        
        $magic = '/usr/share/misc/magic';
        $finfo = new finfo(FILEINFO_MIME, $magic);
        if (!$finfo) {
            $this->_failure("finfo() failed to load magic: '{$magic}'");
            return $this;
        }
        
        $type = $finfo->file(__FILE__);
        if (strpos($type, 'text/') !== 0) {
            $this->_failure("finfo() failed to detect PHP file type, returned: '{$type}'");
            return $this;
        }
            
        $this->_success("Extension 'fileinfo' is configured properly");
        return $this;
    }
        
}
