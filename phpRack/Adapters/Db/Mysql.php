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
 * @see phpRack_Adapters_Db_Abstract
 */
require_once PHPRACK_PATH . '/Adapters/Db/Abstract.php';

/**
 * MySQL adapter
 *
 * The class is using native PHP mysql_ methods, without any specific
 * extensions like PDO or Mysqli.
 *
 * @package Adapters
 * @subpackage Db
 */
class phpRack_Adapters_Db_Mysql extends phpRack_Adapters_Db_Abstract
{
    /**
     * Current mysql connection link identifier
     *
     * @var int Result of mysql_connect()
     * @see connect()
     */
    private $_linkId;
    
    /**
     * Connect to the server
     *
     * @param string JDBC URL to connect to the server
     * @return void
     * @see http://java.sun.com/docs/books/tutorial/jdbc/basics/connecting.html
     * @throws Exception If MySQL extension is not loaded
     * @throws Exception If any of the required params are missed in the URL
     */
    public function connect($url)
    {
        // Parse JDBC URl, and throw exception if it is invalid
        $jdbcUrlParts = $this->_parseJdbcUrl($url);

        if (!extension_loaded('mysql')) {
            throw new Exception('MySQL extension is not loaded');
        }

        $server = $jdbcUrlParts['host'];

        // Check whether server port was set in JDBC URL
        if (isset($jdbcUrlParts['port'])) {
            $server .= ':' . $jdbcUrlParts['port'];
        }

        // Check whether username was set in JDBC URL
        if (isset($jdbcUrlParts['params']['username'])) {
            $username = $jdbcUrlParts['params']['username'];
        } else {
            $username = ini_get('mysql.default_user');
        }

        // Check whether password was set in JDBC URL
        if (isset($jdbcUrlParts['params']['password'])) {
            $password = $jdbcUrlParts['params']['password'];
        } else {
            $password = ini_get('mysql.default_password');
        }

        // Try to connect with MySQL server
        $this->_linkId = @mysql_connect($server, $username, $password);

        if (!$this->_linkId) {
            throw new Exception("Can't connect to MySQL server: '{$server}'");
        }

        // Check whether database was set in JDBC URL
        if (!empty($jdbcUrlParts['database'])) {
            // Try to set this database as current
            if (!@mysql_select_db($jdbcUrlParts['database'], $this->_linkId)) {
                throw new Exception("Can't select database '{$jdbcUrlParts['database']}'");
            }
        }
    }
    
    /**
     * Execute SQL query on the server
     *
     * @param string SQL query
     * @return string Raw result from the server, in text
     * @throws Exception If something wrong happens there
     * @todo #10 Try to have better response formatting
     * @see mysql_query()
     */
    public function query($sql)
    {
        if (!$this->_linkId) {
            throw new Exception('connect() method should be called before');
        }

        $result = mysql_query($sql, $this->_linkId);

        // INSERT, UPDATE, DELETE, DROP, USE etc type queries
        // on success return just true
        if ($result === true) {
            return '';
        }

        // Something goes wrong
        if ($result === false) {
            throw new Exception('MySQL query error: ' . mysql_error());
        }

        // SELECT, SHOW type queries
        $response = '';
        while (false !== ($row = mysql_fetch_row($result))) {
            $response = implode("\t", $row) . "\n";
        }

        return $response;
    }

    /**
     * Return true if adapter is connected with database
     *
     * @return boolean
     * @see $this->_linkId
     */
    public function isConnected()
    {
        if ($this->_linkId) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return true if some database was selected for use
     *
     * @return boolean
     */
    public function isDatabaseSelected()
    {
        $result = $this->query('SELECT DATABASE()');
        if (trim($result) == '') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Close connection to database, if was earlier opened
     *
     * @return void
     */
    public function closeConnection()
    {
        if (is_resource($this->_linkId)) {
            mysql_close($this->_linkId);
            $this->_linkId = null;
        }
    }

    /**
     * Destructor automatically close opened connection
     *
     * @return void
     */
    public function __destruct()
    {
        $this->closeConnection();
    }
}
