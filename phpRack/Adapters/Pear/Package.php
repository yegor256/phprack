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
 * PEAR adapter used for checking PEAR packages availability
 *
 * @package Adapters
 */
class phpRack_Adapters_Pear_Package
{
    /**
     * Last checked package name
     *
     * @var string
     * @see __construct()
     * @see getName()
     */
    private $_name;

    /**
     * Construct the class
     *
     * @param string Name of the package
     * @return void
     */
    public function __construct($name)
    {
        $this->_name = $name;
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
     * @throws Exception If PEAR is not installed properly
     * @see phpRack_Package_Pear::package()
     */
    public function getVersion()
    {
        $command = 'pear info ' . escapeshellarg($this->_name);
        $result = shell_exec($command);

        if (!$result) {
            throw new Exception('PEAR is not installed properly');
        }

        $matches = array();
        if (!preg_match('/^Release Version\s+(\S+)/m', $result, $matches)) {
            throw new Exception('Invalid version for the package');
        }
        return $matches[1];
    }
}
