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
 * @version $Id: Package.php 82 2010-03-16 13:46:41Z yegor256@yahoo.com $
 * @category phpRack
 */

/**
 * Url adapter
 *
 * @package Adapters
 * @todo #27 should be implemented using PHP sockets
 *           I think that we should NOT try to use CURL, since it's not always
 *           available in PHP.
 */
class phpRack_Adapters_Url
{
    
    /**
     * Constructor
     *
     * @param string URL
     * @return void
     */
    public function __construct($url) 
    {
        //...
    }
    
    /**
     * Factory, to simplify calls
     *
     * @param string URL
     * @return phpRack_Adapters_Url
     */
    public static function factory($url) 
    {
        return new self($url);
    }
    
    /**
     * The URL is accessible?
     *
     * @return boolean
     */
    public function isAccessible() 
    {
        // ...
    }
    
    /**
     * Get full content in the URL
     *
     * @return string Content
     * @throws Exception If can't get content for some reason
     */
    public function getContent() 
    {
        // ...
    }
            
}
