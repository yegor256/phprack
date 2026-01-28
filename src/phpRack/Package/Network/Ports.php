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
 * @see phpRack_Adapters_Url
 */
require_once PHPRACK_PATH . '/Adapters/Url.php';

/**
 * Ports on the server.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Network_Ports extends phpRack_Package
{

    /**
     * This port is open on this machine as INPUT port?
     *
     * @param integer Port number to check
     * @param string IP address of the server to check
     * @return $this
     */
    public function isOpen($port, $server = '127.0.0.1')
    {
        $urlAdapter = phpRack_Adapters_Url::factory("{$server}:{$port}");

        if ($urlAdapter->isAccessible()) {
            $this->_success("Port '{$port}' is open at '{$server}', it's OK");
        } else {
            $this->_failure("Port '{$port}' is NOT open at '{$server}'");
        }

        return $this;
    }

}
