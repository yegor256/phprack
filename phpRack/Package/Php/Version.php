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
 * PHP-version related assertions
 *
 * @package Tests
 */
class PhpRack_Package_Php_Version extends PhpRack_Package
{

    /**
     * Current version is newer than given one?
     *
     * @param string Version name
     * @return $this
     * @see http://www.php.net/manual/en/function.version-compare.php
     */
    public function atLeast($version) 
    {
        if (version_compare(phpversion(), $version) >= 0) {
            $this->_success('PHP version is ' . phpversion() . ", newer or equal to {$version}");
        } else {
            $this->_failure('PHP version is ' . phpversion() . ", older than {$version}");
        }
        return $this;
    }
        
}
