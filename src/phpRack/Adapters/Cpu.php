<?php
/**
 * AAAAA
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * CPU adapter used to get details about available processor
 *
 * @package Adapters
 * @subpackage Cpu
 */
class phpRack_Adapters_Cpu
{
    /**
     * CPU adapter factory return adapter depending on operating system
     *
     * @return phpRack_Adapters_Cpu_Abstract
     * @throws phpRack_Exception If OS is not supported
     * @see For MacOS I think we should use system_profiler shell command.
     *      After that we can parse it output in similar way like we do it for
     *      Windows or Linux
     * @todo #17 How about Mac OS? There is no /proc directory in Mac OS
     */
    public static function factory()
    {
        /**
         * @see phpRack_Adapters_Os
         */
        require_once PHPRACK_PATH . '/Adapters/Os.php';
        $os = phpRack_Adapters_Os::get();
        $classFile = PHPRACK_PATH . '/Adapters/Cpu/' . ucfirst($os) . '.php';

        if (!file_exists($classFile)) {
            throw new phpRack_Exception("OS '{$os}' is not supported yet");
        }
        eval('require_once $classFile;');
        $className = 'phpRack_Adapters_Cpu_' . ucfirst($os);
        return new $className();
    }
}
