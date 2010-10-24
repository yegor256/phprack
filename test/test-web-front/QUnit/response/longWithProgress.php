<?php
for ($i = 1; $i <= 10; $i++) {
    echo ' ';

    if (ob_get_level()) {
        ob_flush();
    }

    flush();
    sleep(1);
}
?>

{"success":true,"log":"[OK] Test passed, 10.00sec"}
