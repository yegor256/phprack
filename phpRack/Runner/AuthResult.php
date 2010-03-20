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
     * Stores auth result
     *
     * @var boolean
     * @see isValid()
     */
    protected $_valid = false;
    
    /**
     * Constructor
     *
     * @param boolean Whether the auth is valid or not
     */
    public function __construct($valid)
    {
        $this->_valid = $valid;
    }
    
    /**
     * Result is VALID?
     *
     * @return boolean
     */
    public function isValid() 
    {
        return $this->_valid;
    }
    
}
