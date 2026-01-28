<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2009-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Tests
 * @subpackage core
 */

/**
 * Runner logger
 *
 * @see phpRack_Runner#run()
 * @package Tests
 * @subpackage core
 */
class phpRack_Runner_Logger
{
    /**
     * Cuts log according to the limit provided
     *
     * @param string Log to cut
     * @param integer Limit in Kb
     * @see run()
     * @return string
     */
    public static function cutLog($log, $limit)
    {
        $len = 0;
        if (function_exists('mb_strlen')) {
            $len += mb_strlen($log, 'UTF-8');
        } elseif (function_exists('iconv_strlen')) {
            $len += iconv_strlen($log, 'UTF-8');
        } else {
            // bad variant
            $len += strlen($log) / 2;
        }

        $max = $limit * 1024; // in kb
        if ($len > $max) {
            $cutSize = $max / 2;
            $func = '';
            if (function_exists('iconv_substr')) {
                $func = 'iconv_substr';
            } elseif (function_exists('mb_substr')) {
                $func = 'mb_substr';
            }
            if ($func) {
                $head = call_user_func($func, $log, 0, $cutSize, 'UTF-8');
                $tail = call_user_func(
                    $func, $log, -1 * $cutSize, $cutSize, 'UTF-8'
                );
            } else {
                // bad variant
                $head = substr($log, 0, $cutSize / 2);
                $tail = substr($log, -1 * $cutSize / 2);
            }
            return $head . "\n\n... content skipped (" . ($len - $max) . " bytes) ...\n\n" . $tail;
        }
        return $log;
    }

    /**
     * Checks for string encoding, and if encoding is not utf-8, encodes to utf-8
     *
     * @param string String to convert into UTF-8
     * @return string Proper UTF-8 formatted string
     * @see run()
     * @see #60 I think that this method shall be extensively tested. Now I have problems
     *      with content that is not in English.
     */
    public static function utf8Encode($str)
    {
        return utf8_encode($str);
        // $isUtf = false;
        // if (function_exists('mb_check_encoding')) {
        //     $isUtf = mb_check_encoding($str, 'UTF-8');
        // }
        // if (function_exists('iconv')) {
        //     $isUtf = (@iconv('UTF-8', 'UTF-16', $str) !== false);
        // }
        // return (!$isUtf) ? utf8_encode($str) : $str;
    }

}
