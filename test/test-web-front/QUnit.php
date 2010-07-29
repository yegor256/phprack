<html>
<head>
<link rel="stylesheet" href="QUnit/qunit.css" type="text/css" />
<script type="text/javascript" src="../phpRack/layout/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
    var phpParams = {
        calls: [],
        ok: 'OK',
        failure: 'FAILURE',
        requestUri: 'QUnit/response/simple.js',
        ajaxTag: 'test',
        ajaxToken: 'token',
    };
</script>
<script type="text/javascript" src="../phpRack/layout/index.js"></script>
<script type="text/javascript" src="QUnit/qunit.js"></script>
<?php

$jsTestsPath = "QUnit/test/";
$iterator = new RecursiveDirectoryIterator($jsTestsPath);
foreach (new RecursiveIteratorIterator($iterator) as $file) :

?>

<script type="text/javascript" src="<?php echo str_replace('\\', '/', $file->getPathname()); ?>"></script>

<?php endforeach; ?>
</head>
<body>
<ol id="qunit-tests"></ol>

</body>
</html>