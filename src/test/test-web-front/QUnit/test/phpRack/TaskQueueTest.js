/**
 * AAAAA
 *
 * @author netcoderpl@gmail.com
 * @category phpRack
 */

$(
    function()
    {
        module("phpRack_TaskQueue");
        test(
            "test we can add task",
            function() {
                var call = {
                    fileName: 'testfilename',
                    divId: 'testid'
                };
                var data = {};
                data[phpParams.ajaxTag] = call.fileName;
                data[phpParams.ajaxToken] = call.divId;
                var test = new phpRack_Test(
                    {
                        id: 1,
                        url: phpParams.requestUri,
                        data: data,
                        autoStart: false,
                        pauseWhenFocusLost: false
                    }
                );
                taskQueue = new phpRack_TaskQueue();
                taskQueue.setThreadsCount(2);
                taskQueue.add(test);
            }
        );
    }
);
