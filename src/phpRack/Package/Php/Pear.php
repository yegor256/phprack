<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Tests
 * @subpackage packages
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
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * PEAR package used for checking PEAR packages availability.
 *
 * @package Tests
 * @subpackage packages
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
     * Check that exactly this version of PEAR package is present
     *
     * @param string Version number, required
     * @return $this
     * @see PearTest::testPearPackages()
     */
    public function exactly($requiredVersion)
    {
        return $this->_validateVersion($requiredVersion, '==');
    }

    /**
     * Check that at least this version of PEAR package is present
     *
     * @param string Version number, required
     * @return $this
     * @see PearTest::testPearPackages()
     */
    public function atLeast($requiredVersion)
    {
        return $this->_validateVersion($requiredVersion, '>=');
    }

    /**
     * Show full list of installed packages
     *
     * @return $this
     * @see PearTest::testShowPearPackages()
     */
    public function showList()
    {
        try {
            $packages = $this->_pear->getAllPackages();

            $this->_log("Installed PEAR packages:");
            foreach ($packages as $package) {
                $this->_log($package->getRawInfo());
            }
        } catch (phpRack_Exception $e) {
            $this->_failure("PEAR problem: {$e->getMessage()}");
        }
        return $this;
    }

    /**
     * Validate version.
     *
     * @return void
     * @see exactly()
     * @see atLeast()
     */
    protected function _validateVersion($required, $comparison = '=')
    {
        if (is_null($this->_package)) {
            $this->_failure("PEAR package is absent, can't compare versions");
            return $this;
        }

        $currentVersion = $this->_package->getVersion();

        if (version_compare($currentVersion, $required, $comparison)) {
            $this->_success("PEAR '{$this->_package->getName()}' package version is '{$currentVersion}'");
        } else {
            $this->_failure(
                sprintf(
                    "PEAR '%s' package version is '%s', but '%s' is required (%s)",
                    $this->_package->getName(),
                    $currentVersion,
                    $required,
                    $comparison
                )
            );
        }
        return $this;
    }

}
