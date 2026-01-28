<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * Iterator filter class which help to get only files which match filter rules
 *
 * @package Adapters
 * @subpackage Files
 * @see phpRack_Package_Disc::showDirectory()
 */
class phpRack_Adapters_Files_DirectoryFilterIterator extends FilterIterator
{

    /**
     * Directory we're iterating
     *
     * @var string
     * @see __construct()
     */
    protected $_dir;

    /**
     * Maximum depth to be visible
     *
     * @var integer
     * @see setMaxDepth()
     */
    private $_maxDepth = null;

    /**
     * Regular expression patterns used to determine what files should be ignored
     *
     * @var string[]
     */
    private $_excludePatterns;

    /**
     * Regular expression pattern used to determine what files should returned
     *
     * @var string
     */
    private $_extensionsPattern;

    /**
     * Constructor, private, don't call it directly, instead use factory()
     *
     * @param string Path
     * @return void
     * @see factory()
     */
    public function __construct($dir)
    {
        parent::__construct(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir),
                RecursiveIteratorIterator::SELF_FIRST
            )
        );
        $this->_dir = $dir;
    }

    /**
     * Create new iterator from directory path
     *
     * @param string Path
     * @return phpRack_Adapters_Files_DirectoryFilterIterator
     */
    public static function factory($dir)
    {
        return new self($dir);
    }

    /**
     * Set which extensions will be used as whitelist
     *
     * @param string|array Comma separated list of extensions, or list of them
     * @return $this
     */
    public function setExtensions($extensions)
    {
        if (!is_array($extensions)) {
            $extensions = explode(',', $extensions);
        }
        // Escape extension special chars to have always valid regular expression
        foreach ($extensions as &$extension) {
            $extension = preg_quote(trim($extension));
        }
        $this->_extensionsPattern = '/(\.' . implode('|', $extensions). ')$/';
        return $this;
    }

    /**
     * Set pattern which will be used as blacklist
     *
     * @param string|array Regular expression pattern, or list of them
     * @return $this
     */
    public function setExclude($excludePatterns)
    {
        if (!is_array($excludePatterns)) {
            $excludePatterns = array($excludePatterns);
        }
        $this->_excludePatterns = $excludePatterns;
        return $this;
    }

    /**
     * Set maximum directory depth
     *
     * @param integer Maximum depth
     * @return $this
     */
    public function setMaxDepth($maxDepth)
    {
        $this->_maxDepth = $maxDepth;
        return $this;
    }

    /**
     * Callback function which will be called to determine current file should be in collection or no
     *
     * @return boolean
     */
    public function accept()
    {
        $file = $this->current();
        return ($this->_validFile($file) && $this->_validByOptions($file));
    }

    /**
     * Validates file object by basic restrictions.
     * @param $file mixed
     * @return bool
     */
    private function _validFile($file)
    {
        if (is_dir($file)) {
            return false;
        }
        // Ignore "dots files" which appear in some systems
        if (trim($file, '.') == '') {
            return false;
        }
        if (!is_null($this->_maxDepth)
            && substr_count(substr($file, strlen($this->_dir) + 1), '/') > $this->_maxDepth
        ) {
            return false;
        }
        return true;
    }

    /**
     * Validates file by specific iterator options.
     * @param $file mixed
     * @return bool
     */
    private function _validByOptions($file)
    {
        // Ignore files which don't match extensionsPattern
        if ($this->_extensionsPattern && !preg_match($this->_extensionsPattern, $file)) {
            return false;
        }
        // Ignore files which match excludePattern
        if ($this->_excludePatterns) {
            foreach ($this->_excludePatterns as $pattern) {
                if (preg_match($pattern, $file)) {
                    return false;
                }
            }
        }
        return true;
    }

}
