<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

class Php_PhpConfigurationIsValidTest extends PhpRack_Test
{
    protected function _init()
    {
        $this->setAjaxOptions(
            array(
                'autoStart' => false, // true by default
            )
        );
    }

    public function testPhpLint()
    {
        $options = array(
            'extensions' => 'php,phtml',
            'exclude' => array(
                '/sample*/',
                '/\.svn/'
            ),
        );
        // lint validation of all files in the directory
        $this->assert->php
            ->lint('../test/phpRack/Package/Php/_files/php', $options);
    }

    public function testPhpLintOfApplication()
    {
        $options = array(
            'extensions' => 'php,phtml',
            'exclude' => '/\.svn/',
        );
        // lint validation of all files in the directory
        $this->assert->php
            ->lint('./', $options);
    }

}
