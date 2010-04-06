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
 * @see phpRack_Adapters_File
 */
require_once PHPRACK_PATH . '/Adapters/File.php';

/**
 * File informations and content
 *
 * @package Tests
 */
class phpRack_Package_Disc_File extends phpRack_Package
{
    /**
    * Buffer used is tail function to read blocks from file end
    */
    const READ_BUFFER_SIZE = 1024;

    /**
     * Check that file exists
     *
     * @param string File name to check
     * @return boolean True if file exists
     */
    protected function _isFileExists($fileName)
    {
        if (!file_exists($fileName)) {
            $this->_failure("File {$fileName} is not found");
            return false;
        }

        $this->_log("File '{$fileName}' (" . filesize($fileName) . ' bytes):');
        return true;
    }

    /**
     * Show the content of the file
     *
     * @param string File name to display
     * @return $this
     */
    public function cat($fileName)
    {
        $fileName = phpRack_Adapters_File::factory($fileName)->getFileName();

        // Check that file exists
        if (!$this->_isFileExists($fileName)) {
            return $this;
        }

        $this->_log(file_get_contents($fileName));
            
        return $this;
    }

    /**
     * Show last x lines from the file
     *
     * @param string File name
     * @param string How many lines to display?
     * @return $this
     */
    public function tail($fileName, $linesCount)
    {
        $fileName = phpRack_Adapters_File::factory($fileName)->getFileName();

        // Check that file exists
        if (!$this->_isFileExists($fileName)) {
            return $this;
        }

        // Open file and move pointer to end of file
        $fp = fopen($fileName, 'rb');
        fseek($fp, 0, SEEK_END);

        // Read offset of end of file
        $offset = ftell($fp);

        // set ajax option with file end offset for usage in next Ajax request
        $test = $this->_result->getTest();
        if ($test) {
            $test->setAjaxOptions(
                array(
                    'data' => array('fileLastOffset' => $offset)
                )
            );
        }
        $content = '';

        do {
            // Move file pointer for new read
            $offset = max(0, $offset - self::READ_BUFFER_SIZE);
            fseek($fp, $offset, SEEK_SET);

            $readBuffer = fread($fp, self::READ_BUFFER_SIZE);
            $linesCountInReadBuffer = substr_count($readBuffer, "\n");

            // If we have enought lines extract from last readed fragment only required lines
            if ($linesCountInReadBuffer >= $linesCount) {
                $readBuffer = implode("\n", array_slice(explode("\n", $readBuffer), -$linesCount));
            }

            // Update how many lines still need to be readed
            $linesCount -= $linesCountInReadBuffer;

            // Attach last readed lines at beggining of earlier readed fragments
            $content = $readBuffer . $content;
        } while ($offset > 0 && $linesCount > 0);

        $this->_log($content);
        return $this;
    }

    /**
     * Show last x lines from the file, and refresh it imediatelly
     *
     * @param string File name
     * @param string How many lines to display?
     * @param string How many seconds each line should be visible
     * @return $this
     * @see phpRack_Runner::run()
     */
    public function tailf($fileName, $linesCount, $lineVisible)
    {
        $fileName = phpRack_Adapters_File::factory($fileName)->getFileName();
        $test = $this->_result->getTest();
        if ($test) {
            $test->setAjaxOptions(
                array(
                    'reload' => 0.5, //500ms I think is okey for delay between requests, can be lower
                    'lineVisible' => $lineVisible,
                    'linesCount' => $linesCount,
                    'attachOutput' => true
                )
            );
            $options = $test->getAjaxOptions();
        } else {
            $options = array();
        }

        // if it is first request send all x last lines
        if (!isset($options['fileLastOffset'])) {
            $this->tail($fileName, $linesCount);
            return;
        }

        $fp = fopen($fileName, 'rb');
        // get only new content since last time
        $content = stream_get_contents($fp, -1, $options['fileLastOffset']);

        // save current offset
        $offset = ftell($fp);
        fclose($fp);

        $this->_log($content);

        // set ajax option with new file end offset for usage in next Ajax request
        if ($test) {
            $test->setAjaxOptions(
                array(
                    'data' => array('fileLastOffset' => $offset),
                )
            );
        }
    }

    /**
     * Show first x lines from the file
     *
     * @param string File name
     * @param string How many lines to display?
     * @return $this
     */
    public function head($fileName, $linesCount)
    {
        $fileName = phpRack_Adapters_File::factory($fileName)->getFileName();

        // Check that file exists
        if (!$this->_isFileExists($fileName)) {
            return $this;
        }

        $content = '';
        $readedLinesCount = 0;
        $fp = fopen($fileName, 'rb');

        // Read line by line until we have required count or we reach EOF
        while ($readedLinesCount < $linesCount && !feof($fp)) {
            $content .= fgets($fp);
            $readedLinesCount++;
        }

        fclose($fp);
        $this->_log($content);
        return $this;
    }

    /**
     * Checks whether a file exists
     *
     * @param string File name to check
     * @return $this
     */
    public function exists($fileName)
    {
        $fileName = phpRack_Adapters_File::factory($fileName)->getFileName();

        clearstatcache();
        if (file_exists($fileName)) {
            $this->_success("File '{$fileName}' exists");
        } else {
            $this->_failure("File '{$fileName}' does not exist");
        }
        return $this;
    }

    /**
     * Checks whether a file is readable
     *
     * @param string File name to check
     * @return $this
     */
    public function isReadable($fileName)
    {
        $fileName = phpRack_Adapters_File::factory($fileName)->getFileName();

        clearstatcache();
        if (is_readable($fileName)) {
            $this->_success("File '{$fileName}' is readable");
        } else {
            $this->_failure("File '{$fileName}' is not readable");
        }
        return $this;
    }

    /**
     * Check whether a file is writable
     *
     * @param string File name to check
     * @return $this
     */
    public function isWritable($fileName)
    {
        $fileName = phpRack_Adapters_File::factory($fileName)->getFileName();

        clearstatcache();
        if (is_writable($fileName)) {
            $this->_success("File '{$fileName}' is writable");
        } else {
            $this->_failure("File '{$fileName}' is not writable");
        }
        return $this;
    }

    /**
     * Check whether the filename is a directory
     *
     * @param string File name to check
     * @return $this
     */
    public function isDir($fileName)
    {
        $fileName = phpRack_Adapters_File::factory($fileName)->getFileName();

        clearstatcache();
        if (is_dir($fileName)) {
            $this->_success("File '{$fileName}' is a directory");
        } else {
            $this->_failure("File '{$fileName}' is not a directory");
        }
        return $this;
    }
}
