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
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * CPU Performance check.
 *
 * @package Tests
 * @subpackage packages
 */
class phpRack_Package_Cpu_Performance extends phpRack_Package
{
    /**
     * Check whether server CPU has least this BogoMips
     *
     * @param float required BogoMips
     * @return $this
     * @see PerformanceTest#testServerIsFast()
     */
    public function atLeast($bogoMips)
    {
        /**
         * @see phpRack_Adapters_Cpu
         */
        require_once PHPRACK_PATH . '/Adapters/Cpu.php';

        try {
            $cpu = phpRack_Adapters_Cpu::factory();
            $currentBogoMips = $cpu->getBogoMips();
            if ($currentBogoMips >= $bogoMips) {
                $this->_success("CPU is fast enough with '{$currentBogoMips}' BogoMips");
            } else {
                $this->_failure(
                    "CPU is too slow. " .
                    "It has only '{$currentBogoMips}' BogoMips, but '{$bogoMips}' is required"
                );
            }
        } catch (phpRack_Exception $e) {
            $this->_failure("CPU problem: {$e->getMessage()}");
        }

        return $this;
    }
}
