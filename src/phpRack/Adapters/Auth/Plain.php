<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
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
 * Authentication plain adapter
 *
 * @package Adapters
 * @subpackage Auth
 */
class phpRack_Adapters_Auth_Plain extends phpRack_Adapters_Auth_Abstract
{

    /**
     * Authenticate and return an auth result
     *
     * @return phpRack_Runner_Auth_Result
     * @see phpRack_Adapters_Auth_Abstract::authenticate()
     */
    public function authenticate()
    {
        $auth = $this->_options['auth'];
        if ($auth['username'] != $this->_request['login']) {
            return $this->_validated(
                false,
                "Invalid login '{$this->_request['login']}"
            );
        }

        if (md5($auth['password']) != $this->_request['hash']) {
            return $this->_validated(
                false,
                'Invalid password'
            );
        }

        return $this->_validated(true);
    }

}
