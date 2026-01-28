<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * @see phpRack_Adapters_Auth_Abstract
 */
require_once PHPRACK_PATH . '/Adapters/Auth/Abstract.php';

/**
 * Authentication array adapter
 *
 * @package Adapters
 * @subpackage Auth
 */
class phpRack_Adapters_Auth_Array extends phpRack_Adapters_Auth_Abstract
{
    /**
     * Authenticate and return an auth result
     *
     * @return phpRack_Runner_Auth_Result
     * @see phpRack_Adapters_Auth_Abstract::authenticate()
     */
    public function authenticate()
    {
        if (strlen($this->_request['hash']) != 32) {
            // This situation is a clear indicator of something wrong
            // in phpRack configuration. "hash" should contain MD5 hash.
            return $this->_validated(
                false,
                "Invalid password hash: '{$this->_request['hash']}'"
            );
        }

        $htpasswd =& $this->_options['htpasswd'];
        foreach (array_keys($htpasswd) as $login) {
            if ($login == $this->_request['login']) {
                $user = $login;
            }
        }
        if (!isset($user)) {
            return $this->_validated(false, 'Invalid user name');
        }

        $password = $htpasswd[$user];
        if (md5($password) != $this->_request['hash']) {
            return $this->_validated(false, 'Invalid password provided');
        }

        // everything is fine
        return $this->_validated(true);
    }
}
