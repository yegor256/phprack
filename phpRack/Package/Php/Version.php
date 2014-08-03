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
 * @version $Id$
 * @category phpRack
 * @package Tests
 * @subpackage packages
 */

/**
 * @see phpRack_Package
 */
require_once PHPRACK_PATH . '/Package.php';

/**
 * PHP-version related assertions.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Php_Version extends phpRack_Package
{

    /**
     * Current version is newer than given one?
     *
     * @param string Version name
     * @return $this
     * @see http://www.php.net/manual/en/function.version-compare.php
     */
    public function atLeast($version)
    {
        if (version_compare(phpversion(), $version) >= 0) {
            $this->_success('PHP version is ' . phpversion() . ", newer or equal to {$version}");
        } else {
            $this->_failure('PHP version is ' . phpversion() . ", older than {$version}");
        }

        return $this;
    }

}
