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
     * @see phpRack_Adapters_Db_Mysql
     */
    public function connect($host, $port, $username, $password)
    {
        assert(is_string($host));
        assert(is_numeric($port));
        assert(is_string($username));
        assert(is_string($password));
        $this->_failure('Not implemented');
        return $this;
    }

    /**
     * Check that database exists, and selects this database as current
     *
     * @param string Database name
     * @return $this
     * @see connect()
     * @throws Exception If this method is called before connect()
     */
    public function dbExists($dbName)
    {
        assert(is_string($dbName));
        $this->_failure('Not implemented');
        return $this;
    }

    /**
     * Check that table exists
     *
     * @param Table name
     * @return $this
     * @see connect()
     * @throws Exception If this method is called before connect()
     * @throws Exception If this method is called before dbExists()
     */
    public function tableExists($tableName)
    {
        assert(is_string($tableName));
        $this->_failure('Not implemented');
        return $this;
    }
}
