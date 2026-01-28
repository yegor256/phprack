<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
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
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * CPU Performance check.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Cpu_Performance extends phpRack_Package
{
    /**
     * Check whether server CPU has least this BogoMips
     *
     * @param float required BogoMips
     * @return $this
     * @see PerformanceTest#testServerIsFast()
     */
    public function atLeast($bogoMips)
    {
        /**
         * @see phpRack_Adapters_Cpu
         */
        require_once PHPRACK_PATH . '/Adapters/Cpu.php';

        try {
            $cpu = phpRack_Adapters_Cpu::factory();
            $currentBogoMips = $cpu->getBogoMips();
            if ($currentBogoMips >= $bogoMips) {
                $this->_success("CPU is fast enough with '{$currentBogoMips}' BogoMips");
            } else {
                $this->_failure(
                    "CPU is too slow. " .
                    "It has only '{$currentBogoMips}' BogoMips, but '{$bogoMips}' is required"
                );
            }
        } catch (phpRack_Exception $e) {
            $this->_failure("CPU problem: {$e->getMessage()}");
        }
        return $this;
    }
}
