/**
 * @version $Id$
 */
$(
    function()
    {
        // List with DOM ids and test names
        var calls = {
            <?php $jsCallsCount = count($jsCalls) ?>
            <?php foreach ($jsCalls as $i => $testName): ?>
                'div#d<?php echo $i ?>': '<?php echo $testName?>'<?php if ($i < $jsCallsCount) echo ',' ?>
            <?php endforeach; ?>
        };

        // Our processing queue to control tests concurrency
        function phpRack_TaskQueue()
        {
            var that = {
                queue: [], // Used for storing waiting tasks
                threadsCount: 1, // How many concurent threads we can have
                activeThreadsCount: 0, // How many threads is currently running
                // Callback function executed after each task is finished
                onTaskFinish: function()
                {
                    that.activeThreadsCount--;
                    that._runNextTask();
                },
                // Add task to our processing queue
                add: function(task)
                {
                    that.queue.push(task);
                    that._runNextTask();
                },
                // Control tasks proccessing
                _runNextTask: function()
                {
                    // If we have free thread
                    if (that.activeThreadsCount < that.threadsCount) {
                        that.activeThreadsCount++;

                        // Get next task from queue and run it
                        task = that.queue.shift();
                        if (task) {
                            // Set callback function on task, to know that we can execute next one
                            task.setOption('onFinish', that.onTaskFinish);
                            task.run();
                        }
                    }
                },
                // Set how many threads should be used for processing our task queue
                setThreadsCount: function(threadsCount)
                {
                    that.threadsCount = threadsCount;
                }
            }

            return that;
        }

        function phpRack_Timer(options)
        {
            var that = {
                elapsedSeconds: 0,
                // timer interval handler (used internally for unregister timer from window)
                intervalId: null,
                options: {onTick: null},
                // Constructor
                __construct: function(options)
                {
                    // Overwrite default options
                    $.extend(that.options, options);
                },
                // When timer is active this function will be automatically executed every second
                tick: function()
                {
                    that.elapsedSeconds++;

                    // If callback function was passed to constructor as param, execute it
                    if (that.options.onTick != null) {
                        that.options.onTick(that);
                    }
                },
                start: function()
                {
                    // Stop timer if was earlier executed
                    that.stop();

                    // Reset timer elapsed time
                    that.elapsedSeconds = 0;

                    // Register timer function which will be called every 1000ms = 1s
                    that.intervalId = window.setInterval(that.tick, 1000);
                },
                stop: function()
                {
                    // If timer was earlier started and is still active
                    if (that.intervalId != null) {
                        // Remove interval to stop calling tick() function every second
                        window.clearInterval(that.intervalId);
                    }
                    that.intervalId = null;
                },
                // Return elapsed seconds in format (m:ss)
                getFormattedTime: function()
                {
                    var result = '';
                    var minutes = Math.floor(that.elapsedSeconds / 60);
                    var seconds = that.elapsedSeconds % 60;
                    result += minutes + ':';
                    if (seconds < 10) {
                        result += '0';
                    }
                    result += seconds;
                    return result;
                },
                // Return elapsed seconds as integer
                getElapsedSeconds: function()
                {
                    return that.elapsedSeconds;
                }
            }
            that.__construct(options);
            return that;
        }

        function phpRack_Test(options)
        {
            // jQuery object which represent test task Element in DOM tree
            var $this = $(options.id);
            var that = {
                options: options,
                timer: null,
                isRunning: false,
                // jQuery object which represent test result Element in DOM tree
                $result: $this.find('span.result'),
                // jQuery object which represent test name Element in DOM tree
                $label: $this.find('span.label'),
                // jQuery object which represent test message Element in DOM tree
                $message: $this.find('pre'),
                displayTimer: false,
                // Constructor
                __construct: function()
                {
                    // Create timer object and pass callback function to it
                    that.timer = new phpRack_Timer({onTick: that.onTimerTick});

                    // If user click on test name
                    that.$label.click(
                        function()
                        {
                            // Set that timer should be visible
                            that.displayTimer = true;

                            // Try execute test one more time
                            that.run();
                        }
                    );
                },
                setOption: function(key, value)
                {
                    that.options[key] = value;
                },
                onResultClick: function()
                {
                    that.$message.slideToggle();
                },
                _setStatus: function (success, message)
                {
                    // Update result span with OK/FAILURE depending on success param
                    if (success == true) {
                        that.$result.html('<?php echo phpRack_Test::OK?>').addClass('success');
                    } else {
                        that.$result.html('<?php echo phpRack_Test::FAILURE?>').addClass('failure');
                    }

                    // Fill message <pre></pre> with text returned from server
                    that.$message.html(message);

                    // Stop timer and update its state to user can repeat test
                    that.isRunning = false;
                    that.timer.stop();

                    // Remove earlier added handler to don't have duplicate
                    that.$result.unbind('click', that.onResultClick);
                    that.$result.bind('click', that.onResultClick);

                    // If we have registered callback function after test finish, execute it
                    if (that.options.onFinish) {
                        that.options.onFinish();
                    }
                },
                _startTimer: function()
                {
                    // Start test timer
                    that.timer.start();
                    that.onTimerTick();
                },
                // Callback function which will be called every seconds from phpRack_Timer
                onTimerTick: function()
                {
                    // If test is executed above 5s, set flag to display timer
                    if (that.timer.getElapsedSeconds() > 5) {
                        that.displayTimer = true;
                    }

                    // Check that should display timer (User click or time execution > 5s)
                    if (that.displayTimer) {
                        that.$result.html('running (' + that.timer.getFormattedTime() + ')...');
                    }
                },
                run: function()
                {
                    // Script still waiting for receive response from server
                    if (that.isRunning) {
                        // set flag to display timer
                        that.displayTimer = true;
                        that.onTimerTick();

                        // Prevent execution one more time because test is still running
                        return;
                    }

                    // Set initial states
                    that._startTimer();
                    that.isRunning = true;
                    that.$result.html('running...');

                    // Remove added classes, because test can be executed many times
                    that.$result.removeClass('success failure');

                    // Make ajax query to server
                    $.ajax(
                        {
                            url: that.options.url,
                            data: that.options.data,
                            dataType: 'json',
                            cache: false, // Set flag to don't cache server response
                            // Standard callback if JSON returned from server is correct
                            // or have 0 bytes response
                            success: function (json)
                            {
                                // We should use standard display method without word wrap
                                that.$message.removeClass('word_wrap');

                                // If server returned no empty response
                                if (json) {
                                    // Set status to OK with log message
                                    that._setStatus(json.success, json.log);
                                } else {
                                    // Set status to FAILURE with empty response message
                                    that._setStatus(false, 'Server returned empty response');
                                }
                            },
                            // Callback function if have some communication errors
                            error: function (XMLHttpRequest, textStatus, errorThrown)
                            {
                                // If we have some problem with server (Internal error, 404, 301/302 redirection)
                                if (XMLHttpRequest.status != 200) {
                                    // Create error message with headers
                                    message = XMLHttpRequest.status + ' ' + XMLHttpRequest.statusText + "\n"
                                              + XMLHttpRequest.responseText;
                                } else {
                                    // We received malformed JSON
                                    // (php script throwed some warning, unhandled exception, extra data, etc...)
                                    message = errorThrown;
                                }

                                // Use word wrap for this messages type
                                that.$message.addClass('word_wrap');

                                // Set status to FAILURE with errorThrown as message
                                that._setStatus(false, message);
                            }
                        }
                    );
                }
            }

            that.__construct();
            return that;
        }

        // Create queue for processing our tests
        var taskQueue = new phpRack_TaskQueue();

        // Set how many threads(tests) should be executed in same time
        // (not too high to avoid Apache limit problems)
        taskQueue.setThreadsCount(2);

        for (var id in calls) {
            // Create test object
            var test = new phpRack_Test(
                {
                    id: id,
                    url: '<?php echo $_SERVER['REQUEST_URI']?>',
                    data: {
                        '<?php echo PHPRACK_AJAX_TAG?>': calls[id],
                        '<?php echo PHPRACK_AJAX_TOKEN?>': id
                    }
                }
            );

            // Add test to processing queue
            taskQueue.add(test);
        }
    }
);
