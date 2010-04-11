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
 * @see phpRack_Adapters_Pear_Package
 */
require_once PHPRACK_PATH . '/Adapters/Pear/Package.php';

/**
 * PEAR adapter used for checking PEAR packages availability
 *
 * @package Adapters
 */
class phpRack_Adapters_Pear
{
    /**
     * Find and create new package
     *
     * @param string Package name
     * @return phpRack_Adapters_Pear_Package|null
     * @throws Exception If PEAR is not installed properly
     * @see phpRack_Package_Pear::package()
     */
    public function getPackage($name)
    {
        $command = 'pear info ' . escapeshellarg($name);
        /**
         * @see phpRack_Adapters_Shell_Command
         */
        require_once PHPRACK_PATH . '/Adapters/Shell/Command.php';
        $result = phpRack_Adapters_Shell_Command::factory($command)->run();
        
        if (!$result) {
            throw new Exception('PEAR is not installed properly');
        }
        if (!preg_match('/^Release Version\s+(\S+)/m', $result)) {
            return null;
        }
        return new phpRack_Adapters_Pear_Package($name);
    }
}
