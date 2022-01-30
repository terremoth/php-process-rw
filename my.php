<?php
function execute($cmd,$stdin=null,$timeout=10000){
    $proc= proc_open($cmd,array(0=>array('pipe','r'),1=>array('pipe','w'),2=>array('pipe','w')),$pipes);
    $write  = array($pipes[0]);
    $read   = array($pipes[1], $pipes[2]);
    $except = null;
    $stdout = '';
    $stderr = '';
    while($r = stream_select($read, $write, $except, null, $timeout)){
        foreach($read as $stream){

            // handle STDOUT
            if($stream===$pipes[1])
                /*...*/         $stdout.=stream_get_contents($stream);

            // handle STDERR
            if($stream===$pipes[2])
                /*...*/         $stderr.=stream_get_contents($stream);
        }

        // Handle STDIN (???)
        if(isset($write[0])) {
            echo "I can handle stdin".PHP_EOL;
        }

// the following code is temporary
        $n = isset($n) ? $n+1 : 0;
        if ($n > 10) break; // break while loop after 10 iterations

    }
}
///*

$descriptorspec = [
    0=>["pipe", "r"],  // stdin is a pipe that the child will read from
    1=>["pipe", "w"],  // stdout is a pipe that the child will write to
    2=>["pipe", "r"] // stderr is a file to write to
];
$cwd = null;
$env = null;

$process = proc_open('php process.php', $descriptorspec, $pipes, $cwd, $env);

if (is_resource($process)) {
    // $pipes now looks like this:
    // 0 => writeable handle connected to child stdin
    // 1 => readable handle connected to child stdout
    // Any error output will be appended to /tmp/error-output.txt
    stream_set_blocking($pipes[0], 0);
    stream_set_blocking($pipes[1], 0);
    stream_set_blocking($pipes[2], 0);

//    fwrite($pipes[0], "LIST DISK".PHP_EOL);
//    fwrite($pipes[0], "exit".PHP_EOL);
//    fclose($pipes[0]);

//    $str = fread($pipes[1], 4000);
    while (proc_get_status($process)['running']) {
        // Read all available output (unread output is buffered).
        $str = fread($pipes[0], 1024);
        $str2 = fread($pipes[1], 1024);
        $str3 = fread($pipes[2], 1024);

        if ($str) {
            var_dump($str);
        }
        if ($str2) {
            var_dump($str2);
        }
        if ($str3) {
            var_dump($str3);
        }
        sleep(3); // 100ms
    }
    echo 'ja saiu'.PHP_EOL;
    fclose($pipes[2]);

    // It is important that you close any pipes before calling
    // proc_close in order to avoid a deadlock
    $return_value = proc_close($process);

    echo "command returned $return_value\n";
}
/*
require_once 'InterExec.php';

$exec = new InterExec('php process.php');


$exec->on('start', function(){
    echo 'Start'.PHP_EOL;
});

$exec->on('output', function($exec, $data){
    var_dump($exec).PHP_EOL;
    echo 'Caiu no output; '.PHP_EOL;
});

$exec->on('input', function($exec, $last){
    echo 'last:'.PHP_EOL;
    echo $last.PHP_EOL;

    //return $data;
});

$exec->on('stop', function(){
    echo 'Stop<br/>';
});

$exec->run();
//*/

