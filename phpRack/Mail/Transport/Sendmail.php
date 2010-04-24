<?php
require_once PHPRACK_PATH . '/Mail/Transport/Abstract.php';

/**
 * Sendmail
 *
 * @see phpRack_Mail_Transport_Abstract
 */
class phpRack_Mail_Transport_Sendmail extends phpRack_Mail_Transport_Abstract
{
    /**
     * Sending mail
     *
     * @return bool
     */
    public function send()
    {
        return mail(
            $this->_to[0],
            $this->_getEncodedSubject(),
            $this->_getEncodedBody(),
            $this->_getHeaders()
        );
    }

    /**
     * Builds headers for the mail function
     *
     * @return string Plain list of headers
     */
    private function _getHeaders()
    {
        $headers = '';

        $count = count($this->_to);
        if ($count > 1) {
            for ($i=1;$i<$count;$i++) {
                $headers .= 'Cc: ' . $this->_to[$i] . "\r\n";
            }
        }

        $headers .= 'From: ' . $this->_from . "\r\n";
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";
        $headers .= 'Content-transfer-encoding: base64' . "\r\n";
        return $headers;
    }
}
