<?php
/**
 * phpRack: Integration Testing Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL: http://www.phprack.com/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@phprack.com so we can send you a copy immediately.
 *
 * @copyright Copyright (c) phpRack.com
 * @version $Id$
 * @category phpRack
 */

/**
 * Connection monitor used to check whether connection between client
 * and our script is still opened
 *
 * @package Adapters
 * @author netcoderpl@gmail.com
 */
class phpRack_Adapters_ConnectionMonitor
{
    /**
     * Connection status last checked time
     *
     * @var int
     * @see ping()
     */
    private $_lastCheckTime = null;

    /**
     * Connection status checking interval
     *
     * @var int
     * @see ping()
     */
    private $_checkInterval = 1; // 1 second

    /**
     * phpRack_Adapters_ConnectionMonitor instance
     *
     * @var phpRack_Adapters_ConnectionMonitor
     * @see getInstance()
     */
    private static $_instance;

    /**
     * Get phpRack_Adapters_ConnectionMonitor instance
     *
     * @return phpRack_Adapters_ConnectionMonitor
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Check client connection is still opened. We can check it by sending
     * space char " " every $this->_checkInterval second.
     *
     * After that PHP can detect connection status. If it was closed objects
     * destructors will be automatically executed and script stop work.
     *
     * @return void
     */
    public function ping()
    {
        if ($this->_lastCheckTime === null
            || $this->_lastCheckTime + $this->_checkInterval < time()
        ) {
            echo ' ';

            /**
             * Bypass output buffering, without it PHP may not recognize that
             * connection is closed
             */
            if (ob_get_level()) {
                ob_flush();
            }

            flush();
            $this->_lastCheckTime = time();
        }
    }
}
