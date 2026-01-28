/**
 * SPDX-FileCopyrightText: Copyright (c) Yegor Bugayenko, 2010-2026
 * SPDX-License-Identifier: MIT
 *
 * @author netcoderpl@gmail.com
 * @category phpRack
 */

$(
    function()
    {
        module("Core");
        test(
            "stripTags function",
            function()
            {
                equals(
                    "Special<b>Test<b>With<br/>Html".stripTags(),
                    "SpecialTestWithHtml",
                    "Check html tags are removed"
                );
            }
        );

        test(
            "htmlspecialchars function",
            function()
            {
                equals(
                    "Special<b>test</b>".htmlspecialchars(),
                    "Special&lt;b&gt;test&lt;/b&gt;",
                    "Check html tags are replaced by entities"
                );
            }
        );
    }
);
