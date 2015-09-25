<!--
A simple PHP script that will create or modify the robots.txt in all sub domains on a server to disallow indexing. 
Very useful for large staging servers.
Place this file at the root of the server and navigate to it with your browser. That is all you need to do.
If the robots.txt doesnt exsist it will be created. If it does exsist it will be modified and a backup will be made (just in case).
-->

<style>
body
	{
		background:grey;
		color:white;
		font-size:20px;
		text-align:center;
		font-family:"Lucida Console", Monaco, monospace;
	}
</style>

<?php
/*
//A script that will scan the root of your server, and look for all folders, it assumes all folders on the root should 
//be disallowed with robots.txt
*/

$path = '../public_html' . $name[0];
$results = scandir($path);
$dir_list =[];
echo "SCANNING ROOT:<P>FOUND:<P>";
foreach ($results as $result) {
    if ($result === '.' or $result === '..') continue;

    if (is_dir($path . '/' . $result)) {
		array_push($dir_list, $result);
        echo "~/".$result."/</br>";
    }
}
$line = "------- ------- -------";
echo "<P>".$line."<P>ASSUMING ALL SUBDIR...<P>".$line."<P>";
foreach ($dir_list as $cur_dir)	
	{
		$txt = "User-agent: *".PHP_EOL."Disallow: /";
		$dir_results = scandir($cur_dir);
		if (in_array("robots.txt", $dir_results))
			{
				echo "<P>".$cur_dir." already contains robots.txt.";
				echo "</br> Creating backup at old_robots.txt";
				$new_robots = fopen($cur_dir."/robots.txt", "w");
				$old_data = file_get_contents($cur_dir."/robots.txt");
				fwrite($new_robots, $txt);
				fclose($new_robots);
				$old_robots = fopen($cur_dir."/old_robots.txt", "w");
				fwrite($old_robots, $old_data);
				fclose($old_robots);
				echo "</br>BACKUP COMPLETE.</br>Created new robots.txt file.<P>".$line;
			}
		else
			{
				echo "<P>".$cur_dir." has no robots.txt";
				echo "<P>NOW CREATING ROBOTS.TXT";
				$new_robots = fopen($cur_dir."/robots.txt", "w");
				fwrite($new_robots, $txt);
				fclose($new_robots);
			}
	}
?>
