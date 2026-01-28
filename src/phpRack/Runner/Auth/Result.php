<?php
/**
 * AAAAA
 *
 * @category phpRack
 * @package Tests
 * @subpackage core
 */

/**
 * Result of authentication before running tests
 *
 * @see phpRack_Runner_Auth#_validated()
 * @package Tests
 * @subpackage core
 */
class phpRack_Runner_Auth_Result
{

    /**
     * Stores auth result
     *
     * @var boolean
     * @see isValid()
     */
    protected $_valid;

    /**
     * Optional error message
     *
     * @var string
     * @see isValid()
     */
    protected $_message;

    /**
     * Constructor
     *
     * @param boolean Whether the auth is valid or not
     * @param string Optional error message
     * @return void
     * @see phpRack_Runner_Auth::_validated()
     */
    public function __construct($valid, $message = null)
    {
        $this->_valid = $valid;
        $this->_message = $message;
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

    /**
     * Error message, if exists
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

}
