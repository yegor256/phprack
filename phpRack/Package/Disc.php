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
        $dir = realpath(PHPRACK_PATH . '/' . $dir);
        if (!$dir) {
            $this->_failure(
                "Directory 'PHPRACK_PATH.\$dir' is absent: '" .
                PHPRACK_PATH . "' . '{$dir}'"
            );
            return $this;
        }
        
        // list of directory prefixes to exclude from listing
        $exclude = array();
        if (isset($options['exclude'])) {
            $exclude = $options['exclude'];
            if (!is_array($exclude)) {
                $exclude = array($exclude);
            }
        }
        
        $lines = array();
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $file) {
            $name = substr($file, strlen($dir) + 1);
            $toExclude = false;
            
            foreach ($exclude as $regex) {
                if (preg_match($regex, $name)) {
                    $toExclude = true;
                }
            }
            if ($toExclude) {
                continue;
            }
            
            $lines[] = 
            str_repeat("\t", substr_count($name, '/')) . $file->getBaseName()
            . ($file->isFile() ? ': ' . $file->getSize() : false);
        }
        
        $this->_log(implode("\n", $lines));
        
        return $this;
    }
    
}
