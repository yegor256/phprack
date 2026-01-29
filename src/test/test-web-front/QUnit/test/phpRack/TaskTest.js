/**
 * SPDX-FileCopyrightText: Copyright (c) 2009-2026 Yegor Bugayenko
 * SPDX-License-Identifier: MIT
 *
 * @author netcoderpl@gmail.com
 * @category phpRack
 */

$(
    function()
    {
        module("phpRack_Test");
        test(
            "test we can monitor task progress",
            function() {
                var $task = $('span.label:contains(\'LongTest.php\')').parent();
                $task.find('span.result').click();
                // wait until timer become visible
                setTimeout(
                    function()
                    {
                        // resume test suite
                        start();
                        var resultText = $task.find('span.result').text();
                        ok(resultText.match(/bytes/), 'Check we can see received bytes count');
                    },
                    8000
                );
                stop();
            }
        );

        test(
            "test groups tasks in same dir",
            function() {
                var $label = $('div.taskGroupControl span.label:contains(\'Php\')');
                ok($label.length, 'Attaches control label');

                var $taskGroupControl = $label.parent('div.taskGroupControl');
                ok($taskGroupControl.length, 'Attaches group control div');

                var $taskGroupContainer = $taskGroupControl.next('div.taskGroupContainer');
                ok($taskGroupContainer.length, 'Attaches group container div');

                ok(!$taskGroupContainer.is(':visible'), 'Hides task group container by default');
                $label.click();
                ok($taskGroupContainer.is(':visible'), 'Shows task group container after click on label');
                $label.click();
                // we must wait for animated collapsing
                setTimeout(
                    function()
                    {
                        // resume test suite
                        start();
                        ok(!$taskGroupContainer.is(':visible'), 'Hides task group container after next click on label');
                    },
                    1000
                );
                stop();
            }
        );
    }
);
