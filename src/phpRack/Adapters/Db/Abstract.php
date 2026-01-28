<?php
/**
 * AAAAA
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

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
     * @throws phpRack_Exception If something wrong happens there
     */
    abstract public function connect($url);

    /**
     * Execute SQL query on the server
     *
     * @param string SQL query
     * @return string Raw result from the server, in text
     * @throws phpRack_Exception If something wrong happens there
     */
    abstract public function query($sql);

    /**
     * Parse JDBC URL and return its components
     *
     * This method matches URLSs like:
     *
     * <code>
     * jdbc:mysql://localhost:3306/test?username=login&password=password
     * jdbc:mysql://localhost:3306/test
     * jdbc:mysql://localhost:3306
     * jdbc:mysql://localhost
     * </code>
     *
     * Mandatory parts of the URL are: "adapter", "host". All other params are
     * optional and could be omitted.
     *
     * @param string JDBC URL to parse
     * @throws phpRack_Exception If JDBC URL have wrong format
     * @return array We set "adapter", "host", "port", "database", "params"
     */
    protected function _parseJdbcUrl($url)
    {
        $pattern = '#^jdbc:(?P<adapter>[^:]+)'
            . '://(?P<host>[^:/]+)'
            . '(?::(?P<port>\d+))?'
            . '(?:/(?P<database>[^?]+))?'
            . '(?:\?(?P<params>.*))?$#';

        $matches = array();
        if (!preg_match($pattern, $url, $matches)) {
            throw new phpRack_Exception('JDBC URL parse error');
        }

        // Convert params string to array
        if (isset($matches['params'])) {
            $paramsString = $matches['params'];
            parse_str($paramsString, $matches['params']);
        }

        return $matches;
    }
}
