<?php

if(get_permission($cur_page, true) == false) $cur_page = 'login';

include('application/header.php');

$page = $cur_page.'.php';
if(file_exists(SRV_ROOT.'views/'.$page)) include($page);
else include('404.php');

include('application/footer.php'); 

?> 