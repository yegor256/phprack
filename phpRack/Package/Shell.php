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
 * @version $Id: Db.php 169 2010-03-23 07:04:08Z yegor256@yahoo.com $
 * @category phpRack
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * Assertions related to SHELL
 *
 * @package Tests
 */
class phpRack_Package_Shell extends phpRack_Package
{

    /**
     * Execute a command and tries to find a regex inside it's result
     *
     * Use it like this, to make sure that PHP scripts are started
     * by "apache" user:
     *
     * <code>
     * class MyTest extends phpRack_Test {
     *   public function testAuthorship() {
     *     $this->assert->shell->exec('whoami', '/apache/');
     *   }
     * }
     * </code>
     *
     * @param string Command to run
     * @param string Regular exception
     * @return $this
     */
    public function exec($cmd, $regex = null) 
    {
        $result = shell_exec($cmd);
        $this->_log('$ ' . $cmd);
        $this->_log($result);
        if (!is_null($regex)) {
            if (!preg_match($regex, $cmd)) {
                $this->_failure("Result of '{$cmd}' doesn't match regular expression '{$regex}': '{$result}'");
            }
        }
    }

}
