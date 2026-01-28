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
 * @see phpRack_Adapters_Url
 */
require_once PHPRACK_PATH . '/Adapters/Url.php';

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * Network check using Url.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Network_Url extends phpRack_Package
{
    /**
     * Adapter used for communication with the server.
     *
     * @var phpRack_Adapters_Url
     * @see url()
     * @see regexp()
     */
    private $_adapter;

    /**
     * Contains URL passed to url() function, used in other
     * methods of this class.
     *
     * @var string
     * @see url()
     * @see regex()
     */
    private $_url;

    /**
     * Set URL and validate it.
     *
     * @param $url string URL
     * @param $options array Options which will affect connection
     * @return $this
     * @throws phpRack_Exception if URL is invalid
     */
    public function url($url, $options = array())
    {
        $this->_adapter = phpRack_Adapters_Url::factory($url, $options);
        $this->_url = $url;
        return $this;
    }

    /**
     * Make HTTP call and check that pattern exists in result.
     *
     * @param $pattern string Pattern to check
     * @return $this
     * @see url()
     * @throws phpRack_Exception If this method is called before url()
     * @throws phpRack_Exception If can't connect
     */
    public function regex($pattern = '/.*/')
    {
        if (empty($this->_url)) {
            throw new phpRack_Exception('url() function must be called before');
        }
        $content = $this->_adapter->getContent();
        $found = @preg_match($pattern, $content);
        // If regexp pattern is invalid, try to use it as string pattern
        if ($found === false) {
            $this->_log('Regex is not valid, try to use it as string');
            $found = (strpos($content, $pattern) !== false);
        }
        // If pattern was found in content
        if ($found) {
            $this->_success("Pattern '{$pattern}' was found on '{$this->_url}'");
        } else {
            $this->_failure("Pattern '{$pattern}' was NOT found on '{$this->_url}'");
        }
        return $this;
    }
    /**
     * Checks for http response code.
     * @var string regexp pattern of the valid http response code.
     * @return  boolean.
     * @throws phpRack_Exception If this method is called before url()
     */
    public function responseCode($pattern)
    {
        if (empty($this->_url)) {
            throw new phpRack_Exception('url() function must be called before');
        }
        $code = $this->_adapter->getResponseCode();
        if (preg_match($pattern, $code)) {
            $this->_success("Valid response code {$code} was returned by the link '{$this->_url}'");
        } else {
            $this->_failure("Invalid response code {$code} was returned by the link '{$this->_url}'");
        }
    }
}
