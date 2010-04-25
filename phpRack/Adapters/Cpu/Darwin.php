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
 * @version $Id: Linux.php 447 2010-04-24 03:59:09Z yegor256@yahoo.com $
 * @category phpRack
 */

/**
 * @see phpRack_Adapters_Cpu_Abstract
 */
require_once PHPRACK_PATH . '/Adapters/Cpu/Abstract.php';

/**
 * Darwin CPU Adapter (Mac OS)
 *
 * @package Adapters
 * @subpackage Cpu
 * @todo #17 Should be implemented
 */
class phpRack_Adapters_Cpu_Darwin extends phpRack_Adapters_Cpu_Abstract
{
    /**
     * Get CPU BogoMips
     *
     * @return float
     * @throws Exception If unable to get BogoMips
     * @see phpRack_Package_Cpu_Performance::atLeast()
     * @see phpRack_Adapters_Cpu_Abstract::getBogoMips()
     * @todo #17 Should be implemented
     */
    public function getBogoMips()
    {
        throw new Exception('Darwin::getBogoMips() not implemented yet');
    }

    /**
     * Get CPU frequency in MHz
     *
     * @return float
     * @throws Exception If can't get cpu frequency
     * @see getBogoMips()
     * @see phpRack_Adapters_Cpu_Abstract::getCpuFrequency()
     * @todo #17 Should be implemented
     */
    public function getCpuFrequency()
    {
        throw new Exception('Darwin::getCpuFrequency() not implemented yet');
    }
}
