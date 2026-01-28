<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 *
 * @version $Id: Package.php 82 2010-03-16 13:46:41Z yegor256@yahoo.com $
 * @category phpRack
 * @package Adapters
 */

/**
 * One file to work with
 *
 * @package Adapters
 */
class phpRack_Adapters_File
{

    /**
     * Absolute file name
     *
     * @var string
     */
    protected $_fileName;

    /**
     * Constructor
     *
     * @param string File name
     * @return void
     * @see _convertFileName()
     */
    public function __construct($fileName)
    {
        $this->_fileName = $this->_convertFileName($fileName);
    }

    /**
     * Create an instance of this class
     *
     * @param string File name
     * @return phpRack_Adapters_File
     * @see _convertFileName()
     */
    public static function factory($fileName)
    {
        return new self($fileName);
    }

    /**
     * Returns an absolute file name
     *
     * @param string File name
     * @return string
     * @see _convertFileName()
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /**
     * Converts file name from any form possible to an absolute path
     *
     * For example, you can use it like this, inside any package:
     *
     * <code>
     * // convert it to PHPRACK_PATH . '/../test.php'
     * $file = $this->_convertFileName('/../test.php');
     * // returns '/home/my/test.php'
     * $file = $this->_convertFileName('/home/my/test.php');
     * // returns 'c:/Windows/System32/my.dll'
     * $file = $this->_convertFileName('c:/Windows/System32/my.dll');
     * </code>
     *
     * If the file not found, it doesn't affect the result of this method. The
     * result always contain an absolute path of the file. This method doesn't
     * do any operations with the file, just re-constructs its name.
     *
     * @param string File name, as it is provided (raw form)
     * @return string
     */
    protected function _convertFileName($fileName)
    {
        switch (true) {
            // relative name started with '/..', or '../', or './'
            case preg_match('/^\/?\.\.?\//', $fileName):
                return PHPRACK_PATH . '/' . $fileName;

            default:
                return $fileName;
        }
    }

}
