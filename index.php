<?php 
	session_start();
?>
<?php 


	require_once "header.php";
		if(isset($_SESSION['logged_user'])){
			//если залогинен
			echo 'Вы вошли под ником '.$_SESSION['logged_user'];?>
			<a href="myprofile.php?name=<?php echo $_SESSION['logged_user']?>">Мой профиль</a>

			<a href="myprofile.php"><img src="avatars/<?php echo $_SESSION['uava'];?>" width="100" height="100" alt="ava"></a>
			<a href="destroy.php">Выйти из профиля</a>
<?php
			if ($_SESSION['user_role'] == 'admin'){
				echo'вы админ';?>
				<a href="superuser.php">Панель админа</a>
<?php
			}
		}else{ 
?>
			<a href="/login.php">Авторизироваться</a><br>
			<a href="/signup.php">Регистрация</a>
					
<?php
		}
		//вывод всех пользователей сайта
		$sql = ("SELECT * FROM users ");
		$result = $pdo->prepare($sql);
		$result ->execute();
		$allus = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach ($allus as $allus){ ?>
			<br><br>
			<a href="myprofile.php?name=<?php echo $allus['uname']?>"><?php echo $allus['uname']?></a><br>
			<a href="myprofile.php?name=<?php echo $allus['uname']?>"><img src="avatars/<?php echo $allus['uava']?>" width="100" height="100" alt="ava"></a><br>
			<br> <br> <br>
<?php
}

//pagination///
	$num = 1;///тут указывается кол-во записей на странице
	$page = $_GET['page'];
	$sql = ("SELECT count(*) FROM films");
	$result = $pdo->prepare($sql);
	$result ->execute();
	$paginat = $result->fetch();
	$posts = $paginat[0];
	$total = (($posts - 1) / $num) + 1;
	$total = intval($total);
	$page = intval($page);
	if(empty($page) or $page < 0) $page = 1;
	if ($page > $total) $page = $total;
	$start = $page * $num - $num;
////////////////////////////////////////////////


	$sql = ("SELECT * FROM films ORDER BY id DESC LIMIT $start, $num");
    		$result = $pdo->prepare($sql);
    		$result ->execute();
    		$row = $result->fetchAll(PDO::FETCH_ASSOC);
    		foreach ($row as $row):?>

    			<br/><br/><br/>
				<a href='movie.php?n=<?php echo $row ['fname']?>'><?php echo $row['fname']?></a><br>
				<?php
				//вывод жанров
				$sql = ("SELECT fi.fname, ca.cname FROM films_categories fc
							INNER JOIN films fi ON fc.id_film = fi.id
							INNER JOIN categorys ca ON fc.id_cat = ca.id
							WHERE fi.fname = '".$row['fname']."'");
				$result = $pdo->prepare($sql);
				$result ->execute();
				$cname = $result->fetchAll(PDO::FETCH_ASSOC);
				foreach ($cname as $cname):?>
					<a href="category.php?n=<?php echo $cname['cname']?>"><?php echo $cname['cname']?></a><br>							
				<?php endforeach;?>				
    			<a href="movie.php?n=<?php echo $row ['fname']?>"><img src="posters/<?php echo $row['fposter']; ?>"  width="300" height="300" alt="poster_must_be_here"></a><br>
    			
    			

    			
<?php


    		endforeach;

 ///вывод пагинации///
   	//нужны ли стрелки назад
    if ($page != 1) $pervpage = '<a href=index.php?page=1>Первая</a> | <a href=index.php?page='.($page-1).'>Предыдущая</a> | ';
    //нужны ли стрелки вперед
    if ($page != $total) $nextpage = ' | <a href=index.php?page='.($page + 1). '>Следующая</a> | <a href=index.php?page='.$total.'>Последняя</a>';


    if($page - 5 > 0) $page5left = '<a href=index.php?page='.($page-5).'>'.($page-5).'</a> | ';
    if($page - 4 > 0) $page4left = '<a href=index.php?page='.($page-4).'>'.($page-4).'</a> | ';
    if($page - 3 > 0) $page3left = '<a href=index.php?page='.($page-3).'>'.($page-3).'</a> | ';
    if($page - 2 > 0) $page2left = '<a href=index.php?page='.($page-2).'>'.($page-2).'</a> | ';
    if($page - 1 > 0) $page1left = '<a href=index.php?page='.($page-1).'>'.($page-1).'</a> | ';

    if($page + 5 <= $total) $page5right = ' | <a href=index.php?page='.($page+5).'>'.($page+5).'</a>';
    if($page + 4 <= $total) $page4right = ' | <a href=index.php?page='.($page+4).'>'.($page+4).'</a>';
    if($page + 3 <= $total) $page3right = ' | <a href=index.php?page='.($page+3).'>'.($page+3).'</a>';
    if($page + 2 <= $total) $page2right = ' | <a href=index.php?page='.($page+2).'>'.($page+2).'</a>';
    if($page + 1 <= $total) $page1right = ' | <a href=index.php?page='.($page+1).'>'.($page+1).'</a>';

///вывод
    if($total>1){
    	echo "<div class = \"pstrnav\">";
    	echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
    	echo "</div>";
    }

 ////
 ?>
<script src="http://code.jquery.com/jquery-1.12.1.js"></script>
<?php 
require_once "footer.php";