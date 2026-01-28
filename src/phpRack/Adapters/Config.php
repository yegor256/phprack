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
 * Config adapter used for store tests configuration and provide object
 * oriented access methods
 *
 * You can use it like this:
 *
 * <code>
 * $config = new phpRack_Adapters_Config(
 *   array(
 *     'alpha' => array(
 *       'beta' => 123,
 *     )
 *   )
 * );
 * assert($config->alpha->beta == 123);
 * </code>
 *
 * @package Adapters
 * @subpackage Configs
 */
class phpRack_Adapters_Config
{
    /**
     * Contains array of configuration data
     *
     * @var array
     * @see __construct()
     */
    protected $_data;

    /**
     * Create object oriented config container
     *
     * @param array Config data as array
     * @return void
     * @see ConfigTest::testConfigIni() and other integration tests
     */
    public function __construct(array $data)
    {
        $this->_data = array();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->_data[$key] = new self($value);
            } else {
                $this->_data[$key] = $value;
            }
        }
    }

    /**
     * Magic method which provide access to configuration options
     *
     * @param string Name of config option
     * @return mixed
     * @throws phpRack_Exception if config option not exists
     * @see ConfigTest::testConfigIni() and other integration tests
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->_data)) {
            throw new phpRack_Exception("Config option '{$name}' doesn't exist");
        }
        return $this->_data[$name];
    }

    /**
     * Magic method which provide possibility to check whether some
     * configuration option exists
     *
     * @param string Name of config option
     * @return boolean
     * @see ConfigTest::testConfigIni() and other integration tests
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }
}
