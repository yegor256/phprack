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
 * PHP extensions related assertions
 *
 * @property-read phpRack_Package_Php_Extensions_Fileinfo $fileinfo fileinfo to validate
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Php_Extensions extends phpRack_Package
{

    /**
     * Show full list of loaded extensions
     *
     * @return $this
     */
    public function showAll()
    {
        $list = get_loaded_extensions();
        $this->_log('PHP extensions loaded: ' . implode(', ', $list));
        return $this;
    }

    /**
     * Given extension is loaded?
     *
     * @param string Name of the extension to check
     * @return $this
     */
    public function isLoaded($name)
    {
        if (extension_loaded($name)) {
            $this->_success("PHP extension '{$name}' is loaded");
        } else {
            $this->_failure("PHP extension '{$name}' is NOT loaded: extension_loaded('{$name}') returned FALSE");
        }

        return $this;
    }

}
