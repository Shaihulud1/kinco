<?php

require_once 'libs/db.php';
$sql = ("SELECT * FROM categorys");
$result = $pdo->prepare($sql);
$result ->execute();

$cata = $result->fetchAll(PDO::FETCH_ASSOC);
	if(count($cata)){
		foreach ($cata as $cata) {
			echo "<option>{$cata['cname']}</option>";
		}
	}else{
		echo 'error';
	}
?>


    

