<style type="text/css">
#debug_icon{width:auto;padding:10px;background-color:#F00;position:fixed;right:10px;bottom:0px;color:#000;}
#debug_detail{position:fixed;left:0px;top:0px;width:99%;height:300px;display:none;color:#000;text-align:left;border:1px solid #F00;background-color:#fff;overflow:auto;}
pre{width:auto;padding-right:15px;border:1px solid #00F;text-justify:auto;}
</style>

<script type="text/javascript">
$(document).ready(function(){
	
	$("#debug_icon").click(function() 
	{
		$("#debug_detail").toggle("slow");
	});
	
});
</script>

<div id="debug_icon">Debug</div>
<div id="debug_detail">
	<pre>
    <?php 
		echo '<h2>Server</h2>';
		print_r($_SERVER);
	?>
    </pre>
	<pre>
    <?php
    	echo '<h2>Session</h2>';
		print_r($_SESSION);
	?>
    </pre>
    <pre>
    <?php 
		echo '<h2>Post</h2>';
		print_r($_POST);
	?>
    </pre>
    <pre>
    <?php 
		echo '<h2>Get</h2>';
		print_r($_GET);
	?>
    </pre>
    <pre>
    <?php 
		echo '<h2>Files</h2>';
		print_r($_FILES);
	?>
    </pre>
    <pre>
    <?php 
		echo '<h2>Cookie</h2>';
		print_r($_COOKIE);
	?>
    </pre>
	<pre>
    	<h2>Time and Memory usage</h2>
    <?php 
		echo 'Memory used by current script '.(memory_get_peak_usage(true) / 1024 ).' Kb <br />';
		/*$End = getTime(); 
		echo "Time taken = ".number_format(($End - $Start),2)." secs <br />"; 
		echo 'Garbage cycles '.gc_collect_cycles();*/
	?>
    </pre>
</div>