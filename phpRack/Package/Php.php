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
                    $line = "\n\n= {$line} =";
                    break;
                case 'h2':
                    $line = "\n== {$line} ==";
                    break;
            }
            $lines[] = strval($line);
        }
        $this->_log(implode("\n", $lines));
        
        return $this;
    }

    /**
     * Check files in directory have correct php syntax
     *
     * @param string Directory path to check
     * @param array List of options
     * @return $this
     */
    public function lint($dir, array $options = array())
    {
        $dir = $this->_convertFileName($dir);
        if (!file_exists($dir)) {
            $this->_failure("Directory '{$dir}' does not exist");
            return $this;
        }

        // Create our file iterator
        require_once PHPRACK_PATH . '/Adapters/Files/DirectoryFilterIterator.php';
        $iterator = phpRack_Adapters_Files_DirectoryFilterIterator::factory($dir);

        if (!empty($options['exclude'])) {
            $iterator->setExclude($options['exclude']);
        }

        if (!empty($options['extensions'])) {
            $iterator->setExtensions($options['extensions']);
        }

        foreach ($iterator as $file) {
            $command = 'php -l ' . escapeshellarg($file->getPathname()) . ' 2>&1';
            $output = shell_exec($command);

            if (preg_match('#^No syntax errors detected#', $output)) {
                $this->_success("File '{$file->getPathname()}' is valid");
            } else {
                $this->_failure("File '{$file->getPathname()}' is NOT valid");
                $this->_log($output);
            }
        }
        return $this;
    }
    
}
