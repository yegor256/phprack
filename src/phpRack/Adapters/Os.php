<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * OS adapter used to get information where script is executed
 *
 * @package Adapters
 */
class phpRack_Adapters_Os
{
    /**
     * System constants used for simplify comparisions
     */
    const WINDOWS = 'Windows';
    const LINUX = 'Linux';
    const DARWIN = 'Darwin';

    /**
     * Recognize OS and return its name as string (Windows, Linux, etc)
     *
     * @return string
     * @see phpRack_Adapters_Cpu::factory()
     * @throws phpRack_Exception if operating system can't be recognized
     */
    public static function get()
    {
        switch (true) {
            /* windows */
            case (substr(PHP_OS, 0, 3) === 'WIN'):
                return self::WINDOWS;

            /* Mac OS and Mac OS X */
            case (substr(PHP_OS, 0, 6) === 'Darwin'):
                return self::DARWIN;

            /* Linux */
            case (substr(PHP_OS, 0, 5) === 'Linux'):
                return self::LINUX;

            /* all other systems */
            default:
                throw new phpRack_Exception('Unknown operating system');
        }
    }

    /**
     * Is it *NIX system?
     *
     * Everything which is NOT windows is Unix. Very rough assumption, but this
     * is enough for now.
     *
     * @return boolean
     */
    public static function isUnix()
    {
        return (self::get() != self::WINDOWS);
    }
}
