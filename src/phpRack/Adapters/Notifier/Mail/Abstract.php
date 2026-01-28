<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * Abstract class for the phpRack_Adapters_Notifier_Mail_*
 *
 * @package Adapters
 * @subpackage Notifiers
 */
abstract class phpRack_Adapters_Notifier_Mail_Abstract
{
    /**
     * Our array with list of options
     *
     * @var array
     * @see __construct()
     */
    protected $_options = array();

    /**
     * Default text of the body
     *
     * @var string
     * @see setBody()
     */
    protected $_body;

    /**
     * List of destination e-mail addresses
     *
     * @var string
     * @see setTo()
     */
    protected $_to = array();

    /**
     * Sender e-mail address
     *
     * @var string
     */
    protected $_from = 'no-reply@phprack.com';

    /**
     * Default message subject
     *
     * @var string
     * @see setSubject()
     */
    protected $_subject = 'phpRack';

    /**
     * Constructor
     *
     * @param array List of parameters
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->_options = $options;
    }

    /**
     * Assigns body of a mail
     *
     * @param string Text to be assigned
     * @return phpRack_Adapters_Mail
     */
    public function setBody($plain)
    {
        $this->_body = trim($plain);
        return $this;
    }

    /**
     * Assigns subject of a mail
     *
     * @param string
     * @return phpRack_Adapters_Mail
     */
    public function setSubject($text)
    {
        $this->_subject = $text;
        return $this;
    }

    /**
     * Sets destination mail or mails.
     *
     * @param string|array $mails
     * @return phpRack_Adapters_Mail
     */
    public function setTo($mails)
    {
        $this->_to = (!is_array($mails)) ? array($mails) : $mails;
        return $this;
    }

    /**
     * Checks if we are ready to build mail
     *
     * @return bool
     * @see phpRack_Adapters_Notifier_Mail_Sendmail->send()
     * @see phpRack_Adapters_Notifier_Mail_Smtp->send()
     * @throws phpRack_Exception if To not defined
     * @throws phpRack_Exception if Body not defined
     */
    protected function _validateBeforeSend()
    {
        if (!count($this->_to)) {
            throw new phpRack_Exception('Recipients are not specified');
        }
        if (empty($this->_body)) {
            throw new phpRack_Exception('Body is not specified');
        }
        return true;
    }

    /**
     * Encodes subject to UTF-8
     *
     * @see setSubject()
     * @return string base64 encoded string with special chars
     */
    protected function _getEncodedSubject()
    {
        return '=?UTF-8?B?' . base64_encode($this->_subject) . '?=';
    }

    /**
     * Encodes body to UTF-8.
     * Output text has fixed width
     *
     * @see setBody()
     * @return string base64 encoded string
     */
    protected function _getEncodedBody()
    {
        return rtrim(chunk_split(base64_encode($this->_body), 72, "\n"));
    }
}
