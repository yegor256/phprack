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
 * Iterator filter class which help to get only files which match filter rules
 *
 * @package Adapters
 */
class phpRack_Adapters_Files_DirectoryFilterIterator extends FilterIterator
{
    
    /**
     * Regular expression pattern used to determine what files should be ignored
     *
     * @var string
     */
    private $_excludePattern;

    /**
     * Regular expression pattern used to determine what files should returned
     *
     * @var string
     */
    private $_extensionsPattern;

    /**
     * Create new iterator from directory path
     *
     * @param string Path
     * @return phpRack_Adapters_Files_DirectoryFilterIterator
     */
    public static function factory($dir) 
    {
        return new self(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir)
            )
        );
    }

    /**
     * Set which extensions will be used as whitelist
     *
     * @param string Comma separated list of extensions
     * @return void
     */
    public function setExtensions($extensions)
    {
        $extensions = explode(',', $extensions);

        // Escape extension special chars to have always valid regular expression
        foreach ($extensions as &$extension) {
            preg_quote(trim($extension));
        }

        $this->_extensionsPattern = '#(\.' . implode('|', $extensions). '$)#';
    }

    /**
     * Set pattern which will be used as blacklist
     *
     * @param string Regular expression pattern
     * @return void
     */
    public function setExclude($excludePattern)
    {
        $this->_excludePattern = $excludePattern;
    }

    /**
     * Callback function which will be called to determine current file should be in collection or no
     *
     * @return boolean
     */
    public function accept()
    {
        $file = $this->current();

        // Ignore "dots files" which appear in some systems
        if (($file == '.') || ($file == '..')) {
            return false;
        }

        // Ignore files which don't match extensionsPattern
        if ($this->_extensionsPattern
            && !preg_match($this->_extensionsPattern, $file)
        ) {
            return false;
        }

        // Ignore files which match excludePattern
        if ($this->_excludePattern && preg_match($this->_excludePattern, $file)) {
            return false;
        }

        // Everything rest is allowable
        return true;
    }
    
}
