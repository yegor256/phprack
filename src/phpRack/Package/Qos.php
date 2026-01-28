<?php
/**
 * AAAAA
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
 * QOS related assertions.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Qos extends phpRack_Package
{
    /**
     * Check latency for the given URL(s).
     *
     * <p>Expected options are:
     *
     * <code>
     * array(
     *   'scenario' => array(
     *     'http://www.google.com/',
     *     'http://www.amazon.com/',
     *   ),
     *   'testsTotal' => 7,
     *   'peakMs' => 5000,
     *   'averageMs' => 2000,
     * )
     * </code>
     *
     * <p>In this example, in total, there will be seven HTTP requests made. Every next
     * HTTP request will get a URL from the 'scenario' array. When all requests
     * are finished their performance will be compared to <code>peakMs</code>
     * and <code>averageMs</code>.
     *
     * @param $options string|array of options
     * @return phpRack_Package_Qos This object
     * @throws phpRack_Exception if invalid option was passed
     * @throws phpRack_Exception if no url was passed
     */
    public function latency($options)
    {
        $options = $this->_prepareLatencyOptions($options);
        /**
         * @see phpRack_Adapters_Url
         */
        require_once PHPRACK_PATH . '/Adapters/Url.php';
        $totalRequestsTime = 0;
        $requestsCompleted = 0;
        reset($options['scenario']);
        while ($requestsCompleted < $options['testsTotal']) {
            $url = current($options['scenario']);
            if ($url === false) {
                reset($options['scenario']);
                continue;
            }
            // make request and measure latency
            $start = microtime(true);
            $urlAdapter = new phpRack_Adapters_Url($url);
            $content = $urlAdapter->getContent();
            $requestTime = microtime(true) - $start;
            $requestTimeInMs = intval($requestTime * 1000);
            $this->_log(
                "HTTP to {$url}: {$requestTimeInMs}ms, "
                . strlen($content) . ' bytes'
            );
            // check single query time meets limit
            if ($requestTimeInMs > $options['peakMs']) {
                $this->_failure(
                    "Peak latency is {$requestTimeInMs}ms, but value below {$options['peakMs']}ms was expected"
                );
                return $this;
            }
            $totalRequestsTime += $requestTime;
            $requestsCompleted++;
            next($options['scenario']);
        }
        // check average queries time meets limit
        $averageMs = intval($totalRequestsTime / $options['testsTotal'] * 1000);
        if ($averageMs < $options['averageMs']) {
            $this->_success("Average latency {$averageMs}ms");
        } else {
            $this->_failure(
                "Average latency is {$averageMs}ms, but {$options['averageMs']}ms was expected"
            );
        }
        return $this;
    }

    /**
     * Prepare latency options (validate them and set defaults options if some option was missed).
     *
     * @param $options string|array of options
     * @return array Options prepared
     * @throws phpRack_Exception if invalid option was passed
     * @throws phpRack_Exception if no url was passed
     */
    protected function _prepareLatencyOptions($options)
    {
        if (is_string($options)) {
            $options = array('scenario' => array($options));
        }
        $defaultOptions = array(
            'scenario'   => array(),
            'averageMs'  => 500,
            'peakMs'     => 1500,
            'testsTotal' => 5
        );
        // validate options
        foreach (array_keys($options) as $key) {
            if (!array_key_exists($key, $defaultOptions)) {
                throw new phpRack_Exception("Invalid option '{$key}'");
            }
        }
        $options = array_merge($defaultOptions, $options);
        if (empty($options['scenario'])) {
            throw new phpRack_Exception('You must specify at least one url to check');
        }
        return $options;
    }
}
