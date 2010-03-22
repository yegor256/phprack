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
        require_once PHPRACK_PATH . '/Adapters/File.php';
        $dir = phpRack_Adapters_File::factory($dir)->getFileName();
        
        if (!file_exists($dir)) {
            $this->_failure("Directory '{$dir}' is absent");
            return $this;
        }
        
        $this->_log("Directory tree '{$dir}':");
        
        // Create our file iterator
        require_once PHPRACK_PATH . '/Adapters/Files/DirectoryFilterIterator.php';
        $iterator = phpRack_Adapters_Files_DirectoryFilterIterator::factory($dir);
        
        $this->_log(
            implode(
                "\n", 
                $this->_convertDirectoriesToLines($iterator, $dir, $options)
            )
        );
        
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
        $lines = array();
        foreach ($iterator as $file) {
            $name = substr($file, strlen($dir) + 1);
            
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
