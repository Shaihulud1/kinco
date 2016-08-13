
<?php
	require_once 'header.php';
	$data = $_POST;
	if (isset($data['push_sign']))
	{
		//кнопка нажата
		$err = array();
		if (trim($data['login'])=='')
		{
			$err[] = 'Введите логин';
		}
   		if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
	    {

	      	$err[] = "Логин может состоять только из букв английского алфавита и цифр";

	    }
		if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)

    	{

        	$err[] = "Логин должен быть не меньше 3-х символов и не больше 30";

    	}
		$sql = ("SELECT count(*) FROM users WHERE uname = '".$_POST['login']."'");
    	$result = $pdo->prepare($sql);
    	$result ->execute();
    	$row = $result->fetch();;
    	if ($row[0] > 0){
    		$err[] = "Пользователь с таким именем уже существует";
    	}
		
		if (trim($data['email'])=='')
		{
			$err[] = 'Введите Email';
		}

		$sql = ("SELECT count(*) FROM users WHERE uemail = '".$_POST['email']."'");
    	$result = $pdo->prepare($sql);
    	$result ->execute();
    	$row = $result->fetch();
    	if ($row[0] > 0){
    		$err[] = "Пользователь с таким email уже существует";
    	}


		if ($data['pass']=='')
		{
			$err[] = 'Введите пароль';
		}
		if ($data['pass2'] != $data['pass']){
			$err[] = 'Повторный пароль не совпадает с введенным';
		}
		
		if(empty($err))
		{
			//все хорошо
			$login = $_POST['login'];
			$email = $_POST['email'];
			$pass = md5(md5($_POST['pass']));
			$sql = ("INSERT INTO users SET uname='".$login."', uemail='".$email."', upass='".$pass."'");
			$result = $pdo->prepare($sql);
			$result ->execute();
			?>
			<script>
				location.replace("http://kin.os:83/index.php")
			</script>
			<?php

		}else{
			//есть ошибки
			echo array_shift($err);
		}		
	}
?>

<body>
	
<form action="/signup.php" METHOD = "POST">
	
	<p>
		<p><strong>LOGIN:</strong></p>
		<input type="text" name = "login">	
	</p>

	<p>
		<p><strong>EMAIL:</strong></p>
		<input type="text" name = "email">	
	</p>

	<p>
		<p><strong>PASS:</strong></p>
		<input type="password" name = "pass">	
	</p>

	<p>
		<p><strong>PASS AGAIN:</strong></p>
		<input type="password" name = "pass2">	
	</p>

	<p>
		<button type="submit" name = "push_sign">Зарегестрироваться</button>
	</p>

	

</form>

<?php 
	require_once "footer.php";
