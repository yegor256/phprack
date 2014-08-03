<?php
/**
 * phpRack: Integration Testing Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL: http://www.phprack.com/LICENSE.txt
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
 * @category phpRack
 * @package Adapters
 * @copyright Copyright (c) 2009-2012 phpRack.com
 * @version $Id$
 */

/**
 * Authentication abstract adapter
 *
 * @category phpRack
 * @package Adapters
 * @subpackage Auth
 */
abstract class phpRack_Adapters_Auth_Abstract
{
    /**
     * Authentication request
     *
     * @var array
     */
    protected $_request = array(
        'login'    => '',
        'hash'     => ''
    );

    /**
     * Authentication options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Set authentication options
     *
     * @param array
     * @see phpRack_Runner_Auth::authenticate()
     * @return @this
     */
    public function setOptions($options)
    {
        $this->_options = $options;

        return $this;
    }

    /**
     * Set request login, hash
     *
     * @param array
     * @see phpRack_Runner_Auth::authenticate()
     * @return @this
     */
    public function setRequest($request)
    {
        foreach ($request as $key => $value) {
            $this->_request[$key] = $value;
        }

        return $this;
    }

    /**
     * Authenticate and return an auth result
     *
     * @return phpRack_Runner_Auth_Result
     * @see phpRack_Runner_Auth::authenticate()
     */
    abstract public function authenticate();

    /**
     * Return an AuthResult
     *
     * @param boolean Success/failure of the validation
     * @param string Optional error message
     * @return phpRack_Runner_Auth_Result
     * @see authenticate()
     */
    protected function _validated($result, $message = null)
    {
        /**
         * @see phpRack_Runner_Auth_Result
         */
        require_once PHPRACK_PATH . '/Runner/Auth/Result.php';

        return new phpRack_Runner_Auth_Result($result, $message);
    }

}
