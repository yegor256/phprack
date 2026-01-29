<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 */

/**
 * @see AbstractTest
 */
require_once 'src/test/AbstractTest.php';

/**
 * @see phpRack_Adapters_Url
 */
require_once PHPRACK_PATH . '/Adapters/Url.php';

class Adapters_UrlTest extends AbstractTest
{
    public function testWeCanCreateUrlAndCheckItsContent()
    {
        $url = phpRack_Adapters_Url::factory('http://www.google.com');
        $accessible = $url->isAccessible();
        if (!$accessible) {
            $this->markTestSkipped('url is not accessible');
        }
        try {
            $content = $url->getContent();
        } catch (Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
        $this->assertIsString($content, 'content is not a string');
    }

    /**
     */
    public function testFactoryWithInvalidUrl(): void
    {
        $this->expectException(phpRack_Exception::class);
        phpRack_Adapters_Url::factory('http://');
    }

    public function testFactoryWithoutHttpInUrl()
    {
        $urlAdapter = phpRack_Adapters_Url::factory('www.google.com');
        $this->assertTrue($urlAdapter instanceof phpRack_Adapters_Url);
    }

    public function testFactoryWithPathAndQuery()
    {
        $urlAdapter = phpRack_Adapters_Url::factory('http://www.google.pl/webhp?hl=en');
        $this->assertTrue($urlAdapter instanceof phpRack_Adapters_Url);
    }

    /**
     */
    public function testFactoryWithInvalidOptions(): void
    {
        $this->expectException(phpRack_Exception::class);
        $options = array(
            'invalidOption' => false
        );
        phpRack_Adapters_Url::factory('http://www.google.com', $options);
    }
}
