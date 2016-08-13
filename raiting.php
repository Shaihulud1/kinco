<?php 
require_once "libs/db.php"; 
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
{


sleep(1); // для теста на локальном компе
echo "Рейтинг фильма: ".$_GET["db_raiting"];
 
    		$sql = ("SELECT count(*) FROM vote WHERE id_film = '".$_GET['id_arc']."' and id_user = '".$_GET['id_user_db']."' ");
    		$result = $pdo->prepare($sql);
    		$result ->execute();
    		$checkvote = $result->fetch();
    		if ($checkvote[0] != 0 ) {
    			$sql = ("UPDATE vote SET id_user='".$_GET['id_user_db']."', id_film='".$_GET['id_arc']."', uraiting='".$_GET['user_votes']."' WHERE id_film = '".$_GET['id_arc']."' and id_user = '".$_GET['id_user_db']."' ");
				$result = $pdo->prepare($sql);
				$result ->execute();

				$sql = ("UPDATE films SET fraiting = '".$_GET['db_raiting']."' WHERE id = '".$_GET['id_arc']."'");
				$result = $pdo->prepare($sql);
				$result ->execute();				
    		}else{


				$sql = ("INSERT INTO vote SET id_user='".$_GET['id_user_db']."', id_film='".$_GET['id_arc']."', uraiting='".$_GET['user_votes']."'");
				$result = $pdo->prepare($sql);
				$result ->execute();

				$sql = ("UPDATE films SET fraiting = '".$_GET['db_raiting']."' WHERE id = '".$_GET['id_arc']."'");
				$result = $pdo->prepare($sql);
				$result ->execute();		
			}	
}
?>
