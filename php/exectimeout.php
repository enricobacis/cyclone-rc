<?php

function killprocess($process) {
  $status = proc_get_status($process);
  if($status['running'] == true) { //process ran too long, kill it
    ob_start();
    //close all pipes that are still open
    fclose($pipes[1]); //stdout
    fclose($pipes[2]); //stderr
    //get the parent pid of the process we want to kill
    $ppid = $status['pid'];
    //use ps to get all the children of this process, and kill them
    $pids = preg_split('/\s+/', `ps -o pid --no-heading --ppid $ppid`);
    foreach($pids as $pid) {
        if(is_numeric($pid)) {
            echo "Killing $pid\n";
            posix_kill($pid, 9); //9 is the SIGKILL signal
        }
    }
    proc_close($process);
    ob_end_clean();
  }
}

function ExecWaitTimeout($cmd, $timeout=5) {
 
  $descriptorspec = array(
      0 => array("pipe", "r"),
      1 => array("pipe", "w"),
      2 => array("pipe", "w")
  );
  $pipes = array();
 
  $timeout += time();
  $process = proc_open($cmd, $descriptorspec, $pipes);
  if (!is_resource($process)) {
    return "Error executing command";
  }
 
  $output = '';
 
  do {
    $timeleft = $timeout - time();
    $read = array($pipes[1]);
    stream_select($read, $write = NULL, $exeptions = NULL, $timeleft, NULL);
 
    if (!empty($read)) {
      $output .= fread($pipes[1], 8192);
    }
  } while (!feof($pipes[1]) && $timeleft > 0);
 
  if ($timeleft <= 0) {
    killprocess($process);
    return "Execution timeout after 5 seconds";
  } else {
    return $output;
  }
}

?>