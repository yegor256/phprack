<?php
/**
 * AAAAA
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
 * Linux CPU Adapter
 *
 * @package Adapters
 * @subpackage Cpu
 */
class phpRack_Adapters_Cpu_Linux extends phpRack_Adapters_Cpu_Abstract
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
        $matches = array();
        // on Linux parse ouput of "cat /proc/cpuinfo" command
        if (
            !preg_match(
                '/^bogomips\s*:\s*(.*)/m',
                $this->_getCpuInfoData(), // Exception is possible here
                $matches
            )
        ) {
            throw new phpRack_Exception("Unable to find bogomips value in cpuinfo");
        }
        return floatval($matches[1]);
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
        // on Linux parse ouput of "cat /proc/cpuinfo" command
        $matches = array();
        if (
            !preg_match(
                '/^cpu MHz\s*:\s*(.*)/m',
                $this->_getCpuInfoData(), // Exception is possible here
                $matches
            )
        ) {
            throw new phpRack_Exception('Unable to find CPU MHz value in cpuinfo');
        }
        return floatval($matches[1]);
    }

    /**
     * Get result of "cat /proc/cpuinfo" shell command execution
     *
     * @return string
     * @throws phpRack_Exception If unable to execute shell command
     * @see getBogoMips()
     * @see getCpuFrequency()
     */
    private function _getCpuInfoData()
    {
        $command = 'cat /proc/cpuinfo';
        /**
         * @see phpRack_Adapters_Shell_Command
         */
        require_once PHPRACK_PATH . '/Adapters/Shell/Command.php';
        // Exception is possible here
        $result = phpRack_Adapters_Shell_Command::factory($command)->run();
        return $result;
    }
}
