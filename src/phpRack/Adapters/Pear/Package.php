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
 * PEAR adapter used for checking PEAR packages availability
 *
 * @package Adapters
 */
class phpRack_Adapters_Pear_Package
{
    /**
     * Package name
     *
     * @var string
     * @see __construct()
     * @see getName()
     */
    private $_name;

    /**
     * Raw package info returned from "pear info $packageName" command
     *
     * @var string
     * @see __construct()
     * @see getName()
     * @see getVersion()
     */
    private $_rawInfo;

    /**
     * Construct the class
     *
     * @param string Name of the package
     * @throws phpRack_Exception if PEAR is not installed properly
     * @return void
     */
    public function __construct($name)
    {
        $this->_name = $name;

        $command = 'pear info ' . escapeshellarg($this->_name);
        /**
         * @see phpRack_Adapters_Shell_Command
         */
        require_once PHPRACK_PATH . '/Adapters/Shell/Command.php';
        $result = phpRack_Adapters_Shell_Command::factory($command)->run();

        if (!$result) {
            throw new phpRack_Exception('PEAR is not installed properly');
        }

        $this->_rawInfo = $result;
    }

    /**
     * Get name of the package
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Check whether Package exists
     *
     * @return boolean
     * @throws Exception If package has invalid version number
     * @see phpRack_Package_Pear::package()
     */
    public function getVersion()
    {
        $matches = array();
        if (!preg_match('/^Release Version\s+(\S+)/m', $this->_rawInfo, $matches)) {
            throw new Exception('Invalid version for the package');
        }
        return $matches[1];
    }

    /**
     * Get package raw info
     *
     * @return string
     * @see phpRack_Package_Pear::package()
     */
    public function getRawInfo()
    {
        return $this->_rawInfo;
    }
}
