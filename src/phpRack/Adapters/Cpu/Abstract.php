<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * Abstract CPU adapter
 *
 * @package Adapters
 * @subpackage Cpu
 * @see phpRack_Adapters_Cpu::factory()
 */
abstract class phpRack_Adapters_Cpu_Abstract
{
    /**
     * Get CPU BogoMips
     *
     * @return float
     * @throws phpRack_Exception If unable to get BogoMips
     * @see phpRack_Package_Cpu_Performance::atLeast()
     */
    abstract public function getBogoMips();

    /**
     * Get CPU frequency in MHz
     *
     * @return float
     * @throws phpRack_Exception If can't get cpu frequency
     * @see getBogoMips()
     */
    abstract public function getCpuFrequency();
}
