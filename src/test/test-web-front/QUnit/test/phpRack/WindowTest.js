/**
 * AAAAA
 *
 * @author netcoderpl@gmail.com
 * @category phpRack
 */

$(
    function()
    {
        module("phpRack_Window");
        test(
            "test window focus can be recognized",
            function()
            {
               $(window).blur();
               ok(!phpRack_Window.hasFocus(), 'Check window blur state can be recognized');
               $(window).focus();
               ok(phpRack_Window.hasFocus(), 'Check window focus state can be recognized');
            }
        );
    }
);
