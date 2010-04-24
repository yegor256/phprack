<?php
/**
 * Abstract class for phpRack_Mail_Transport
 *
 * @todo #32 add correct phpRack_Mail_Transport_Abstract class
 *      move this class to phpRack_Mail_Abstract or something like this
 **/
abstract class phpRack_Mail_Transport_Abstract
{
    /**
     * Our options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Text of a body
     *
     * @var string
     */
    protected $_body;

    /**
     * Destination E-mail address
     *
     * @var string
     */
    protected $_to;

    /**
     * Sender E-mail address
     *
     * @var string
     */
    protected $_from = 'no-reply@phprack.com';

    /**
     * Default message subject
     *
     * @var string
     */
    protected $_subject = 'phpRack';

    /**
     * Constructor
     *
     * @param array List of parameters
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }

    /**
     * Assigning string to the body
     *
     * @param string Text to be assigned
     */
    public function setBody($plain)
    {
        $this->_body = trim($plain);
    }

    /**
     * Assigning string to the subject
     *
     * @param string Text to be assigned
     */
    public function setSubject($text)
    {
        $this->_subject = $text;
    }

    /**
     * Adding destination mails.
     *
     * @param string|array $mails
     */
    public function setTo($mails)
    {
        if (!is_array($mails)) {
            $this->_to = array($mails);
        }
        $this->_to = $mails;
    }

    /**
     * Encoding subject to UTF-8 format with base64
     *
     * @see setSubject()
     * @return string
     */
    protected function _getEncodedSubject()
    {
        return '=?UTF-8?B?' . base64_encode($this->_subject) . '?=';
    }

    /**
     * Encoding body to UTF-8 format with base64. Text will have fixed width
     *
     * @see setBody()
     * @return string
     */
    protected function _getEncodedBody()
    {
        return rtrim(chunk_split(base64_encode($this->_body), 72, "\n"));
    }
}
