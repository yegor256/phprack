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
require_once PHPRACK_PATH . '/Package/Db/Abstract.php';

/**
 * Db abstract
 *
 * @package Tests
 */
class phpRack_Package_Db_Mysql extends phpRack_Package_Db_Abstract
{
    /**
     * Check that we can connect to mysql server
     *
     * @param string Host
     * @param integer Port
     * @param string User name
     * @param string User password
     * @return $this
     * @todo #6 Should we use some abstraction level for example PDO or just use
     *       mysql_/mysqli_ functions?
     */
    public function connect($host, $port, $username, $password)
    {
        $this->_failure('Not implemented');
        return $this;
    }

    /**
     * Check that database exists
     *
     * @param string Database name
     * @return $this
     * @todo #6 If connect method wasn't called earlier what to do, call _failure and return?
     */
    public function dbExists($dbName)
    {
        $this->_failure('Not implemented');
        return $this;
    }

    /**
     * Check that table exists
     *
     * @param Table name
     * @return $this
     * @todo #6 We must have firstly selected db. Should it be automatically selected in dbExists method?
     */
    public function tableExists($tableName)
    {
        $this->_failure('Not implemented');
        return $this;
    }
}
