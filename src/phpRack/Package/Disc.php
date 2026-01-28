<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Tests
 * @subpackage packages
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * Local HDD related assertions
 *
 * @property-read phpRack_Package_Disc_File $file File information and content
 * @property-read phpRack_Package_Disc_FreeSpace $freeSpace Free space on HDD
 * @package Tests
 * @subpackage packages
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

        $this->_log("Directory tree '" . realpath($dir) . "':");

        // Create our file iterator
        require_once PHPRACK_PATH . '/Adapters/Files/DirectoryFilterIterator.php';
        $iterator = phpRack_Adapters_Files_DirectoryFilterIterator::factory($dir);
        if (array_key_exists('exclude', $options)) {
            $iterator->setExclude($options['exclude']);
        }

        if (array_key_exists('maxDepth', $options)) {
            $iterator->setMaxDepth($options['maxDepth']);
        }
        $this->_log(
            implode(
                "\n",
                $this->_convertDirectoriesToLines($iterator, $dir)
            )
        );

        return $this;
    }

    /**
     * Convert list of files to lines to show
     *
     * @param Iterator List of files
     * @param string Parent directory name, absolute
     * @return void
     * @see showDirectory()
     */
    protected function _convertDirectoriesToLines(Iterator $iterator, $dir)
    {
        $lines = array();
        foreach ($iterator as $file) {
            $name = substr($file, strlen($dir) + 1);

            $line = str_repeat('  ', substr_count($name, '/')) . $file->getBaseName();
            $attribs = array();

            if ($file->isFile()) {
                $attribs[] = $file->getSize() . ' bytes';
                $attribs[] = date('d-M-y H:i:s', $file->getMTime());
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
