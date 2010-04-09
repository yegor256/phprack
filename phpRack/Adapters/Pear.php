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
class phpRack_Adapters_Pear
{
    /**
     * Last checked package name
     *
     * @var string
     * @see isPackageExists()
     * @see getPackageName()
     */
    private $_packageName;

    /**
     * Last checked package version
     *
     * @var string
     * @see isPackageExists()
     * @see getPackageVersion()
     */
    private $_packageVersion;

    /**
     * Check whether Package exists
     *
     * @return boolean
     * @throws Exception if PEAR is not installed properly
     * @see phpRack_Package_Pear::package()
     */
    public function isPackageExists($packageName)
    {
        $this->_packageName = $packageName;
        $this->_packageVersion = null;
        $command = 'pear info ' . escapeshellarg($packageName);
        $result = shell_exec($command);

        if (!$result) {
            throw new Exception('PEAR is not installed properly');
        }

        $matches = array();
        if (!preg_match('/^Release Version\s+(\S+)/m', $result, $matches)) {
            return false;
        }

        $this->_packageVersion = $matches[1];

        return true;
    }

    /**
     * Get name of last checked package
     *
     * @return string Name of last checked package
     * @throws Exception If called before isPackageExists()
     * @see phpRack_Package_Pear::atLeast()
     */
    public function getPackageName()
    {
        if ($this->_packageName === null) {
            throw new Exception('You must call isPackageExists() with valid PEAR package');
        }
        return $this->_packageName;
    }

    /**
     * Get version of last checked package
     *
     * @return string Version of last checked package
     * @throws Exception If called before isPackageExists()
     * @see phpRack_Package_Pear::atLeast()
     */
    public function getPackageVersion()
    {
        if ($this->_packageVersion === null) {
            throw new Exception('You must call isPackageExists() with valid PEAR package');
        }

        return $this->_packageVersion;
    }
}
