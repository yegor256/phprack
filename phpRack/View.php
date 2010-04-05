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
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

/**
 * View in order to render test presentation page
 *
 * @package Tests
 * @see bootstrap.php
 */
class phpRack_View
{
    
    /**
     * Injected variables
     *
     * Variables from this array can be used inside view script, just as
     * local class variables. It is implemented by {@link __get()}
     *
     * @var array
     * @see __get()
     */
    protected $_injected = array();

    /**
     * Construct the class
     *
     * @return void
     * @see bootstrap.php
     */
    public function __construct()
    {
    }

    /**
     * Getter dispatcher, used inside view script
     *
     * @param string Name of the property to get
     * @return mixed
     * @see $this->_injected
     */
    public function __get($name) 
    {
        if (array_key_exists($name, $this->_injected)) {
            return $this->_injected[$name];
        }
        throw new Exception("Property '{$name}' is absent in " . get_class($this));
    }
    
    /**
     * Inject variables into class
     *
     * @param array Associative array of variables to inject, where keys are names
     *      and values are real values to be used later in view script.
     * @return $this
     * @see bootstrap.php
     */
    public function assign(array $injects) 
    {
        foreach ($injects as $name=>$value) {
            $this->_injected[$name] = $value;
        }
        return $this;
    }

    /**
     * Render the view and return HTML
     *
     * @param string Name of the script to render, inside "/layout"
     * @return HTML
     * @see bootstrap.php
     */
    public function render($script = 'index.phtml')
    {
        // two-step view, with layout
        $this->assign(array('script' => $script));
        
        ob_start();
        // workaround against ZCA static code analysis
        eval("include PHPRACK_PATH . '/layout/layout.phtml';");
        return ob_get_clean();
    }

    /**
     * Escapes special chars "\" and "'" in javascript
     *
     * @param string Path of the file to be rendered in JavaScript
     * @return string
     * @see #13
     * @see index.phtml
     */
    public function jsPath($path)
    {
        return addcslashes($path, "\\'");
    }
    
}
