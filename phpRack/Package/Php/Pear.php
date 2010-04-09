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
 * @see phpRack_Adapters_Pear
 */
require_once PHPRACK_PATH . '/Adapters/Pear.php';

/**
 * PEAR package used for checking PEAR packages availability
 *
 * @package Tests
 */
class phpRack_Package_Php_Pear extends phpRack_Package
{

    /**
     * Pear adapter
     *
     * @var phpRack_Adapters_Pear
     * @see __construct()
     */
    private $_pear;

    /**
     * Pear package
     *
     * @var phpRack_Adapters_Pear
     * @see package()
     */
    private $_package;

    /**
     * Construct the class
     *
     * @param phpRack_Result
     * @return void
     * @see phpRack_Package::__construct()
     */
    public function __construct(phpRack_Result $result)
    {
        parent::__construct($result);
        $this->_pear = new phpRack_Adapters_Pear();
    }

    /**
     * Check whether PEAR package exists
     *
     * @param string Package name to check
     * @return $this
     * @see PearTest::testPearPackages()
     */
    public function package($name)
    {
        try {
            $this->_package = $this->_pear->getPackage($name);
            if (is_null($this->_package)) {
                $this->_failure("PEAR '{$name}' package does NOT exist");
            } else {
                $this->_success("PEAR '{$name}' package exists, ver.{$this->_package->getVersion()}");
            }
        } catch (Exception $e) {
            $this->_failure("PEAR problem: {$e->getMessage()}");
        }
        return $this;
    }

    /**
     * Check that least this version of PEAR package is present
     *
     * @param string Version number, required
     * @return $this
     * @see PearTest::testPearPackages()
     */
    public function atLeast($requiredVersion)
    {
        if (is_null($this->_package)) {
            $this->_failure("Package is absent, can't compare versions");
            return $this;
        }

        $currentVersion = $this->_package->getVersion();

        if (version_compare($currentVersion, $requiredVersion, '>=')) {
            $this->_success("PEAR '{$this->_package->getName()}' package version is '{$currentVersion}'");
        } else {
            $this->_failure(
                "PEAR '{$this->_package->getName()}' package version is '{$currentVersion}'"
                . ", but '{$requiredVersion}' is required"
            );
        }
        return $this;
    }
    
}
