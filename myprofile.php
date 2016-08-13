<?php 
	session_start();
?>
<?php 
	require_once "header.php";
	require_once 'libs/iupload.php';
	if($_SESSION['logged_user'] == $_GET['name'] && isset($_SESSION['logged_user'])){
		//вошел в свой профиль, если зареган
		echo "Вы вошли в свой профиль";?>
		<h1>ваш никнейм <?php echo $_SESSION['logged_user'] ?></h1></br>
		<a href="myprofile.php"><img src="avatars/<?php print $_SESSION['uava'];?>" width="100" height="100" alt="ava"></a>
		<form method="post" enctype="multipart/form-data">
	      <input type="file" name="file">
	      <input type="submit" value="Загрузить новый аватар">
    	</form>
    	<br>
    	<form method="POST">
		 	<p>
				<p><strong>OLD PASS:</strong></p>
				<input type="password" name = "o_pass">	
			</p>

			<p>
				<p><strong>NEW PASS:</strong></p>
				<input type="password" name = "n_pass">	
			</p>

			<p>
				<p><strong>NEW PASS AGAIN:</strong></p>
				<input type="password" name = "n_pass2">	
			</p>

			<p>
				<button type="submit" name = "change_pass">Сменить пароль</button>
			</p>
    	</form>
    	</br>
    	<br><br><br><br><br>
    	<h1>Фильмы которые понравились<h1>
<?php
		$sql = ("SELECT * FROM likes WHERE id_user = '".$_SESSION['logged_id']."'");
    	$result = $pdo->prepare($sql);
    	$result ->execute();
    	$likes = $result->fetchAll(PDO::FETCH_ASSOC);
    	foreach ($likes as $likes) {
			$sql = ("SELECT * FROM films WHERE id = '".$likes['id_film']."'");
	    	$result = $pdo->prepare($sql);
	    	$result ->execute();
	    	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	    	foreach ($row as $row ) {
	    		
?>
				<a href='movie.php?n=<?php echo $row ['fname']?>'><?php echo $row['fname']?></a><br>
    			<a href="movie.php?n=<?php echo $row ['fname']?>"><img src="posters/<?php echo $row['fposter']; ?>"  width="300" height="300" alt="poster_must_be_here"></a><br>
    	
<?php 	}	
    }
?>
		<br><br>
    	<h1>Фильмы которые не понравились<h1>
<?php
		$sql = ("SELECT * FROM dislikes WHERE id_user = '".$_SESSION['logged_id']."'");
    	$result = $pdo->prepare($sql);
    	$result ->execute();
    	$dislikes = $result->fetchAll(PDO::FETCH_ASSOC);
    	foreach ($dislikes as $dislikes) {
			$sql = ("SELECT * FROM films WHERE id = '".$dislikes['id_film']."'");
	    	$result = $pdo->prepare($sql);
	    	$result ->execute();
	    	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	    	foreach ($row as $row ) {
	    		
?>
				<a href='movie.php?n=<?php echo $row ['fname']?>'><?php echo $row['fname']?></a><br>
    			<a href="movie.php?n=<?php echo $row ['fname']?>"><img src="posters/<?php echo $row['fposter']; ?>"  width="300" height="300" alt="poster_must_be_here"></a><br>
    	
<?php 	}	
    }
?>
    	<br><br>
    	<h1>Фильмы я посмотрю позже<h1>
<?php
		$sql = ("SELECT * FROM later WHERE id_user = '".$_SESSION['logged_id']."'");
    	$result = $pdo->prepare($sql);
    	$result ->execute();
    	$later = $result->fetchAll(PDO::FETCH_ASSOC);
    	foreach ($later as $later) {
			$sql = ("SELECT * FROM films WHERE id = '".$later['id_film']."'");
	    	$result = $pdo->prepare($sql);
	    	$result ->execute();
	    	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	    	foreach ($row as $row ) {
?>
				<a href='movie.php?n=<?php echo $row ['fname']?>'><?php echo $row['fname']?></a><br>
    			<a href="movie.php?n=<?php echo $row ['fname']?>"><img src="posters/<?php echo $row['fposter']; ?>"  width="300" height="300" alt="poster_must_be_here"></a><br>	
    	<br><br>
<?php }
}
?>

<?php
	
 		// смена аватарки
		if(isset($_FILES['file'])) {
			 	//загружен файл
		      	// проверяем, можно ли загружать изображение
		      	$check = can_upload($_FILES['file']);
		    
		      if($check === true){
		        // загружаем изображение на сервер
		        $ava_new = make_upload($_FILES['file']);
		        echo "<strong>Файл успешно загружен!</strong>";
		        $_SESSION['uava'] = $ava_new;
		        $login = $_SESSION['logged_user'];
		        $sql = ("UPDATE users SET uava = '".$ava_new."' WHERE uname = '".$login."' ");
				$result = $pdo->prepare($sql);
				$result ->execute();
		      }
		      else{
		        // выводим сообщение об ошибке
		        echo "<strong>$check</strong>";  
		      }
		}
		// тут начинается смена пароля
	$data = $_POST;
	if (isset($data['change_pass']))
		{
			//кнопка нажата
			$err = array();
			$o_pass = md5(md5($_POST['o_pass']));


			if ($data['o_pass']=='')
			{
				$err[] = 'Введите старый пароль';
			}			

			$sql = ("SELECT count(*) FROM users WHERE upass = '".$o_pass."' and uname = '".$_SESSION['logged_user']."' ");
	    	$result = $pdo->prepare($sql);
	    	$result ->execute();
	    	$row = $result->fetch();
	    	if ($row[0] != 1){
	    		$err[] = "Вы ввели неправильный старый пароль";
	    	}
	    	if ($data['n_pass']=='')
			{
				$err[] = 'Вы не ввели новый пароль';
			}
	    	if ($data['n_pass2']=='')
			{
				$err[] = 'Вы не ввели новый еще раз пароль';
			}

			if ($data['n_pass2'] != $data['n_pass']){
				$err[] = 'Повторный пароль не совпадает с введенным';
			}
			if(empty($err))
			{
				//все хорошо
				$n_pass = md5(md5($_POST['n_pass']));
				$sql = ("UPDATE users SET upass = '".$n_pass."' WHERE uname = '".$_SESSION['logged_user']."' ");
				$result = $pdo->prepare($sql);
				$result ->execute();
				?>

				<?php
			}else{
				//есть ошибки
				echo array_shift($err);
			}		
		}



	}elseif( (isset($_SESSION['logged_user'])) || ($_SESSION['logged_user'] != $_GET['name']) ){
		//если чужой профиль
		echo "Вы вошли в профиль пользователя с ником ".$_GET['name'];
		//получаем айди пользователя
    	 	$sql = ("SELECT * FROM users WHERE uname = '".$_GET['name']."'");
    		$result = $pdo->prepare($sql);
    		$result ->execute();
    		$row = $result->fetchAll(PDO::FETCH_ASSOC);
    		foreach ($row as $row) {
    			$thisid = $row['id'];
    			$thisava = $row['uava'];
    		}

		$sql = ("SELECT * FROM users WHERE uname = '".$_GET['name']."'");
		$result = $pdo->prepare($sql);
		$result ->execute();
		$allus = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach ($allus as $allus) { ?>
		<a href="myprofile.php"><img src="avatars/<?php echo $allus['uava']?>" width="100" height="100" alt="ava"></a>
    	<br><br><br><br><br>
    	<h1>Фильмы которые понравились<h1>
<?php
		$sql = ("SELECT * FROM likes WHERE id_user = '".$thisid."'");
    	$result = $pdo->prepare($sql);
    	$result ->execute();
    	$likes = $result->fetchAll(PDO::FETCH_ASSOC);
    	foreach ($likes as $likes) {
			$sql = ("SELECT * FROM films WHERE id = '".$likes['id_film']."'");
	    	$result = $pdo->prepare($sql);
	    	$result ->execute();
	    	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	    	foreach ($row as $row ) {
	    		
?>
				<a href='movie.php?n=<?php echo $row ['fname']?>'><?php echo $row['fname']?></a><br>
    			<a href="movie.php?n=<?php echo $row ['fname']?>"><img src="posters/<?php echo $row['fposter']; ?>"  width="300" height="300" alt="poster_must_be_here"></a><br>
    	
<?php 	}	
    }
?>		<br><br>
    	<h1>Фильмы которые не понравились<h1>
<?php
		$sql = ("SELECT * FROM dislikes WHERE id_user = '".$thisid."'");
    	$result = $pdo->prepare($sql);
    	$result ->execute();
    	$dislikes = $result->fetchAll(PDO::FETCH_ASSOC);
    	foreach ($dislikes as $dislikes) {
			$sql = ("SELECT * FROM films WHERE id = '".$dislikes['id_film']."'");
	    	$result = $pdo->prepare($sql);
	    	$result ->execute();
	    	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	    	foreach ($row as $row ) {
	    		
?>
				<a href='movie.php?n=<?php echo $row ['fname']?>'><?php echo $row['fname']?></a><br>
    			<a href="movie.php?n=<?php echo $row ['fname']?>"><img src="posters/<?php echo $row['fposter']; ?>"  width="300" height="300" alt="poster_must_be_here"></a><br>
    	
<?php 	}	
    }
?>
    	<br><br>
    	<h1>Фильмы которые посмотрит позже<h1>
<?php
		$sql = ("SELECT * FROM later WHERE id_user = '".$thisid."'");
    	$result = $pdo->prepare($sql);
    	$result ->execute();
    	$later = $result->fetchAll(PDO::FETCH_ASSOC);
    	foreach ($later as $later) {
			$sql = ("SELECT * FROM films WHERE id = '".$later['id_film']."'");
	    	$result = $pdo->prepare($sql);
	    	$result ->execute();
	    	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	    	foreach ($row as $row ) {
?>
				<a href='movie.php?n=<?php echo $row ['fname']?>'><?php echo $row['fname']?></a><br>
    			<a href="movie.php?n=<?php echo $row ['fname']?>"><img src="posters/<?php echo $row['fposter']; ?>"  width="300" height="300" alt="poster_must_be_here"></a><br>	
    	<br><br>
<?php }
}
?>		

<?php		
		}

	}

require_once "footer.php";


