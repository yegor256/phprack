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
 * This class is used as a view component in our simple MVC pattern, where
 * model is {@link phpRack_Runner}, view is {@link phpRack_View} and controller is a simple
 * script in {@link bootstrap.php}. An instance of this class is accepting
 * variables and a script name in order to render it, for example:
 *
 * <code>
 * $view = new phpRack_View();
 * $view->assign(array('name' => 'My Name To Render'));
 * $html = $view->render('index.phtml');
 * </code>
 *
 * In this example, you can access "name" inside "index.phtml" like this:
 * $this->name.
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
        return $this->compressedHtml(ob_get_clean());
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
    
    /**
     * Compress HTML content
     *
     * @param string HTML content, before compression
     * @return string HTML content, compressed
     * @todo we should remove COMMENTS from XML here, I don't know how, so far...
     *      The method used in compressedCss() is not suitable here, since
     *      we will damage the content of <script>, <style>, <pre>, and <textarea>
     *      tags. Thus, I think that we should use DOM+XPath in order to go through
     *      the XML document and remove all comments. Than we should get the
     *      string version of the document from the container.
     */
    public function compressedHtml($html) 
    {
        return $html;
        // $xml = simplexml_load_string($html);
        // return $xml->asXML();
    }
    
    /**
     * Return a compressed version of CSS
     *
     * @param string Relative path of CSS script, inside /layout dir
     * @return string CSS content compressed
     */
    public function compressedCss($css) 
    {
        $content = file_get_contents(PHPRACK_PATH . '/layout/' . $css);
        $replacers = array(
            '/[\n\r\t]+/' => ' ', // remove duplicated white spaces
            '/\s+/' => ' ', // convert multiple spaces to single
            '/\s+([\,\:\{\}])/' => '${1}', // compress leading white spaces
            '/([\,\;\:\{\}])\s+/' => '${1}', // compress trailing white spaces
            '/\/\*.*?\*\//' => '', // kill comments at all
        );
        return preg_replace(
            array_keys($replacers), 
            $replacers,
            $content
        );
    }
    
}
