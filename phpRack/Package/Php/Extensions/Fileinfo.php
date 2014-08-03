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
 * @copyright Copyright (c) 2009-2012 phpRack.com
 * @version $Id: Extensions.php 25 2010-02-20 09:30:13Z yegor256@yahoo.com $
 * @category phpRack
 * @package Tests
 * @subpackage packages
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * fileinfo to validate.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Php_Extensions_Fileinfo extends phpRack_Package
{

    /**
     * Extension is properly configured?
     *
     * @return $this
     */
    public function isAlive()
    {
        if (!extension_loaded('fileinfo')) {
            $this->_failure("Extension 'fileinfo' is NOT loaded, we can't validate it any further");

            return $this;
        }

        $magic = '/usr/share/misc/magic';
        $finfo = new finfo(FILEINFO_MIME, $magic);
        if (!$finfo) {
            $this->_failure("finfo() failed to load magic: '{$magic}'");

            return $this;
        }

        $type = @$finfo->file(__FILE__);
        if (strpos($type, 'text/') !== 0) {
            $this->_failure("finfo() failed to detect PHP file type, returned: '{$type}'");

            return $this;
        }

        $this->_success("Extension 'fileinfo' is configured properly");

        return $this;
    }

}
