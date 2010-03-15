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
 * Result of authentication before running tests
 *
 * @package Tests
 */
class phpRack_Runner_AuthResult
{
    /**
     * Password validation result, if file not found or password doesn't match defaults to false
     *
     * @var boolean
     * @see isValid()
     */
    protected $valid = false;
    
    /**
     * Stores error message, if ocurred during authentication
     *
     * @var string
     * @see __construct()
     */
    protected $errorMsg;
    
    /**
     * Constructor
     *
     * @param string Login attempting to access phpRack
     * @param string Hash of password provided by user
     */
    public function __construct($login, $hash)
    {
        global $phpRackConfig;
        if (!is_array($phpRackConfig))
        {
            $this->errorMsg = 'Config variable $phpRackConfig is not an array';
            return;
        }
        if (array_key_exists('auth', $phpRackConfig))
        {
            if (($phpRackConfig['auth']['username']==$login) && (md5($phpRackConfig['auth']['username'])==$hash))
            {
                $this->valid = true;
                return;
            } else {
                $this->errorMsg = 'Incorrect login or password';
                return;
            }
        } elseif (array_key_exists('htpasswd', $phpRackConfig)) {
            /* we assume provided path is absolute or at least relative to current directory */
            $fileContent = file($phpRackConfig['htpasswd'], FILE_SKIP_EMPTY_LINES);
            foreach ($fileContent as $line)
            {
                list($lg, $psw) = explode(':', $line, 2);
                /* Just to make sure we don't analyze some whitespace characters */
                $lg = trim($lg);
                $psw = trim($psw);
                if (($lg==$login) && ($psw==$hash))
                {
                    $this->valid = true;
                    return;
                }
            }
            $this->errorMsg = 'Incorrect password or user doesn\'t exist';
        } else {
            $this->valid = true;
            return;
        }
    }
    
    
    /**
     * Returns message of error in authentication, if any
     *
     * @return string
     */
    public function getErrorMessage() 
    {
        return $this->errorMsg;
    }
    
    /**
     * Result is VALID?
     *
     * @return boolean
     */
    public function isValid() 
    {
        return $this->valid;
    }
    
}
