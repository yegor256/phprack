<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 *
 * @category phpRack
 * @package Adapters
 */

/**
 * @see phpRack_Adapters_Config
 */
require_once PHPRACK_PATH . '/Adapters/Config.php';

/**
 * @see phpRack_Exception
 */
require_once PHPRACK_PATH . '/Exception.php';

/**
 * Config adapter used for store test configuration loaded from INI file
 *
 * You can use it like this:
 *
 * <code>
 * // app.ini:
 * // [production]
 * // params.db.username = 'test'
 * $ini = new phpRack_Adapters_Config_Ini('app.ini', 'production');
 * assert($ini->params->db->username == 'test');
 * </code>
 *
 * @package Adapters
 * @subpackage Configs
 */
class phpRack_Adapters_Config_Ini extends phpRack_Adapters_Config
{
    /**
     * Create config object and load selected section from INI file,
     * or all sections if $sectionName == null
     *
     * @param string Filename of INI file
     * @param string|null Section name to load, or null to load all
     * @return void
     * @throws phpRack_Exception if INI file not exists
     * @throws phpRack_Exception if section not exists in INI file
     * @see ConfigTest::testConfigIni() and other integration tests
     */
    public function __construct($filename, $sectionName = null)
    {
        $sections = $this->_loadSectionsFromIniFile($filename);
        // one section to return
        if ($sectionName) {
            if (!array_key_exists($sectionName, $sections)) {
                throw new phpRack_Exception("Section '{$sectionName}' doesn't exist in INI file '{$filename}'");
            }
            $dataArray = $this->_sectionToArray($sections[$sectionName]);
        } else {
            // all sections to return
            foreach ($sections as $key => $section) {
                $dataArray[$key] = $this->_sectionToArray($section);
            }
        }
        parent::__construct($dataArray);
    }

    /**
     * Convert section with "key.subkey1.subkey2" values as keys
     * to multidimensional associative array
     *
     * @param array section from ini file
     * @return array
     * @see __construct()
     */
    protected function _sectionToArray($section)
    {
        $dataArray = array();
        foreach ($section as $key => $value) {
            $currentElement =& $dataArray;
            foreach (explode('.', $key) as $keyFragment) {
                $currentElement =& $currentElement[$keyFragment];
            }
            $currentElement = $value;
        }
        return $dataArray;
    }

    /**
     * Load config sections from INI file, taking into account section inheritance
     *
     * @param string INI file to load and parse
     * @return array
     * @throws phpRack_Exception if config INI file not exists
     * @see __construct()
     */
    protected function _loadSectionsFromIniFile($filename)
    {
        if (!file_exists($filename)) {
            throw new phpRack_Exception("INI file '{$filename}' doesn't exist");
        }
        $sections = array();
        $iniFileSections = parse_ini_file($filename, true);

        foreach ($iniFileSections as $sectionName => $data) {
            // divide section name to check it have some parent ([section : parent])
            $nameParts = explode(':', $sectionName);
            $thisSectionName = trim($nameParts[0]);

            // if section have parent
            if (isset($nameParts[1])) {
                $parentSectionName = trim($nameParts[1]);
                // merge current section values, with parent values
                $data = array_merge(
                    $sections[$parentSectionName],
                    $data
                );
            }
            $sections[$thisSectionName] = $data;
        }

        return $sections;
    }
}
