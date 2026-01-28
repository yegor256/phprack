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
 * Authentication file adapter
 *
 * @package Adapters
 * @subpackage Auth
 */
class phpRack_Adapters_Auth_File extends phpRack_Adapters_Auth_Abstract
{
    /**
     * Authenticate and return an auth result
     *
     * @return phpRack_Runner_Auth_Result
     * @see phpRack_Adapters_Auth_Abstract::authenticate()
     */
    public function authenticate()
    {
        /**
         * @see phpRack_Adapters_File
         */
        require_once PHPRACK_PATH . '/Adapters/File.php';
        $file = phpRack_Adapters_File::factory($this->_options['htpasswd'])->getFileName();

        $fileContent = file($file);
        foreach ($fileContent as $line) {
            list($login, $password) = explode(':', $line, 2);
            /* Just to make sure we don't analyze some whitespace */
            $login = trim($login);
            $password = trim($password);
            if (($login == $this->_request['login']) && ($password == $this->_request['hash'])) {
                return $this->_validated(true);
            }
        }
        return $this->_validated(false, 'Invalid login credentials provided');
    }
}
