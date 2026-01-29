<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 *
 * @version $Id: Extensions.php 25 2010-02-20 09:30:13Z yegor256@yahoo.com $
 * @category phpRack
 * @package Tests
 * @subpackage packages
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * fileinfo to validate.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Php_Extensions_Fileinfo extends phpRack_Package
{

    /**
     * Extension is properly configured?
     *
     * @return $this
     */
    public function isAlive()
    {
        if (!extension_loaded('fileinfo')) {
            $this->_failure("Extension 'fileinfo' is NOT loaded, we can't validate it any further");
            return $this;
        }

        $magic = '/usr/share/misc/magic';
        $finfo = new finfo(FILEINFO_MIME, $magic);
        if (!$finfo) {
            $this->_failure("finfo() failed to load magic: '{$magic}'");
            return $this;
        }

        $type = @$finfo->file(__FILE__);
        if (strpos($type, 'text/') !== 0) {
            $this->_failure("finfo() failed to detect PHP file type, returned: '{$type}'");
            return $this;
        }

        $this->_success("Extension 'fileinfo' is configured properly");
        return $this;
    }

}
