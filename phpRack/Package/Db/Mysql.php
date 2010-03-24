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
 * @see phpRack_Adapters_Db_Mysql
 */
require_once PHPRACK_PATH . '/Adapters/Db/Mysql.php';

/**
 * Db abstract
 *
 * @package Tests
 */
class phpRack_Package_Db_Mysql extends phpRack_Package
{
    /**
     * MySQL adapter
     *
     * @var phpRack_Adapters_Db_Mysql
     */
    private $_adapter;

    public function __construct(phpRack_Result $result)
    {
        parent::__construct($result);
        $this->_adapter = new phpRack_Adapters_Db_Mysql();
    }

    /**
     * Check that we can connect to mysql server
     *
     * @param string Host
     * @param integer Port
     * @param string User name
     * @param string User password
     * @return $this
     * @see phpRack_Adapters_Db_Mysql
     * @todo #6 I think we should escape params in $jdbcUrl using urlencode, maybe other idea?
     */
    public function connect($host, $port, $username, $password)
    {
        assert(is_string($host));
        assert(is_numeric($port));
        assert(is_string($username));
        assert(is_string($password));

        $jdbcUrl = "jdbc:mysql://{$host}:{$port}?username={$username}&password={$password}";

        try {
            $this->_adapter->connect($jdbcUrl);
            $this->_success("Connected successfully to MySQL server {$host}:{$port}");
        } catch(Exception $e) {
            $this->_failure("Can't connect to MySQL server {$host}:{$port}");
        }

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

        if (!$this->_adapter->isConnected()) {
            throw new Exception('You must call connect() method before');
        }

        try {
            $this->_adapter->query("USE {$dbName}");
            $this->_success("Database {$dbName} exists");
        } catch (Exception $e) {
            $this->_failure($e->getMessage());
        }

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

        if (!$this->_adapter->isConnected()) {
            throw new Exception('You must call connect() method before');
        }

        if (!$this->_adapter->isDatabaseSelected()) {
            throw new Exception('You must call dbExists() method before');
        }

        $response = $this->_adapter->query(sprintf("SHOW TABLES LIKE '%s'", addslashes($tableName)));
        if ($response == '') {
            $this->_failure("Table {$tableName} not exists");
        } else {
            $this->_success("Table {$tableName} exists");
        }

        return $this;
    }

    /**
    * Close connection to db
    *
    * @return void
    */
    public function closeConnection()
    {
        $this->_adapter->closeConnection();
    }
}
