<?php
	$tokenage = file_get_contents("time.txt");
	echo $tokenage;
	echo '<br>';
	echo time();
	echo '<br>';
	echo (time() - $tokenage);
    file_put_contents('time
    test.txt', 'test');
?>