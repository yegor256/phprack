<?php
/**
 * AAAAA
 *
 * @category phpRack
 * @package Tests
 * @subpackage core
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * @see phpRack_Result
 */
require_once PHPRACK_PATH . '/Result.php';

/**
 * @see phpRack_Test
 */
require_once PHPRACK_PATH . '/Test.php';

/**
 * One single test assertion
 *
 * @see phpRack_Test::__get()
 * @property-read phpRack_Package_Cpu $cpu CPU related assertions
 * @property-read phpRack_Package_Db $db DB related assertions
 * @property-read phpRack_Package_Disc $disc Local HDD related assertions
 * @property-read phpRack_Package_Network $network Network-related assertions
 * @property-read phpRack_Package_Php $php PHP related assertions
 * @property-read phpRack_Package_Qos $qos QOS related assertions
 * @property-read phpRack_Package_Shell $shell Assertions related to SHELL
 * @property-read phpRack_Package_Simple $simple Simple package, for simple assertions
 * @package Tests
 * @subpackage core
 */
class phpRack_Assertion
{

    /**
     * Result collector
     *
     * @var phpRack_Result
     * @see __construct()
     */
    protected $_result;

    /**
     * Construct the class
     *
     * @param phpRack_Test Test, which pushes results here
     * @return void
     * @see phpRack_Test::__get()
     */
    private function __construct(phpRack_Test $test)
    {
        $this->_result = new phpRack_Result($test);
    }

    /**
     * Create new assertion
     *
     * There is a combination of static factory() method and a private
     * constructor. However we don't have any static factory here, just an
     * incapsulation of constructor. Some time ago we had a static factory,
     * but then removed it. Maybe in the future we might get back to this
     * design approach.
     *
     * @param phpRack_Test Test that is using this assertion
     * @return phpRack_Assertion
     * @see phpRack_Test::__get()
     */
    public static function factory(phpRack_Test $test)
    {
        return new self($test);
    }

    /**
     * Dispatcher of calls to packages
     *
     * @param string Name of the package to get
     * @return phpRack_Package
     * @see phpRack_Test::_log() and many other methods inside Integration Tests
     */
    public function __get($name)
    {
        return phpRack_Package::factory($name, $this->_result);
    }

    /**
     * Call method, any one
     *
     * This magic method will be called when you're using any assertion and
     * some method inside it, for example:
     *
     * <code>
     * // inside your instance of phpRack_Test:
     * $this->assert->php->extensions->isLoaded('simplexml');
     * </code>
     *
     * The call in the example will lead you to this method, and will call
     * __call('simplexml', array()).
     *
     * @param string Name of the method to call
     * @param array Arguments to pass
     * @return mixed
     * @see PhpConfigurationTest::testPhpExtensionsExist isLoaded() reaches this point
     */
    public function __call($name, array $args)
    {
        return call_user_func_array(
            array(
                phpRack_Package::factory('simple', $this->_result),
                $name
            ),
            $args
        );
    }

    /**
     * Get instance of result collector
     *
     * @return phpRack_Result
     * @see phpRack_Test::_log() and many other methods inside Integration Tests
     */
    public function getResult()
    {
        return $this->_result;
    }

}
