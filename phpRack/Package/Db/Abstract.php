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
 * Db abstract
 *
 * @package Tests
 */
abstract class phpRack_Package_Db_Abstract extends phpRack_Package
{
    /**
     * @todo #6 Should we have this method here or it is too detailed on this abstraction level?
     * @todo #6 What method should be used to "connect" with file databases like sqlite?
     *           We must support there other params like "path to file" for example.
     *           Maybe instead "connect" we should use more abstracted method?
     */
    abstract public function connect($host, $port, $username, $password);

    /**
     *
     * 
     */
    abstract public function dbExists($dbName);
    
    /**
     *
     *
     */
    abstract public function tableExists($tableName);
}
