<?php
/**
 * AAAAA
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * @see phpRack_Adapters_Notifier_Mail_Abstract
 */
require_once PHPRACK_PATH . '/Adapters/Notifier/Mail/Abstract.php';

/**
 * Sendmail implementation of phpRack mail
 *
 * @see phpRack_Notifier_Mail_Abstract
 * @package Adapters
 * @subpackage Notifiers
 */
class phpRack_Adapters_Notifier_Mail_Sendmail extends phpRack_Adapters_Notifier_Mail_Abstract
{
    /**
     * Preparing and sending mail.
     *
     * Function returns result of the operation
     *
     * @return bool
     * @link http://php.net/manual/en/function.mail.php
     */
    public function send()
    {
        $this->_validateBeforeSend();
        return mail(
            $this->_to[0],
            $this->_getEncodedSubject(),
            $this->_getEncodedBody(),
            $this->_getHeaders()
        );
    }

    /**
     * Function builds headers for mail
     *
     * @return string Plain list with headers
     * @see send()
     */
    private function _getHeaders()
    {
        $headers = array();
        if (count($this->_to) > 1) {
            $headers['Cc'] = implode(', ', array_slice($this->_to, 1));
        }
        $headers['From'] = $this->_from;
        $headers['MIME-Version'] = '1.0';
        $headers['Content-Type'] = 'text/plain; charset=UTF-8';
        $headers['Content-transfer-encoding'] = 'base64';

        return implode(
            "\r\n",
            array_map(
                fn($v, $k) => $k . ": " . $v,
                $headers,
                array_keys($headers)
            )
        );
    }
}
