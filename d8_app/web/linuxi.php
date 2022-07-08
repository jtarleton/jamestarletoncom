<?php 

$oscmds = [
	'uname -a',
	        'lsb_release -a'
];

$cmds = [
	'glances --version',
	'curl --version',
	'aria2c --version',
	'wget --version',
	'uname -a',
	'lsb_release -a',
	'php -v',
	'apache2 -v',
	'composer --version',
	'drush --version',
	'drush status',
	'phpunit --version',
	'mysql --version',
	'python3 --version',
	'pip --version',
	'ruby -v',
	'gem --version',
	'npm -v',
	'locate --version',
	'vim --version'
	];



$osresults = [];

foreach($oscmds as $cmd) {

	$osresults[$cmd] = shell_exec($cmd);
}



$results = [];

foreach($cmds as $cmd) {

$results[$cmd] = shell_exec($cmd);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<style type="text/css">

    body {background-color: #fff; color: #222; font-family: sans-serif;}
    pre {margin: 0; font-family: monospace;}
    a:link {color: #009; text-decoration: none; background-color: #fff;}
    a:hover {text-decoration: underline;}
    table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
    .center {text-align: center;}
    .center table {margin: 1em auto; text-align: left;}
    .center th {text-align: center !important;}
    td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
    th {position: sticky; top: 0; background: inherit;}
    h1 {font-size: 150%;}
    h2 {font-size: 125%;}
    .p {text-align: left;}
    .e {background-color: #ccf; width: 300px; font-weight: bold;}
    .h {background-color: #99c; font-weight: bold;}
    .v {background-color: #ddd; max-width: 300px; overflow-x: auto; word-wrap: break-word;


font-family: monospace;
    white-space: pre;
}
    .v i {color: #999;

font-family: monospace;
    white-space: pre;
width:300px;
overflow:scroll;
	}
    img {float: right; border: 0;}
    hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}

</style>

<title>PHP 8.0.14 - phpinfo()</title>
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />

</head>


<body>
<div class="center">
    <table>
        <tr class="h">
            <td>
                <h1 class="p">System Applications Summary</h1>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="e">Zend Multibyte Support </td>
            <td class="v">provided by mbstring </td>
        </tr>
    </table>

    <table>
        <tr class="v">
            <td>k
            </td>
        </tr>
    </table>

    <hr />

    <h1>Configuration</h1>




<h2><a name="module_calendar">OS Info</a></h2>
    <table>

        <?php foreach($osresults as $c=>$result): ?>
        <tr>
        <td class="e"><?php echo $c; ?></td>
            <td class="v"><?php echo (strpos($result, 'Included patches')!==FALSE) ? strstr($result,'Included patches', true) : $result; ?>

            </td>
        </tr>
        <?php endforeach; ?>
    </table>


    <h2><a name="module_calendar">Binary Commands</a></h2>
    <table>

	<?php foreach($results as $c=>$result): ?>
        <tr>
	<td class="e"><?php echo $c; ?></td>
            <td class="v"><?php echo (strpos($result, 'Included patches')!==FALSE) ? strstr($result,'Included patches', true) : $result; ?>

            </td>
	</tr>
	<?php endforeach; ?>
    </table>


    <table>
        <tr class="h"><th>Directive</th><th>Local Value</th><th>Master Value</th></tr>
        <tr>
            <td class="v">
            </td>
            <td class="v">
            </td>
            <td class="v">
            </td>
        </tr>
    </table>


</div>
</body>
</html>



