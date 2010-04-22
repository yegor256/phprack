<?php
/**
 * @todo: add correct phpRack_Mail_Transport_Abstract class
 *        move this class to phpRack_Mail_Abstract or something like this
 **/
abstract class phpRack_Mail_Transport_Abstract
{
    private $_options = array();

    protected $_body;

    protected $_to;

    protected $_from = 'no-reply@phprack.com';

    protected $_subject = 'phpRack';

    public function __construct(array $options)
    {
        $this->_options = $options;
    }

    public function setBody($plain)
    {
        $this->_body = trim($plain);
    }

    public function setSubject($text)
    {
        $this->_subject = $text;
    }

    public function setTo($mails)
    {
        if (!is_array($mails)) {
            $this->_to = array($mails);
        }
        $this->_to = $mails;
    }

    protected function _getEncodedSubject()
    {
        return '=?UTF-8?B?' . base64_encode($this->_subject) . '?=';
    }

    protected function _getEncodedBody()
    {
        return rtrim(chunk_split(base64_encode($this->_body), 72, "\n"));
    }
}