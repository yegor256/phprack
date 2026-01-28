<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * MySQL adapter result formatter
 *
 * @package Adapters
 * @subpackage Db
 */
class phpRack_Adapters_Db_Mysql_Result_Formatter
{
    /**
     * Format SQL query result with spaces for better readability
     *
     * @param resource returned from mysql_query()
     * @return string formatted query result as plain text
     * @see phpRack_Adapters_Db_Mysql::query()
     */
    public static function formatResult($result)
    {
        $response = '';
        // margin between columns in spaces
        $columnsMargin = 2;

        // create array for storing columns meta data
        $columns = array_fill(0, mysql_num_fields($result), array());

        // determine columns lenght and create columns headers
        foreach ($columns as $columnIndex => &$column) {
            // get column data for this index
            $column['meta'] = mysql_fetch_field($result, $columnIndex);

            // set what length should has this columns (get max length from data and column name)
            $column['length'] = max(strlen($column['meta']->name), $column['meta']->max_length);

            // add centered column header
            $response .= str_pad($column['meta']->name, $column['length'], ' ', STR_PAD_BOTH);

            // add margin between columns for better readability
            $response .= str_repeat(' ', $columnsMargin);
        }

        $response .= "\n";

        // foreach row in result
        while (false !== ($row = mysql_fetch_row($result))) {
            // foreach column in result row
            foreach ($row as $columnIndex => $value) {
                $column = &$columns[$columnIndex];

                // choose which padding type we should use
                if ($column['meta']->numeric) {
                    $padType = STR_PAD_LEFT;
                } else {
                    $padType = STR_PAD_RIGHT;
                }
                // pad value with spaces for have equal width in all rows
                $response .= str_pad($value, $column['length'], ' ', $padType);

                // add margin between columns for better readability
                $response .= str_repeat(' ', $columnsMargin);
            }
            $response .= "\n";
        }

        return $response;
    }

    /**
     * Remove header line from query result, which is added by _formatResult()
     * method. Sometimes we just need raw result without this extra line.
     *
     * @param string query result with header line
     * @return string
     * @see formatResult()
     * @see phpRack_Adapters_Db_Mysql::isDatabaseSelected()
     */
    public static function removeColumnHeadersLine($result)
    {
        $pos = strpos($result, "\n");
        // If we have only headers line
        if ($pos === false || strlen($result) == $pos + 1) {
            return '';
        }
        return substr($result, $pos + 1);
    }

}
