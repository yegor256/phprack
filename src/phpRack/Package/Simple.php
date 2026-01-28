<?php
/**
 * AAAAA
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
 * Simple package, for simple assertions.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Simple extends phpRack_Package
{

    /**
     * Just fail the test
     *
     * @param string Message to show
     * @return $this
     */
    public function fail($msg)
    {
        $this->_failure($msg);
        return $this;
    }

    /**
     * Mark the test as successful
     *
     * @param string Message to show
     * @return $this
     */
    public function success($msg)
    {
        $this->_success($msg);
        return $this;
    }

    /**
     * Is it true?
     *
     * @param mixed Variable to check
     * @return $this
     */
    public function isTrue($var)
    {
        if ($var) {
            $this->_success('Variable is TRUE, success');
        } else {
            $this->_failure('Failed to assert that variable is TRUE');
        }
        return $this;
    }

    /**
     * Is it false?
     *
     * @param mixed Variable to check
     * @return $this
     */
    public function isFalse($var)
    {
        if (!$var) {
            $this->_success('Variable is FALSE, success');
        } else {
            $this->_failure('Failed to assert that variable is FALSE');
        }
        return $this;
    }

}
