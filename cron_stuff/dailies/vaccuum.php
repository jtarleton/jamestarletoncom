<?php 

$exec = shell_exec('journalctl --vacuum-time=1d');
echo $exec;
