<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class PhpConfigurationTest extends phpRack_Test
{

    public function testPhpVersionIsCorrect()
    {
        $this->assert->php->version
            ->atLeast('5.2');

        $this->assert
            ->isTrue(function_exists('lcfirst'))
            ->onSuccess("Method lcfirst() exists, it's PHP5.3 for sure")
            ->onFailure("Method lcfirst() is absent, it's PHP5.2 or older");
    }

    public function testPhpExtensionsExist()
    {
        $this->assert->php->extensions
            ->isLoaded('xsl')
            ->isLoaded('simplexml')
            ->isLoaded('fileinfo');

        $this->assert->php->extensions->fileinfo->isAlive();
    }

    /**
     * Test for the extension which does not exist.
     */
    public function testPhpExtensionsNotExist()
    {
        $this->assert->php->extensions
            ->isLoaded('someIncorrectExtensionName');
    }

    /**
     * Tests showAll() function of hpRack_Package_Php_Extensions.
     */
    public function testShowAllExtensions()
    {
        $this->assert->php->extensions->showAll();
    }

    public function testPhpFunctionExist()
    {
        $this->assert->php
            ->fnExists('lcfirst')
            ->fnExists('printf');
    }

}
