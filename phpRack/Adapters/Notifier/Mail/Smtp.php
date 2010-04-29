<?php
/**
 * phpRack: Integration Testing Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL: http://www.phprack.com/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@phprack.com so we can send you a copy immediately.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright (c) phpRack.com
 * @version $Id$
 * @category phpRack
 */

/**
 * @see phpRack_Adapters_Notifier_Mail_Abstract
 */
require_once PHPRACK_PATH . '/Adapters/Notifier/Mail/Abstract.php';

/**
 * Smtp implementation of phpRack mail
 *
 * @see phpRack_Notifier_Mail_Abstract
 */
class phpRack_Adapters_Notifier_Mail_Smtp extends phpRack_Adapters_Notifier_Mail_Abstract
{
    /**
     * Response list from server to debug
     *
     * @var array
     * @see _mustBe()
     * @todo #32 Unused variable, for future use?
     */
    protected $_response;

    /**
     * Connection status
     *
     * @var bool
     * @see _connect()
     * @see __destruct()
     */
    protected $_connected = false;

    /**
     * Connection entry point
     *
     * @var resource
     * @see _connect()
     * @see _query()
     */
    protected $_connection;

    /**
     * Connection address for the stream
     *
     * @var string
     * @see _connect();
     */
    protected $_address;

    /**
     * Constructor for the smtp protocol.
     * Creates address to connect to
     *
     * @param array List of parameters
     * @return void
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        $host = '127.0.0.1';
        if (!empty($this->_options['host'])) {
            $host = $this->_options['host'];
        }

        $port = 25;
        if (!empty($this->_options['port'])) {
            $port = (int)$this->_options['port'];
        }

        $protocol = 'tcp';
        if (!empty($this->_options['tls'])) {
            $protocol = 'tls';
        }

        $this->_address = $protocol . '://' . $host . ':' . $port;
    }

    /**
     * Prepares and sending mail.
     *
     * @todo #32 add check for STARTTLS
     * @throws Exception if connection doesn't established
     */
    public function send()
    {
        $this->_validateBeforeSend();

        if (!$this->_connect()) {
            throw new Exception("Can't connect to the mail server");
        }

        // Hello server
        $this->_query('EHLO ' . php_uname('n'))
            ->_mustBe(220)
            ->_mustBe(250);

        // Auth info
        $this->_query('AUTH LOGIN')
            ->_mustBe(334);
        $this->_query(base64_encode($this->_options['username']))
            ->_mustBe(334);
        $this->_query(base64_encode($this->_options['password']))
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
     * Connects to the stream and returns connection status
     *
     * @return bool
     */
    protected function _connect()
    {
        $this->_connection = stream_socket_client($this->_address);
        return ($this->_connected = is_resource($this->_connection));
    }

    /**
     * Sends server queries to complete mail
     *
     * @see _query()
     * @todo #32 i think CC must be Bcc in this part
     * @return void
     */
    protected function _sendHeaders()
    {
        $this->_query('From: <' . $this->_from . '>');
        $this->_query('To: <' . $this->_to[0] . '>');
        unset($this->_to[0]);
        if (count($this->_to)) {
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
     * Writes data to the connetion (stream)
     *
     * @todo #32 move this method to phpRack_Adapters_Notifier_Mail_Abstract
     * @var string $msg
     * @return phpRack_Adapters_Notifier_Mail_Smtp
     * @throws Exception if can't write to the stream
     */
    protected function _query($msg)
    {
        if (!fwrite($this->_connection, $msg . "\r\n")) {
            throw new Exception("Can't write to a socket");
        }
        return $this;
    }

    /**
     * Reads stream. Moves caret and checks for a code or codes.
     * Second parameter used as time limit for read stream
     *
     * @todo #32 move this method to phpRack_Adapters_Notifier_Mail_Abstract
     * @var int|array $code
     * @var int $timeout (Default: 300)
     * @throws Exception if can't change stream timeout
     * @throws Exception if wrong answer from the server
     * @return phpRack_Adapters_Notifier_Mail_Smtp
     */
    protected function _mustBe($code, $timeout = 300)
    {
        if (!is_array($code)) {
            $code = array($code);
        }
        
        if (!stream_set_timeout($this->_connection, $timeout)) {
            throw new Exception("Can't change stream timeout");
        }

        $error = true;
        $msg = $cmd = '';
        do {
            $this->_response[] = $data = fgets($this->_connection, 1024);
            sscanf($data, '%d%s', $cmd, $msg);
            if (in_array($cmd, $code)) {
                $error = false;
            }
        } while (strpos($msg, '-') === 0);

        if ($error) {
            throw new Exception('Wrong answer from the server: ' . $data);
        }
        return $this;
    }

    /**
     * Destructor.
     * Closes connection if needed
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->_connected) {
            fclose($this->_connection);
        }
    }
}
