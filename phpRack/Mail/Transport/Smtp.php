<?php
require_once PHPRACK_PATH . '/Mail/Transport/Abstract.php';

class phpRack_Mail_Transport_Smtp extends phpRack_Mail_Transport_Abstract
{
    /**
     * Response from server to debug
     * @var array
     */
    protected $_response;

    /**
     * Connection status
     * @var bool
     */
    protected $_connected = false;

    /**
     * Our connection entry poing
     * @var resource
     */
    protected $_connection;

    /**
     * Constructor for smtp protocol.
     * Creation of address to connect to.
     * @param array $options
     * @return void
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        $host = '127.0.0.1';
        if (!empty($this->_options['smtp']['host'])) {
            $host = $this->_options['smtp']['host'];
        }

        $port = 25;
        if (!empty($this->_options['smtp']['port'])) {
            $port = (int)$this->_options['smtp']['port'];
        }

        $protocol = 'tcp';
        if (!empty($this->_options['smtp']['tls'])) {
            $protocol = 'tls';
        }

        $this->_connection = stream_socket_client($protocol . '://' . $host . ':' . $port);
        $this->_connected = is_resource($this->_connection);
    }

    /**
     *
     * @todo #32 add check for STARTTLS
     * @return void
     */
    public function send()
    {
        if (!$this->_connected) {
            throw new Exception('Can\'t connect to the mail server');
        }

        // Hello server
        $this->_query('EHLO ' . php_uname('n'))
            ->_mustBe(220)
            ->_mustBe(250);

        // Auth info
        $this->_query('AUTH LOGIN')
            ->_mustBe(334);
        $this->_query(base64_encode($this->_options['smtp']['username']))
            ->_mustBe(334);
        $this->_query(base64_encode($this->_options['smtp']['password']))
            ->_mustBe(235);

        // Basic set
        $this->_query('MAIL FROM:<' . $this->_from . '>')
            ->_mustBe(250);
        $this->_query('RCPT TO:<' . $this->_to[0] . '>')
            ->_mustBe(250);
        $this->_query('DATA')
            ->_mustBe(354);

        // Mail headers
        $this->_sendHeaders();

        // Closing data part and sending
        $this->_query('.')
            ->_mustBe(250, 600);
        $this->_query('QUIT')
            ->_mustBe(221, 600);

        return true;
    }

    /**
     * @return void
     */
    protected function _sendHeaders()
    {
        $this->_query('From: <' . $this->_from . '>');
        $this->_query('To: <' . $this->_to[0] . '>');
        unset($this->_to[0]);
        if (count($this->_to)) {
            /**
             * @todo #32 i think this must be Bcc, not Cc
             */
            $this->_query('Cc: <' . implode('>,<', $this->_to) . '>');
        }

        $this->_query('Subject: ' . $this->_getEncodedSubject());
        $this->_query('MIME-Version: 1.0');
        $this->_query('Content-Type: text/plain; charset=UTF-8');
        $this->_query('Content-transfer-encoding: base64');
        $this->_query("\r\n");
        $this->_query($this->_getEncodedBody());
    }

    /**
     * @todo #32 move this method to phpRack_Mail_Transport_Abstract
     * @var string $msg
     */
    protected function _query($msg)
    {
        if (!fwrite($this->_connection, $msg . "\r\n")) {
            throw new Exception('Can\'t write to a socket');
        }
        return $this;
    }

    /**
     * @todo #32 move this method to phpRack_Mail_Transport_Abstract
     * @var int|array $code
     * @var int $timeout (Default: 300)
     */
    protected function _mustBe($code, $timeout = 300)
    {
        if (!is_array($code)) {
            $code = array($code);
        }
        $msg = $cmd = '';
        $error = true;
        if (!stream_set_timeout($this->_connection, $timeout)) {
            throw new Exception('Can\'t change stream timeout');
        }
        do {
            $this->_response[] = $data = fgets($this->_connection, 1024);
            sscanf($data, '%d%s', $cmd, $msg);
            if (in_array($cmd, $code)) {
                $error = false;
            }
        } while (strpos($msg, '-') === 0);

        if ($error) {
            throw new Exception('Wrong answer from the server');
        }
        return $this;
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->_connected) {
            fclose($this->_connection);
        }
    }
}
