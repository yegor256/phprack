<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * @see phpRack_Adapters_Cpu_Abstract
 */
require_once PHPRACK_PATH . '/Adapters/Cpu/Abstract.php';

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * Windows CPU Adapter
 *
 * @package Adapters
 * @subpackage Cpu
 */
class phpRack_Adapters_Cpu_Windows extends phpRack_Adapters_Cpu_Abstract
{
    /**
     * Get CPU BogoMips
     *
     * @return float
     * @throws phpRack_Exception If unable to get BogoMips
     * @see phpRack_Package_Cpu_Performance::atLeast()
     * @see phpRack_Adapters_Cpu_Abstract::getBogoMips()
     */
    public function getBogoMips()
    {
        /**
         * On Windows return approximated result which can be calculated using
         * this formula: CPU clock * 2
         */
        return $this->getCpuFrequency() * 2;
    }

    /**
     * Get CPU frequency in MHz
     *
     * @return float
     * @throws phpRack_Exception If can't get cpu frequency
     * @see getBogoMips()
     * @see phpRack_Adapters_Cpu_Abstract::getCpuFrequency()
     */
    public function getCpuFrequency()
    {
        $wmi = new COM('Winmgmts://');
        $query = 'SELECT maxClockSpeed FROM CIM_Processor';

        // get CPUS-s data
        $cpus = $wmi->execquery($query);
        $maxClockSpeed = 0;

        /**
         * We must iterate through all CPU-s because $cpus is object
         * and we can't get single entry by $cpus[0]->maxClockSpeed
         */
        foreach ($cpus as $cpu) {
            $maxClockSpeed = max($maxClockSpeed, $cpu->maxClockSpeed);
        }

        /**
         * If returned $cpus set was empty(some error occured)
         *
         * We can't check it earlier with empty($cpus) or count($cpus)
         * because $cpus is object and doesn't implement countable
         * interface.
         */
        if (!$maxClockSpeed) {
            throw new phpRack_Exception(
                "Unable to get maxClockSpeed using COM 'Winmgmts://' and '{$query}' query"
            );
        }
        return floatval($maxClockSpeed);
    }
}
