<?php 

// do something every day
//

$execd = shell_exec('date');
echo 'The time is now ' . $execd;
exit();

