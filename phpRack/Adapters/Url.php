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
 * Socket based adapter which help checking remote url accessibility
 * and retrieve content from them
 *
 * @package Adapters
 */
class phpRack_Adapters_Url
{
    /**
     * Socket returned by fsockopen()
     *
     * @see connect()
     * @var resource
     */
    private $_socket;

    /**
     * Host name, set in __construct
     *
     * @see __construct()
     */
    private $_host;

    /**
     * Port number used to connect with host.
     * Can be changed by passing URL with custom one to __construct()
     *
     * @see __construct()
     * @var integer
     */
    private $_port = 80;

    /**
     * Connection options
     *
     * @var array
     */
    private $_options = array(
        'connect_timeout' => 1,    // timeouts in seconds
        'read_timeout'    => 5
    );

    /**
     * Constructor
     *
     * @param string URL
     * @return void
     */
    public function __construct($url)
    {
        // If url has not sheme defined - add it
        if (!preg_match('#^\w+://#', $url)) {
            $url = 'http://' . $url;
        }

        $urlParts = @parse_url($url);

        // If there was url parsing error
        if ($urlParts === false) {
            throw new Exception('This is NOT valid url');
        }

        // Set host
        $this->_host = $urlParts['host'];

        // Set url path
        if (isset($urlParts['path'])) {
            $this->_path = $urlParts['path'];
        } else {
            $this->_path = '/';
        }

        // Check if have query params after "?", if yes attach them to our _path
        if (isset($urlParts['query'])) {
            $this->_path .= '?' . $urlParts['query'];
        }

        // Check if port number was passed in URL
        if (isset($urlParts['port'])) {
            $this->_port = $urlParts['port'];
        }
    }

    /**
     * Factory, to simplify calls
     *
     * @param string URL
     * @return phpRack_Adapters_Url
     */
    public static function factory($url)
    {
        return new self($url);
    }

    /**
     * Create connection to server
     *
     * @return void
     * @throws Exception if can't connect to server
     */
    protected function _connect()
    {
        // If we are not connected
        if (!$this->_socket) {
            $errorNumber = null;
            $errorString = null;

            // Try to open connection to server
            $this->_socket = @fsockopen(
                $this->_host,
                $this->_port,
                $errorNumber,
                $errorString,
                $this->_options['connect_timeout']
            );
        }

        // If can't connect
        if (!$this->_socket) {
            throw new Exception (
                "Can't connect to '{$this->_host}':'{$this->_port}'"
                . " Error #'{$errorNumber}': '{$errorString}'"
            );
        }
    }

    /**
     * Close current connection
     *
     * @return void
     */
    protected function _disconnect()
    {
        // If we are connected
        if (is_resource($this->_socket)) {
            fclose($this->_socket);
            $this->_socket = null;
        }
    }

    /**
     * Check whether URL is accessible
     *
     * @return boolean
     */
    public function isAccessible()
    {
        try {
            $this->_connect();
        } catch (Exception $e) {
            assert($e instanceof Exception); // for ZCA only
            return false;
        }

        return true;
    }

    /**
     * Get content of URL passed to constructor
     *
     * @see __construct()
     * @return string Content of URL
     * @throws Exception If can't get content for some reason
     */
    public function getContent()
    {
        // Try to connect with server, if can't will throw exception
        $this->_connect();

        // Create HTTP request
        $request = "GET {$this->_path} HTTP/1.1\r\n"
            . "Host: {$this->_host}\r\n"
            . "Connection: Close\r\n\r\n\r\n";

        // Send request
        fwrite($this->_socket, $request);

        $response = '';

        stream_set_timeout($this->_socket, $this->_options['read_timeout']);

        $info = array('timed_out' => false);
        // Receive response
        while (!feof($this->_socket) && !$info['timed_out']) {
            $line = fgets($this->_socket, 1024);
            $response .= $line;
            $info = stream_get_meta_data($this->_socket);
        }

        // Close connection
        $this->_disconnect();

        // If connection timeouted
        if ($info['timed_out']) {
            throw new Exception('Connection timed out!');
        }

        return $response;
    }
}
