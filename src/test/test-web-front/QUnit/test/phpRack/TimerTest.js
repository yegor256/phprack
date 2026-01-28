/**
 * AAAAA
 *
 * @author netcoderpl@gmail.com
 * @category phpRack
 */

$(
    function()
    {
        module("phpRack_Timer");
        test(
            "test timer works",
            function()
            {
                var options = {
                    onTick: function(timer) {
                        // resume test suite
                        start();
                        equals(timer.getElapsedSeconds(), 1, 'Check whether timer value is incremented');
                        equals(timer.getFormattedTime(), '0:01', 'Check time is correctly formatted');
                        timer.stop();
                    }
                }
                var timer = new phpRack_Timer(options);
                timer.start();
                equals(timer.getElapsedSeconds(), 0, 'Check whether timer start from 0');
                equals(timer.getFormattedTime(), '0:00', 'Check time is correctly formatted');
                // wait for timer tick, halt test suite
                stop();
            }
        );
    }
);
