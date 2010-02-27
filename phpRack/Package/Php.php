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
 * PHP related assertions
 *
 * @package Tests
 */
class phpRack_Package_Php extends phpRack_Package
{
    
    /**
     * Show phpinfo() in proper format
     *
     * @return $this
     */
    public function phpinfo() 
    {
        ob_start();
        phpinfo(INFO_ALL);
        $html = ob_get_clean();
        
        // maybe it's CLI version, not HTML?
        if (strpos($html, '<') !== 0) {
            $this->_log($html);
            return;
        }
        
        // clean HTML out of special symbols
        $html = preg_replace('/&(#\d+|\w+);/', ' ', $html);
        
        $lines = array();
        $xml = simplexml_load_string($html);
        foreach ($xml->xpath("//tr | //h1 | //h2") as $line) {
            switch (strtolower($line->getName())) {
                case 'tr':
                    $ln = '';
                    foreach ($line->children() as $td) {
                        $ln .= trim(strval($td)) . "\t";
                    }
                    $line = $ln;
                    break;
                case 'h1':
                    $line = "\n\n" . $line;
                    break;
                case 'h2':
                    $line = "\n" . $line;
                    break;
            }
            $lines[] = strval($line);
        }
        $this->_log(implode("\n", $lines));
        
        return $this;
    }
    
}
