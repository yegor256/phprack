<?php
/**
 * AAAAA
 *
 * @version $Id: Linux.php 447 2010-04-24 03:59:09Z yegor256@yahoo.com $
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
 * Darwin CPU Adapter (Mac OS)
 *
 * @package Adapters
 * @subpackage Cpu
 * @todo #17 Should be implemented
 */
class phpRack_Adapters_Cpu_Darwin extends phpRack_Adapters_Cpu_Abstract
{
    /**
     * Get CPU BogoMips
     *
     * @return float
     * @see phpRack_Package_Cpu_Performance::atLeast()
     * @see phpRack_Adapters_Cpu_Abstract::getBogoMips()
     */
    public function getBogoMips()
    {
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
        /**
         * @see phpRack_Adapters_Shell_Command
         */
        require_once PHPRACK_PATH . '/Adapters/Shell/Command.php';
        $data = phpRack_Adapters_Shell_Command::factory(
            'system_profiler SPHardwareDataType -xml 2>&1'
        )->run();
        $xml = simplexml_load_string($data);
        if (!($xml instanceof SimpleXMLElement)) {
            throw new phpRack_Exception("Invalid result from system_profiler: '{$data}'");
        }
        $nodes = $xml->xpath('//string[preceding-sibling::key="current_processor_speed"]');
        $node = strval($nodes[0]);

        if (strpos($node, 'GHz') === false) {
            throw new phpRack_Exception("Strange frequency from system_profiler: '{$node}'");
        }

        return floatval($node) * 1000;
    }
}
