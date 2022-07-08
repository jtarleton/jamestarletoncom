<?php 

// do something every month
//

$execd = shell_exec('date');
echo 'The time is now ' . $execd;
exit();

