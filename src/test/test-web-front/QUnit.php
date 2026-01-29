<?php
/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 */

define('INSTRUMENTED', dirname(__FILE__) . '/../../build/instrumented-js');
if (!is_dir(INSTRUMENTED)) {
    exit("'" . INSTRUMENTED . "' directory does NOT exist, execute 'phing' first");
}

define(
    'IS_AJAX_REQUEST',
    isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
);

if (IS_AJAX_REQUEST) {
    include 'phprack.php';
    exit;
}

ob_start();
?>

<link rel="stylesheet" href="QUnit/qunit.css" type="text/css" />
<script type="text/javascript" src="QUnit/qunit.js"></script>

<?php
$jsTestsPath = "QUnit/test/";
/**
 * @see phpRack_Adapters_Files_DirectoryFilterIterator
 */
require_once '../../phpRack/Adapters/Files/DirectoryFilterIterator.php';
$iterator = phpRack_Adapters_Files_DirectoryFilterIterator::factory($jsTestsPath);
$iterator->setExclude('/.svn/')
    ->setExtensions('js');
?>
<?php foreach ($iterator as $file): ?>
    <script type="text/javascript"
        src="<?php echo str_replace('\\', '/', $file->getPathname()); ?>">
    </script>
<?php
endforeach; ?>

<h1 id="qunit-header">phpRack Test Suite</h1>
<h2 id="qunit-banner"></h2>
<div id="qunit-testrunner-toolbar"></div>
<h2 id="qunit-userAgent"></h2>
<ol id="qunit-tests"></ol>
<button style="margin: 5px 0px;" onclick='window.open("./JsCoverage/jscoverage.html");'>
    Coverage report
</button>

<?php

$qUnitContent = ob_get_clean();

ob_start();
include 'phprack.php';
$content = ob_get_clean();

// if we see task list, we must be authorized
if (strpos($content, '<div id="task-list">') !== false) {
    // attach required by qunit html, css, and script just after jQuery script
    $pattern = '</script>';
    $pos = strpos($content, $pattern);
    if ($pos === null) {
        throw Exception("Can not find {$pattern} tag");
    }
    $content = substr_replace($content, $qUnitContent, $pos + strlen($pattern), 0);
}

echo $content;
