<?php
ob_implicit_flush(true);
ob_end_flush();

//Command to execute ping to 8.8.8.8
$cmd = "ping 8.8.8.8";

$descriptorspec = array(
    0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
    1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
    2 => array("pipe", "w")    // stderr is a pipe that the child will write to
);
flush();

//Open process
$process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());
echo "<pre>";

//If is a resource
if (is_resource($process)) {
    //While command is executing
    while ($s = fgets($pipes[1])) {
        //Regexp matching ping
        preg_match("/([0-9]{1,3}\.?[0-9]{1,2}) ms/i", "$s", $pings);

        //Set default style for the line
        $style = '';
        if(isset($pings[1])) {
            $ping = $pings[1];

            //If ping is a number, color it when ping is more than 40 or less than 10
            if (is_numeric($ping)) {
                if ($ping > 40) {
                    $style = "background-color: #fd4545;color: white;";
                } elseif ($ping < 10) {
                    $style = "    background-color: #09b509;color: white;";
                }
            }
        }

        //Print the result
        print "<span style='$style'>".$s."</span>";
        flush();
    }
}
echo "</pre>";