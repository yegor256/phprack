<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
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
 * Free space on HDD.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Disc_FreeSpace extends phpRack_Package
{

    /**
     * We have at least this amount of space on the current disc?
     *
     * @param integer Mega-bytes to check
     * @return $this
     */
    public function atLeast($mb)
    {
        $free = disk_free_space(dirname(__FILE__)) / (1024 * 1024);
        if ($free >= $mb) {
            $this->_success(sprintf("We have %0.2fMb free space, {$mb}Mb required", $free));
        } else {
            $this->_failure(sprintf("We have just %0.2fMb free space, while {$mb}Mb required", $free));
        }

        return $this;
    }

}
