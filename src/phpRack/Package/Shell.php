<?php
/**
 * AAAAA
 *
 * @version $Id: Db.php 169 2010-03-23 07:04:08Z yegor256@yahoo.com $
 * @category phpRack
 * @package Tests
 * @subpackage packages
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * Assertions related to SHELL.
 *
 * @package Tests
 * @subpackage packages
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
        /**
         * @see phpRack_Adapters_Shell_Command
         */
        require_once PHPRACK_PATH . '/Adapters/Shell/Command.php';
        $result = phpRack_Adapters_Shell_Command::factory($cmd)->run();

        $this->_log('$ ' . $cmd);
        $this->_log($result);
        if (!is_null($regex)) {
            if (!preg_match($regex, $result)) {
                $this->_failure("Result of '{$cmd}' doesn't match regex '{$regex}': '{$result}'");
            } else {
                $this->_success("Result of '{$cmd}' matches regex '{$regex}'");
            }
        }
        return $this;
    }

}
