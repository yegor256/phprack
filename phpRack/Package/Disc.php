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
 * Local HDD related assertions
 *
 * @package Tests
 */
class phpRack_Package_Disc extends phpRack_Package
{
    
    /**
     * Show directory structure
     *
     * @param string Relative path, in relation to the location of {@link PHPRACK_PATH} file
     * @param array List of options
     * @return $this
     */
    public function showDirectory($dir, array $options = array()) 
    {
        $dir = realpath($this->_convertFileName($dir));
        if (!$dir) {
            $this->_failure(
                "Directory 'PHPRACK_PATH.\$dir' is absent: '" .
                PHPRACK_PATH . "' . '{$dir}'"
            );
            return $this;
        }
        
        $this->_log("Directory tree: '{$dir}'");
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $lines = $this->_convertDirectoriesToLines($iterator, $dir, $options);
        $this->_log(implode("\n", $lines));
        
        return $this;
    }
    
    /**
     * Convert list of files to lines to show
     *
     * @param Iterator List of files
     * @param string Parent directory name, absolute
     * @param array List of options
     * @return void
     */
    protected function _convertDirectoriesToLines(Iterator $iterator, $dir, array $options) 
    {
        // list of directory prefixes to exclude from listing
        $exclude = array();
        if (isset($options['exclude'])) {
            $exclude = $options['exclude'];
            if (!is_array($exclude)) {
                $exclude = array($exclude);
            }
        }
        
        $lines = array();
        foreach ($iterator as $file) {
            $name = substr($file, strlen($dir) + 1);
            
            // strange sanity check against these names. they should not
            // be inside this iterator, but on some systems they are there
            if (($name == '.') || ($name == '..')) {
                continue;
            }
            
            $toExclude = false;
            
            foreach ($exclude as $regex) {
                if (preg_match($regex, $name)) {
                    $toExclude = true;
                }
            }
            if ($toExclude) {
                continue;
            }
            
            $line = str_repeat('  ', substr_count($name, '/')) . $file->getBaseName();
            $attribs = array();
            
            if ($file->isFile()) {
                $attribs[] = $file->getSize() . ' bytes';
                $attribs[] = date('m/d/y h:i:s', $file->getMTime());
                $attribs[] = sprintf('0x%o', $file->getPerms());
            }
            
            if ($file->isLink()) {
                $attribs[] = "link to '{$file->getRealPath()}']";
            }
            
            $lines[] = $line . ($attribs ? ': ' . implode('; ', $attribs) : false);
        }
        return $lines;
    }
    
}
