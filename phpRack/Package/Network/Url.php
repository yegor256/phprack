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
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * @see phpRack_Adapters_Url
 */
require_once PHPRACK_PATH . '/Adapters/Url.php';

/**
 * Network check using Url
 *
 * @package Tests
 */
class phpRack_Package_Network_Url extends phpRack_Package
{
    /**
     * Adapter used for cummunication with server
     *
     * @var phpRack_Adapters_Url
     * @see url()
     * @see regexp()
     */
    private $_adapter;

    /**
     * Contain url passed to url function, used in other methods of this class
     *
     * @var string
     * @see url()
     * @see regex()
     */
    private $_url;

    /**
     * Set URL and validate it
     *
     * @param string URL
     * @param array Options which will affect connection
     * @return $this
     * @throws Exception if URL is invalid
     */
    public function url($url, $options = array())
    {
        $this->_adapter = phpRack_Adapters_Url::factory($url, $options);
        $this->_url = $url;
        return $this;
    }

    /**
     * Make HTTP call and check that pattern exists in result
     *
     * @param string Pattern to check
     * @return $this
     * @see url()
     * @throws Exception If this method is called before url()
     * @throws Exception If can't connect
     */
    public function regex($pattern)
    {
        if (empty($this->_url)) {
            throw new Exception('url() function must be called before');
        }

        $content = $this->_adapter->getContent();

        // If pattern was found in content
        if (preg_match($pattern, $content)) {
            $this->_success("Pattern '{$pattern}' was found on '{$this->_url}'");
        } else {
            $this->_failure("Pattern '{$pattern}' was NOT found on '{$this->_url}'");
        }

        return $this;
    }
}
