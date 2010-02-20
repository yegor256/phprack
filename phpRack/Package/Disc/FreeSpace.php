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
 * Free space on HDD
 *
 * @package Tests
 */
class PhpRack_Package_Disc_FreeSpace extends PhpRack_Package
{

    /**
     * We have at least this amount of space on the current disc?
     *
     * @param integer Mega-bytes to check
     * @return $this
     */
    public function atLeast($mb) 
    {
        $free = disk_free_space(dirname(__FILE__)) / (1024 * 1024);
        if ($free > $mb) {
            $this->_success(sprintf("We have %0.2fMb free space, {$mb}Mb required", $free));
        } else {
            $this->_failure(sprintf("We have just %0.2fMb free space, while {$mb}Mb required", $free));
        }
            
        return $this;
    }
        
}
