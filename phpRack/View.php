<?php
/**
 * phpRack: Integration Testing Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL: http://www.phprack.com/LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@phprack.com so we can send you a copy immediately.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright (c) 2009-2012 phpRack.com
 * @version $Id$
 * @category phpRack
 * @package Tests
 * @subpackage core
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
 * @see bootstrap.php
 * @package Tests
 * @subpackage core
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
     * @param $name string Name of the property to get
     * @return mixed
     * @see $this->_injected
     * @throws phpRack_Exception If such property is absent
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_injected)) {
            return $this->_injected[$name];
        }
        throw new phpRack_Exception("Property '{$name}' is absent in " . get_class($this));
    }

    /**
     * Inject variables into class
     *
     * @param $injects array Associative array of variables to inject, where keys are names
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
     * @param $script string Name of the script to render, inside "/layout"
     * @return string HTML
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
     * @param $path string Path of the file to be rendered in JavaScript
     * @return string
     * @see #13
     * @see index.phtml
     */
    public function jsPath($path)
    {
        return addcslashes($path, "\\'");
    }

    /**
     * Compress JS code
     *
     * @param $jsCode string Javascript code, before compression
     * @return string Javascript code, compressed
     * @see compressedHtml
     */
    protected function _compressJs($jsCode)
    {
        // don't compress code when we use instrumented version, to avoid errors after compression
        if (defined('INSTRUMENTED')) {
            return $jsCode;
        }

        $replacers = array(
            '/\/\*.*?\*\//s'   => '', // remove multi line comments
            '/\/\/.*\r?\n\s*/' => '', // remove single line comments
            '/\s*\r?\n\s*/'    => '', // remove lines end with leading/trailing spaces
            '/\s+/'            => ' ', // convert multiple spaces to single
            '/\s?([\(\)\,\;=\'\"\-\+:\*&])\s?/' => '${1}', // compress unnecessary spaces
        );

        return preg_replace(
            array_keys($replacers),
            $replacers,
            $jsCode
        );
    }

    /**
     * Compress HTML content
     *
     * @param $html string HTML content, before compression
     * @return string HTML content, compressed
     */
    public function compressedHtml($html)
    {
        if (!class_exists('DOMDocument')) {
            $replacers = array(
                '/<!--.*?-->/s'    => '', // remove multi line comments
            );
            $html = preg_replace(
                array_keys($replacers),
                $replacers,
                $html
            );
            $offset = 0;
            $startPattern = '<script type="text/javascript">';
            $endPattern = '</script>';
            $lp = 0;

            while (($scriptStartPos = strpos($html, $startPattern, $offset)) !== false) {
                $offset = $scriptStartPos + 1;

                // first script is minified jQuery lib, so doesn't need additional compression
                if (++$lp === 1) {
                    continue;
                }

                $scriptEndPos = strpos($html, $endPattern, $scriptStartPos);
                if ($scriptEndPos === false) {
                    throw new phpRack_Exception('<script> tag is not closed');
                }
                $length = $scriptEndPos - $scriptStartPos + 1;
                $html = substr_replace(
                    $html,
                    $this->_compressJs(substr($html, $scriptStartPos, $length)),
                    $scriptStartPos,
                    $length
                );
            }

            return $html;
        }

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXml($html);

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('xhtml', 'http://www.w3.org/1999/xhtml');
        $comments = $xpath->query('//comment()');
        foreach ($comments as $comment) {
            $comment->parentNode->removeChild($comment);
        }

        // skip compressing first script, because it is minified version of jQuery
        $scripts = $xpath->query('//xhtml:script[position() > 1]');

        foreach ($scripts as $script) {
            foreach ($script->childNodes as $childNode) {
                if ($childNode->nodeType == XML_CDATA_SECTION_NODE) {
                    $childNode->nodeValue = "\n" . $this->_compressJs($childNode->nodeValue);
                }
            }
        }
        /**
         * fix output due to libxml2 bug described in #53
         */

        return preg_replace('/<!\[CDATA\[\s*(\/\/)?\]\]>/', '//', $dom->saveXml());
    }

    /**
     * Return a compressed version of CSS
     *
     * @param $css string Relative path of CSS script, inside /layout dir
     * @return string CSS content compressed
     */
    public function compressedCss($css)
    {
        $content = file_get_contents(PHPRACK_PATH . '/layout/' . $css);
        $replacers = array(
            '/[\n\r\t]+/'         => ' ', // remove duplicated white spaces
            '/\s+/'               => ' ', // convert multiple spaces to single
            '/\s+([\,\:\{\}])/'   => '${1}', // compress leading white spaces
            '/([\,\;\:\{\}])\s+/' => '${1}', // compress trailing white spaces
            '/\/\*.*?\*\//'       => '', // kill comments at all
        );

        return preg_replace(
            array_keys($replacers),
            $replacers,
            $content
        );
    }

}
