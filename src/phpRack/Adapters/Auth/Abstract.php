<?php
/**
 * AAAAA
 *
 * @category phpRack
 * @package Adapters
 * @copyright Copyright (c) 2009-2026 phpRack.com
 */

/**
 * Authentication abstract adapter
 *
 * @category phpRack
 * @package Adapters
 * @subpackage Auth
 */
abstract class phpRack_Adapters_Auth_Abstract
{
    /**
     * Authentication request
     *
     * @var array
     */
    protected $_request = array(
        'login'    => '',
        'hash'     => ''
    );

    /**
     * Authentication options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Set authentication options
     *
     * @param array
     * @see phpRack_Runner_Auth::authenticate()
     * @return @this
     */
    public function setOptions($options)
    {
        $this->_options = $options;
        return $this;
    }

    /**
     * Set request login, hash
     *
     * @param array
     * @see phpRack_Runner_Auth::authenticate()
     * @return @this
     */
    public function setRequest($request)
    {
        foreach ($request as $key => $value) {
            $this->_request[$key] = $value;
        }
        return $this;
    }

    /**
     * Authenticate and return an auth result
     *
     * @return phpRack_Runner_Auth_Result
     * @see phpRack_Runner_Auth::authenticate()
     */
    abstract public function authenticate();

    /**
     * Return an AuthResult
     *
     * @param boolean Success/failure of the validation
     * @param string Optional error message
     * @return phpRack_Runner_Auth_Result
     * @see authenticate()
     */
    protected function _validated($result, $message = null)
    {
        /**
         * @see phpRack_Runner_Auth_Result
         */
        require_once PHPRACK_PATH . '/Runner/Auth/Result.php';
        return new phpRack_Runner_Auth_Result($result, $message);
    }

}
