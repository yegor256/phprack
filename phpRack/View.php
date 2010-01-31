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
 *
 *
 * @package Tests
 */
class PhpRack_View
{
    
    /**
     * Runner of all tests
     *
     * @var PhpRack_Runner
     */
    protected $_runner;
    
    /**
     * Construct the class
     *
     * @return void
     */
    public function __construct(PhpRack_Runner $runner) 
    {
        $this->_runner = $runner;
    }
    
    /**
     * Render the view and return HTML
     *
     * @return HTML
     */
    public function render() 
    {
        ob_start();
        include PHPRACK_PATH . '/layout/index.phtml';
        return ob_get_clean();
    }
    
}
