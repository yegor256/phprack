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
 * @version $Id: Php.php 19 2010-02-07 07:44:16Z yegor256@yahoo.com $
 * @category phpRack
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * Ports on the server
 *
 * @package Tests
 */
class PhpRack_Package_Network_Ports extends PhpRack_Package
{

    /**
     * This port is open on this machine as INPUT port?
     *
     * @param integer Port number to check
     * @param string IP address of the server to check
     * @return $this
     */
    public function isOpen($port, $server = '127.0.0.1') 
    {
        $result = shell_exec("telnet {$server} {$port} 2>&1 </dev/null");
        
        if (preg_match('/Connection refused/', $result)) {
            $this->_failure("Port {$port} is NOT open at {$server}");
        } else {
            $this->_success("Port {$port} is open at {$server}, it's OK");
        }
            
        return $this;
    }
        
}
