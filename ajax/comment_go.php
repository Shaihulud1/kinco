<?php

require_once '../libs/db.php';

if(isset($_POST['comm_text'])){
	$comm_text = htmlspecialchars($_POST['comm_text']);
	$us_id = htmlspecialchars($_POST['user_comment']);
	$id_film = htmlspecialchars($_POST['id_film']);
	$answer = htmlspecialchars($_POST['answer']);
	$err = array();
	if($_POST['user_comment'] == ''){
		$err[] = 'Только зарегестрированнные пользователи могут оставлять комментарии';
	}
	if($_POST['comm_text'] == ''){
		$err[] = 'Вы не ввели текст комментария'; 
	}


	if (empty($err)){

		$sql = ("INSERT INTO comments SET id_movie = '".$id_film."', id_user = '".$us_id."', comment = '".$comm_text."'");
		$result = $pdo->prepare($sql);
		$result ->execute();

		      $sql = ("SELECT comm.id, comm.id_movie, comm.id_user, comm.id_answer_user, comm.comment, us.uname, us.id  FROM comments comm 
		               INNER JOIN users us ON us.id = comm.id_user");
		        $result = $pdo->prepare($sql);
		        $result ->execute();
		        $comments = $result->fetchAll(PDO::FETCH_ASSOC);
		        foreach ($comments as $comment):?>
		          <div class="comment-item">
		             <h3><?php echo $comment['uname']?></h3>
		             <div class="msg_here"><?php echo $comment['comment'];?></div><br>
		             <a id = "answer" href="">Ответить</a>
		         </div>
		  <?php endforeach;

	}else{
		echo array_shift($err);
	}
}



    

