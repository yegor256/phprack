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
 * Abstract adapter for DB connectivity
 *
 * @package Adapters
 * @subpackage Db
 */
abstract class phpRack_Adapters_Db_Abstract
{
    
    /**
     * Connect to the server
     *
     * @param string JDBC URL to connect to the server
     * @return void
     * @see http://java.sun.com/docs/books/tutorial/jdbc/basics/connecting.html
     * @throws Exception If something wrong happens there
     */
    abstract public function connect($url);
    
    /**
     * Execute SQL query on the server
     *
     * @param string SQL query
     * @return string Raw result from the server, in text
     * @throws Exception If something wrong happens there
     */
    abstract public function query($sql);

    /**
    * Parse JDBC URL and return its components
    * @param string JDBC URL to parse
    * @throws Exception If JDBC URL have wrong format
    * @return array
    * @todo #6 Should we allow in regular expression port or database to be optional?
    */
    protected function _parseJdbcUrl($url)
    {
        /**
        * Match urls like:
        * jdbc:mysql://localhost:3306/test?username=login&password=password
        * jdbc:mysql://localhost:3306/test
        * jdbc:mysql://localhost:3306
        * jdbc:mysql://localhost
        */

        $pattern = '#jdbc:(?P<adapter>[^:]+)'
            . '://(?P<host>[^:/]+)'
            . '(?::(?P<port>\d+))?'
            . '(?:/(?P<database>[^?]+))?'
            . '(?:\?(?P<params>.*))?#';

        $matches = array();
        if (!preg_match($pattern, $url, $matches)) {
            throw new Exception('JDBC URL parse error');
        }

        // Convert params string to array
        if (isset($matches['params'])) {
            $paramsString = $matches['params'];
            parse_str($paramsString, $matches['params']);
        }

        return $matches;
    }
}
