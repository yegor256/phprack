<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * @see phpRack_Adapters_Pear_Package
 */
require_once PHPRACK_PATH . '/Adapters/Pear/Package.php';

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

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
     * @throws phpRack_Exception If PEAR is not installed properly
     * @see phpRack_Package_Pear::package()
     */
    public function getPackage($name)
    {
        $package = new phpRack_Adapters_Pear_Package($name);
        try {
            $package->getVersion();
        } catch (phpRack_Exception $e) {
            assert($e instanceof phpRack_Exception); // for ZCA only
            return null;
        }
        return $package;
    }

    /**
     * Get full list of installed packages
     *
     * @return array of phpRack_Adapters_Pear_Package
     * @throws phpRack_Exception If some problem appear during package information loading
     * @see phpRack_Package_Pear::showList()
     */
    public function getAllPackages()
    {
        $packages = array();
        $command = 'pear list -a';
        /**
         * @see phpRack_Adapters_Shell_Command
         */
        require_once PHPRACK_PATH . '/Adapters/Shell/Command.php';
        $result = phpRack_Adapters_Shell_Command::factory($command)->run();

        // divide command output by channels
        foreach (explode("\n\n", $result) as $channel) {
            $lines = explode("\n", $channel);
            $matches = array();

            // get channel name
            if (!preg_match('/CHANNEL ([^:]+):/', $lines[0], $matches)) {
                continue;
            }

            $channelName = $matches[1];

            // skip 3 first lines (channel, separator, packages header line)
            $packageLines = array_slice($lines, 3);

            foreach ($packageLines as $packageLine) {
                // get package name
                if (preg_match('/^(\w+)/', $packageLine, $matches)) {
                    // set full package name with channel
                    $packageName = "{$channelName}/{$matches[1]}";
                    $packages[] = new phpRack_Adapters_Pear_Package($packageName);
                }
            }
        }

        return $packages;
    }
}
