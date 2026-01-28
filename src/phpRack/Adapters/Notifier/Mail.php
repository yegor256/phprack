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
 * Mail adapter used for mailing phpRack reports
 *
 * @package Adapters
 * @subpackage Notifiers
 */
class phpRack_Adapters_Notifier_Mail
{
    /**
     * Factory method to get one of Sendmail or Smtp class instances.
     *
     * Depends on options specified. Available options depend on transport
     * you choose.
     *
     * @see phpRack_Adapters_Notifier_Mail_Smtp
     * @see phpRack_Adapters_Notifier_Mail_Sendmail
     * @param array List of parameters
     * @return phpRack_Adapters_Mail
     * @throws phpRack_Exception
     */
    public static function factory($class = 'sendmail', array $params = array())
    {
        $transport = ucfirst(strtolower($class));
        /**
         * @see phpRack_Adapters_Notifier_Mail_Abstract
         */
        $classFile = PHPRACK_PATH . "/Adapters/Notifier/Mail/{$transport}.php";
        if (!file_exists($classFile)) {
            throw new phpRack_Exception("Transport {$transport} is absent");
        }
        eval("require_once '{$classFile}';"); // for ZCA validation
        $transportClass = 'phpRack_Adapters_Notifier_Mail_' . $transport;
        return new $transportClass($params);
    }
}
