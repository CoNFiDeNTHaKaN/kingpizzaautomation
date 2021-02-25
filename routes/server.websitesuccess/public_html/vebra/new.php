<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>


<?php
	$value = 'test string';
	
		$file = "headers/log	_" . date('Y-m-d_H-i-s', time() ) . '.txt' ;
		
	file_put_contents( $file, $value);
?>


</body>
</html>
