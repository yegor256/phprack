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
 * @version $Id: Package.php 82 2010-03-16 13:46:41Z yegor256@yahoo.com $
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
    */
    private $_linkId;
    
    /**
     * Connect to the server
     *
     * @param string JDBC URL to connect to the server
     * @return void
     * @see http://java.sun.com/docs/books/tutorial/jdbc/basics/connecting.html
     * @throws Exception If something wrong happens there
     * @todo #6 Where and how we should validate that we have all required parameters for mysql_connect?
     *          Should we use some default values, if jdbc url doesn't contain them?
     *
     *          Maybe add for our error handler case to don't display error from php functions
     *          which are preceded by @, to allow script return well formatted failure message.
     */
    public function connect($url)
    {
        assert(is_string($url));

        $jdbcUrlParts = $this->_parseJdbcUrl($url);

        $server = $jdbcUrlParts['host'];

        if (isset($jdbcUrlParts['port'])) {
            $server .= ':' . $jdbcUrlParts['port'];
        }

        if (isset($jdbcUrlParts['params']['username'])) {
            $username = $jdbcUrlParts['params']['username'];
        } else {
            $username = 'root';
        }

        if (isset($jdbcUrlParts['params']['password'])) {
            $password = $jdbcUrlParts['params']['password'];
        } else {
            $password = '';
        }

        if (!extension_loaded('mysql')) {
            throw new Exception('MySQL extension is not loaded');
        }

        $this->_linkId = @mysql_connect($server, $username, $password);

        if (!$this->_linkId) {
            throw new Exception("Can't connect to MySQL server {$server}");
        }

        if ($jdbcUrlParts['database']) {
            if (!@mysql_select_db($jdbcUrlParts['database'], $this->_linkId)) {
                throw new Exception("Can't select db {$jdbcUrlParts['database']}");
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
        assert(is_string($sql));

        if (!$this->_linkId) {
            throw new Exception('connect() method should be called before');
        }

        $result = mysql_query($sql, $this->_linkId);

        if ($result === true) {
            return '';
        }

        if ($result === false) {
            throw new Exception('MySQL query error: ' . mysql_error());
        }

        $response = '';
        while ($row = mysql_fetch_row($result)) {
            $response = implode("\t", $row) . "\n";
        }

        return $response;
    }

    /**
    * Return true if adapter is connected with db
    *
    * @return boolean
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
    * Return true if some db was selected for use
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
    * Close connection to db, if was earlier opened
    *
    * @return void
    */
    public function closeConnection()
    {
        if ($this->_linkId) {
            mysql_close($this->_linkId);
        }
    }
}
