<?php

require_once '../libs/db.php';

if (isset($_POST['id_user'])){
	$id_user = $_POST['id_user'];
	$id_film = $_POST['id_film'];

	$sql = ("INSERT INTO later SET id_user='".$id_user."', id_film='".$id_film."'");
	$result = $pdo->prepare($sql);
	$result ->execute();
}



    

