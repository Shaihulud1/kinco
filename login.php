<?php 
	session_start();
?>
<?php 
	require "header.php";

	$data = $_POST;
	if(isset($data['push_login']))
	{
		//если нажато войти
		$err = array();
		$login = trim($_POST['login']);
		$pass = md5(md5($_POST['pass']));

		$sql = ("SELECT count(*) FROM users WHERE uname = '".$login."' AND upass = '".$pass."' ");
    	$result = $pdo->prepare($sql);
    	$result ->execute();
    	$row = $result->fetch();

    	if ($row[0] < 1) {
    		//неправильный логин или пароль
    		$err[] = "Вы ввели неправильный логин или пароль";
    	}

    	 if(empty($err)){
    	 	//все верно, можно логинить
    	 	$sql = ("SELECT * FROM users WHERE uname = '".$login."'");
    		$result = $pdo->prepare($sql);
    		$result ->execute();
    		$row = $result->fetchAll(PDO::FETCH_ASSOC);
    		foreach ($row as $row) {
    			$_SESSION['logged_user'] = $row['uname'];
    			$_SESSION['logged_id'] = $row['id'];
    			$_SESSION['user_role'] = $row['urole'];
    			$_SESSION['uava'] = $row['uava'];
    		}
    		?>
    		<script>
				location.replace("http://kin.os:83/index.php")
			</script>
			<?php

    	 }else{
    	 	//неправильно что-то
    	 	echo array_shift($err);
    	 }


	}
?>





	<form action="/login.php" METHOD = "POST">
	
	<p>
		<p><strong>LOGIN:</strong></p>
		<input type="text" name = "login">	
	</p>

	<p>
		<p><strong>PASS:</strong></p>
		<input type="password" name = "pass">	
	</p>

	<p>
		<button type="submit" name = "push_login">Войти</button>
	</p>

	

</form>
<?php
	require_once "footer.php";

