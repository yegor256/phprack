<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Url
 */
require_once PHPRACK_PATH . '/Adapters/Pear.php';

class Adapters_PearTest extends AbstractTest
{
    /**
     * MySQL adapter
     *
     * @var phpRack_Adapters_Pear
     */
    private $_adapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_adapter = new phpRack_Adapters_Pear();
    }

    protected function tearDown(): void
    {
        unset($this->_adapter);
        parent::tearDown();
    }

    public function testGetPackageName()
    {
        try {
            $package = $this->_adapter->getPackage('PEAR');
            $this->assertEquals('PEAR', $package->getName());
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    public function testGetPackageVersion()
    {
        try {
            $package = $this->_adapter->getPackage('PEAR');
            $version = $package->getVersion();
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
        $this->assertIsString($version, 'version is not a string');
    }

    public function testGetAllPackages()
    {
        try {
            $packages = $this->_adapter->getAllPackages();
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
        $this->assertIsArray($packages, 'packages is not an array');
    }

    public function testGetPackageRawInfo()
    {
        try {
            $package = $this->_adapter->getPackage('PEAR');
            $info = $package->getRawInfo();
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
        $this->assertIsString($info, 'raw info is not a string');
    }
}
