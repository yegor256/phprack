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
 * @version $Id: Runner.php 411 2010-04-16 06:19:32Z yegor256@yahoo.com $
 * @category phpRack
 */

/**
 * Simple Mailer
 *
 * Some description
 *
 * @package Tests
 * @todo formating of this class and unit tests
 */
class phpRack_Mail
{
    private $_options = array();
    private $_body;
    private $_to;
    private $_response;
    private $_subject = 'phpRack привет';
    private $_connection;

    public function __construct(array $options)
    {
    	$this->_options = $options;
    }

    private function _connect()
    {
        if (empty($this->_options['smtp']['host'])) {
            throw new Exception('You must provide correct mail host');
        }

        $port = 25;
        if (!empty($this->_options['smtp']['port'])) {
            $port = (int)$this->_options['smtp']['port'];
        }

        $protocol = 'tcp';
        if (!empty($this->_options['smtp']['tls'])) {
            $protocol = 'tls';
        }
        $addr = $protocol.'://'.$this->_options['smtp']['host'].':'.$port;
        $this->_connection = stream_socket_client($addr, $a, $b, 10);
        
        stream_set_timeout($this->_connection, 50);

        return is_resource($this->_connection);
    }

    protected function _serverSend($msg)
    {
        if (!fwrite($this->_connection, $msg . "\r\n")) {
            throw new Exception('Can\'t write to a socket');
        }
        return $this;
    }

    protected function _mustBe($code, $timeout = 300)
    {
        if (!is_array($code)) {
            $code = array($code);
        }
        $msg = $cmd = '';
        $error = true;
        stream_set_timeout($this->_connection, $timeout);
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

    public function setBody($plain)
    {
        $this->_body = trim($plain);
    }

    public function setSubject($text)
    {
        $this->_subject = $text;
    }

    protected function _encodeSubject()
    {
        return '=?UTF-8?B?' . base64_encode($this->_subject) . '?=';
    }

    protected function _encodeBody()
    {
        return rtrim(chunk_split(base64_encode($this->_body), 72, "\n"));
    }

    public function setTo($mails)
    {
        if (!is_array($mails)) {
            $this->_to = array($mails);
        }
        $this->_to = $mails;
    }

    public function send()
    {
        if (!$this->_connect()) {
            throw new Exception('Can\'t connect to the mail server');
        }

        $this->_serverSend('EHLO ' . php_uname('n'))
            ->_mustBe(220)
            ->_mustBe(250);
        /**
         * @todo: check for STARTTLS on 25 port or if req.
         * $this->_serverSend('STARTTLS');
         * stream_socket_enable_crypto($this->_connection, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
         * $this->_serverSend('EHLO ' . php_uname('n'));
         */

        // Auth
        $this->_serverSend('AUTH LOGIN')
            ->_mustBe(334);
        $this->_serverSend(base64_encode($this->_options['smtp']['username']))
            ->_mustBe(334);
        $this->_serverSend(base64_encode($this->_options['smtp']['password']))
            ->_mustBe(235);

        // Basic set
        $this->_serverSend('MAIL FROM:<kkamkou@gmail.com>')->_mustBe(250);
        $this->_serverSend('RCPT TO:<' . $this->_to[0] . '>')->_mustBe(250);
        $this->_serverSend('DATA')->_mustBe(354);

        // Mail body
        $this->_buildBody();

        // Closing data part and sending
        $this->_serverSend('.')->_mustBe(250, 600);
        $this->_serverSend('QUIT')->_mustBe(221, 600);
        
        $this->_disconnect();

        return true;
    }

    protected function _buildBody()
    {
        $this->_serverSend('From: <kkamkou@gmail.com');
        $this->_serverSend('To: <' . $this->_to[0] . '>');
        unset($this->_to[0]);
        if (count($this->_to)) {
            /**
             * @todo: i think this must be Bcc, not Cc
             */
            $this->_serverSend('Cc: <' . implode('>,<', $this->_to) . '>');
        }
        
        $this->_serverSend('Subject: ' . $this->_encodeSubject());
        $this->_serverSend('MIME-Version: 1.0');
        $this->_serverSend('Content-Type: text/plain; charset=UTF-8');
        $this->_serverSend('Content-transfer-encoding: base64');
        $this->_serverSend("\r\n");
        $this->_serverSend($this->_encodeBody());
    }

    protected function _disconnect()
    {
        if (is_resource($this->_connection)) {
            fclose($this->_connection);
        }
    }
}
