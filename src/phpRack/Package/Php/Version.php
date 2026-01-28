<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
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
 * PHP-version related assertions.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Php_Version extends phpRack_Package
{

    /**
     * Current version is newer than given one?
     *
     * @param string Version name
     * @return $this
     * @see http://www.php.net/manual/en/function.version-compare.php
     */
    public function atLeast($version)
    {
        if (version_compare(phpversion(), $version) >= 0) {
            $this->_success('PHP version is ' . phpversion() . ", newer or equal to {$version}");
        } else {
            $this->_failure('PHP version is ' . phpversion() . ", older than {$version}");
        }
        return $this;
    }

}
