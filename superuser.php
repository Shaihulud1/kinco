<?php 
	session_start();
?>
<?php 
	require_once "header.php";
	require_once 'libs/pupload.php';
	require_once 'libs/fupload.php';
	require_once 'libs/translate.php';


			if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'moder'){
				//проверка на админа
						// $sql = ("SELECT * FROM films order by id DESC LIMIT 1");
						// $result = $pdo->prepare($sql);
						// $result ->execute();
						// $fil = $result->fetchAll(PDO::FETCH_ASSOC);
						// foreach ($fil as $fil) {
						// 	$thisfilm = $fil['id'] + 1;
						// 	echo $thisfilm;
						// }
				echo'панель админа';
?>
		<form method="post" enctype="multipart/form-data">
	      		
		      	<p>
					<p><strong>Название фильма:</strong></p>
					<input type="text" name = "fname">	
				</p>

			

				

				<p>
					<p><strong>Категория</strong></p>
					<div class="cat_container">
						<?php
						require_once 'libs/db.php';
						$sql = ("SELECT * FROM categorys");
						$result = $pdo->prepare($sql);
						$result ->execute();
						$cata = $result->fetchAll(PDO::FETCH_ASSOC);
							foreach ($cata as $cata): ?>
								<input type="checkbox" name="<?=$cata['cname']?>" value="<?=$cata['id']?>"><?=$cata['cname']?><Br>
								<?php $genre[] = $cata['id'];?>
							<?php endforeach;?>
					</div>
					
					<!-- <input type="category" name = "fcat"> -->	
				</p>

				<p>
					<p><strong>Постер</strong></p>
					<input type="file" name = "fpostes">	
				</p>

				<p>
					<p><strong>Описание</strong></p>
					<textarea name="fabout" id="fabout" cols="30" rows="10"></textarea>	
				</p>

				<p>
					<p><strong>Файл фильма</strong></p>
					<!-- <input type="file" name = "ffile"> -->	
					<input type="text" name = "ffile">
				</p>

	      		<input type="submit" value="Отправить" name = "film_go">

    	</form>
    	<br><br><br><br>
    	<h1>Добавить категорию</h1>
    	<form method="post">
				<p>
					<p><strong>Категория</strong></p>
					<input type="category" name = "new_cat">	
				</p>
	      		<input type="submit" value="Отправить" name = "cat_go">

    	</form>
<?php

			


			$data = $_POST;

			////


			if (isset($data['film_go']))
			{
				//результат чекбокса
				$genreplus = array_intersect($data, $genre);
				
					//кнопка нажата
					$err = array();
				if  ($data['fname']=='')
				{
					$err[] = 'Вы не ввели название фильма';
				}
				if (empty($genreplus)){
					$err[] = 'У вас пустая категория фильма';
				}
				if (!isset($_FILES)){
					$err[] = 'Вы не загрузили фильм';
				}
				if ($data['ffile']==''){
					$err[] = 'Вы не указали ссылку на фильм';
				}

				if(empty($err)){
					//ошибок нет
					//загрузка постера
				// проверяем, можно ли загружать изображение
						      	$pcheck = pcan_upload($_FILES['fpostes']);
						    
						      if($pcheck === true){
						        // загружаем изображение на сервер
						        $fposter = pmake_upload($_FILES['fpostes']);
						        $_SESSION['postup'] = $fposter;
						      }
						      else{
						        // выводим сообщение об ошибке
						       $err[] = "ошибка в загрузке постера фильма";  
						      }	
						      //загрузка кинца
						      // 	$fcheck = fcan_upload($_FILES['ffile']);
						    
						      // if($fcheck === true){
						      //   // загружаем изображение на сервер
						      //   $ffilm = fmake_upload($_FILES['ffile']);
						      //   $_SESSION['filmup'] = $ffilm;
						      // }
						      // else{
						      //   // выводим сообщение об ошибке
						      //   $err[] = "ошибка в загрузке файла" ;  
						      // }

						  

						      		$sql = ("INSERT INTO films SET fname='".$data['fname']."', fposter='".$fposter."',  fabout = '".$data['fabout']."', ffile = '".$data['ffile']."' ");
									$result = $pdo->prepare($sql);
									$result ->execute();

									$sql = ("SELECT * FROM films order by id DESC LIMIT 1");
									$result = $pdo->prepare($sql);
									$result ->execute();
									$fil = $result->fetchAll(PDO::FETCH_ASSOC);
									foreach ($fil as $fil) {
										$thisfilm = $fil['id'];
						      		}

						      		foreach ($genreplus as $key => $value) {
						      			$sql = ("INSERT INTO films_categories SET id_film='".$thisfilm."', id_cat='".$value."' ");
										$result = $pdo->prepare($sql);
										$result ->execute();					     					
						      		}?>

									<script>alert('Фильм добавлен')</script>
			
						      			

<?php
				}else{
					//есть ошибки
					echo array_shift($err);
				}

			}

			///добавление категории
			if (isset($data['cat_go']))
			{
				$sql = ("INSERT INTO categorys SET cname='".$data['new_cat']."'");
				$result = $pdo->prepare($sql);
				$result ->execute();
			}
?>
<script src="http://code.jquery.com/jquery-1.12.1.js"></script>

<?php

		}else{
				//выкинуть на 404 страницу
?>
				<script>
					location.replace("http://kin.os:83/index.php");
				</script>
<?php
			}
require_once "footer.php";
