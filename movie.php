<?php 
	session_start();
?>
<?php 
	require_once "header.php";





$chosenfilm = htmlspecialchars($_GET['n']);

			$sql = ("SELECT f.id, f.fname, f.fposter, f.flikes, f.fdislikes, f.fabout, f.ffile, f.fraiting, f.fyear, f.fcountry FROM films f
					WHERE f.fname = '".$chosenfilm."' ");
    		$result = $pdo->prepare($sql);
    		$result ->execute();
    		$row = $result->fetchAll(PDO::FETCH_ASSOC);




    		foreach ($row as $row): 
                     // WHERE id = '".$_GET['id_arc']."'"

                $sql = ("SELECT * FROM vote WHERE id_user = '".$_SESSION['logged_id']."' and id_film = '".$row['id']."' ");
                $result = $pdo->prepare($sql);
                $result ->execute();
                $last_raiting = $result->fetchAll(PDO::FETCH_ASSOC);
                foreach ($last_raiting as $last_raiting) {
                    $last = $last_raiting['uraiting'];
                }


                $sql = ("SELECT count(*) FROM vote WHERE id_user = '".$_SESSION['logged_id']."' and id_film = '".$row['id']."' ");
                $result = $pdo->prepare($sql);
                $result ->execute();
                $count_vote = $result->fetch(); // кол-во проголосовавших 


                if (!isset($_SESSION['logged_id'])){
                    $unlog = 0;
                }else{
                    $unlog = 1;
                }

    			
    				$sql = ("SELECT count(*) FROM likes WHERE id_film = '".$row['id']."'");
		    		$result = $pdo->prepare($sql);
		    		$result ->execute();
		    		$likes = $result->fetch();
		    		$likes[0];



		    		$sql = ("SELECT count(*) FROM dislikes WHERE id_film = '".$row['id']."'");
		    		$result = $pdo->prepare($sql);
		    		$result ->execute();
		    		$dislikes = $result->fetch();
		    		$dislikes[0];    		
                if (isset($_SESSION['logged_id'])){
    				//проверка на лайк у пользователя
    				$sql = ("SELECT count(*) FROM likes WHERE id_user = '".$_SESSION['logged_id']."' and id_film = '".$row['id']."'");
    				$result = $pdo->prepare($sql);
    				$result ->execute();
    				$checklikes = $result->fetch();

   
    				//проверка на дизлайк
    				$sql = ("SELECT count(*) FROM dislikes WHERE id_user = '".$_SESSION['logged_id']."' and id_film = '".$row['id']."'");
    				$result = $pdo->prepare($sql);
    				$result ->execute();
    				$checkdislikes = $result->fetch();
    				
    				//проверка на посмотреть позже
    				$sql = ("SELECT count(*) FROM later WHERE id_user = '".$_SESSION['logged_id']."' and id_film = '".$row['id']."'");
    				$result = $pdo->prepare($sql);
    				$result ->execute();
    				$checklater = $result->fetch();
    				

                }
    			?>
    		 	<!-- скрытые строки, лучше не трогать -->
    			<input type="hidden" id="id_user" value="<?=$_SESSION['logged_id'];?>" />
    			<input type="hidden" id="id_film" value="<?=$row['id'];?>" /> 
          <input type="hidden" id ="answer" value = '7777' /> <!-- кому отвечают на коммент -->
                <input type="hidden" id="film_raiting" value = "<?=$row['fraiting'];?>" />
                <input type="hidden" id="count_vote" value  = "<?=$count_vote[0];?>" /><!--  голосовал/нет -->
                <input type="hidden" id="unlog" value  = "<?=$unlog;?>" /> <!--  зареган/нет -->
                <input type="hidden" id="last" value = "<?=$last;?>" />  <!-- прошлая оценка -->

<?php
                
    			

    			?>

    			
				<a href='movie.php?n=<?php echo $row ['fname']?>' id = 'go_film'><?php echo $row['fname']?></a><br>
                <?php
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
                <p><?php echo "Год: ".$row['fyear']?><p>
                <p><?php echo "Страна: ".$row['fcountry']?><p>
    			<p><?php echo "Описание: ".$row['fabout']?><p>
<?php            //если пользователь уже ставил лайк, то...

				if ($checklikes[0] > 0 || $checkdislikes[0] > 0){
?>					
					<p><?php echo "лайки " .$likes[0];?></p>
					<p><?php echo "дизлайки " .$dislikes[0];?></p>

<?php
				}
				//если пользователь не зареган
				if (!isset($_SESSION['logged_user'])){ 
?>                     
					<p><?php echo "лайки " .$likes[0];?></p>
					<p><?php echo "дизлайки " .$dislikes[0];?></p>
									
<?php
				}
				// если пользователь зарегестрирован и еще не ставил лайков, дизов
				if (isset($_SESSION['logged_user']) && $checklikes[0] == 0 && $checkdislikes[0] == 0){
				
?>
				
    			<a href="movie.php?n=<?php echo $row ['fname']?>" id = "go_like"><?php echo "лайки " .$likes[0];?></a><br>
    			<a href="index.php" id = "go_dislike"><?php echo "дизлайки " .$dislikes[0];?></a><br>	

<?php
}
				//ПРОВЕРКА LATER
				//зареган и уже поставил посмотреть позже
				if (isset($_SESSION['logged_user']) && $checklater[0] > 0 ){				
?>
				<p><?php echo "Вы решили посмотреть данный фильм позже";?></p>
<?php }
         //если зарегал и не ставил посмотреть позже
				if (isset($_SESSION['logged_user']) && $checklater[0] == 0){
?>
    			<a href="index.php" id = "go_later"><?php echo "Посмотреть позже ";?></a><br>
<?php
				}?>
    
                <div id="raiting_star">
                <div id="raiting">
                <div id="raiting_blank"></div> <!--блок пустых звезд-->
                <div id="raiting_hover"></div> <!--блок  звезд при наведении мышью-->
                <div id="raiting_votes"></div> <!--блок с итогами голосов -->
                </div>

                <?php 
                if (isset($last)): ?>
                    <div id="last_vote">Вы уже голосовали, ваша прошлая оценка: <?php echo $last;?></div>
                <?php 
                endif;
                ?>
                <div id="raiting_info"> <img src="../images/load.gif" /><h5>Рейтинг фильма: </h5></div>
                </div>

    <?php   endforeach;

    		echo "имя файла фильма: ".$row['ffile'];?>


<br><br><br>
   
<?php
//pagination///
  $num = 100;///тут указывается кол-во записей на странице
  $page = $_GET['page'];
  $sql = ("SELECT count(*) FROM comments comm 
               INNER JOIN users us ON us.id = comm.id_user
               INNER JOIN films fi ON fi.id = comm.id_movie
            WHERE fname = '".$chosenfilm."' ");
  $result = $pdo->prepare($sql);
  $result ->execute();
  $paginat = $result->fetch();
  $posts = $paginat[0];
  $total = (($posts - 1) / $num) + 1;
  $total = intval($total);
  $page = intval($page);
  if(empty($page) or $page < 0) $page = 1;
  if ($page > $total) $page = $total;
  $start = $page * $num - $num;?>
  <input id="num" type="hidden" value = "<?=$num?>">
  <input id="start" type="hidden" value = "<?=$start?>">
  <div class="comment_area">
<?php
////////////////////////////////////////////////
      $sql = ("SELECT comm.id, comm.id_movie, comm.id_user, comm.id_answer_user, comm.comment, us.uname, us.id, fi.fname FROM comments comm 
                   INNER JOIN users us ON us.id = comm.id_user
                   INNER JOIN films fi ON fi.id = comm.id_movie 
              WHERE fi.fname = '".$chosenfilm."' LIMIT $start, $num");

        $result = $pdo->prepare($sql);
        $result ->execute();
        $comments = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($comments as $comment):?>
          <div class="comment-item">
             <h3><?php echo $comment['uname']?></h3>
             <div class="msg_here"><?php echo $comment['comment'];?></div><br>
             <a id = "answer" href="<?php echo $comment['uname'];?>" onclick = "var answ_id = $(this).attr('href'); $('#answer').val(answ_id); $('#comm_text').text(answ_id + ','); return false;">Ответить</a>
         </div>
   </div>
  <?php endforeach;?>
<?php
 ///вывод пагинации///
    //нужны ли стрелки назад
    if ($page != 1) $pervpage = '<a href=movie.php?n='.$chosenfilm.'&page=1>Первая</a> | <a href=movie.php?n='.$chosenfilm.'&page='.($page-1).'>Предыдущая</a> | ';
    //нужны ли стрелки вперед
    if ($page != $total) $nextpage = ' | <a href=movie.php?n='.$chosenfilm.'&page='.($page + 1). '>Следующая</a> | <a href=movie.php?n='.$chosenfilm.'&page='.$total.'>Последняя</a>';


    if($page - 5 > 0) $page4left = '<a href=movie.php?n='.$chosenfilm.'&page='.($page-5).'>'.($page-5).'</a> | ';
    if($page - 4 > 0) $page4left = '<a href=movie.php?n='.$chosenfilm.'&page='.($page-4).'>'.($page-4).'</a> | ';
    if($page - 3 > 0) $page3left = '<a href=movie.php?n='.$chosenfilm.'&page='.($page-3).'>'.($page-3).'</a> | ';
    if($page - 2 > 0) $page2left = '<a href=movie.php?n='.$chosenfilm.'&page='.($page-2).'>'.($page-2).'</a> | ';
    if($page - 1 > 0) $page1left = '<a href=movie.php?n='.$chosenfilm.'&page='.($page-1).'>'.($page-1).'</a> | ';

    if($page + 5 <= $total) $page5right = ' | <a href=movie.php?n='.$chosenfilm.'&page='.($page+5).'>'.($page+5).'</a>';
    if($page + 4 <= $total) $page4right = ' | <a href=movie.php?n='.$chosenfilm.'&page='.($page+4).'>'.($page+4).'</a>';
    if($page + 3 <= $total) $page3right = ' | <a href=movie.php?n='.$chosenfilm.'&page='.($page+3).'>'.($page+3).'</a>';
    if($page + 2 <= $total) $page2right = ' | <a href=movie.php?n='.$chosenfilm.'&page='.($page+2).'>'.($page+2).'</a>';
    if($page + 1 <= $total) $page1right = ' | <a href=movie.php?n='.$chosenfilm.'&page='.($page+1).'>'.($page+1).'</a>';

///вывод
    if($total>1){
        echo "<div class = \"pstrnav\">";
        echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
        echo "</div>";
    }

 ////


?>
    
 <form action="" name ="comment_go">
     <textarea name="comm_text" id="comm_text" cols="20" rows="5" ></textarea><br>
     <button id = "go_comm" name = "go_comm">Отправить комментарий</button>
 </form>
<script src="http://code.jquery.com/jquery-1.12.1.js"></script>





<script>

 
// type - тип голоса. Лайк или дизлайк
// element - кнопка, по которой кликнули
$(document).ready(function() {


    $('#go_comm').click(function(){
        
        var comm_text = $('#comm_text').val();
        var user_comment = $('#id_user').val();
        var id_film = $('#id_film').val();
        var answer = $('#answer').val();
        var num = $('#num').val();
        var start = $('#start').val();
        $.ajax({
          type: "POST",
          url: "ajax/comment_go.php",

          data: {comm_text: comm_text,
                 user_comment: user_comment,
                 id_film: id_film,
                 answer: answer,
                 start: start,
                 num: num},
           success: function(data){
              if(data != 'Вы не ввели текст комментария' && data != 'Только зарегестрированнные пользователи могут оставлять комментарии'){
                $('.comment_area').html(data);
              }else{
                alert(data);
              }
            },
            error: function(){
              alert('ошибка');
            }

          });    


        return false; 
    });

    //raiting
    current_raiting = $('#film_raiting').val();
    total_reiting = 2; // итоговый ретинг
    id_user_db = $('#id_user').val();
    count_vote = $('#count_vote').val();
    unlog = $('#unlog').val();
    last = $('#last').val();



    var star_widht = current_raiting * 16.4 + Math.ceil(current_raiting);
    $('#raiting_votes').width(star_widht);
    //ставим текущий рейтинг к кинцу
    $('#raiting_info h5').append(current_raiting);


     //наведение
     $('#raiting').hover(function() {
      $('#raiting_votes, #raiting_hover').toggle();
      },
      function() {
      $('#raiting_votes, #raiting_hover').toggle();
});
     var margin_doc = $("#raiting").offset();
    $("#raiting").mousemove(function(e){
    var widht_votes = e.pageX - margin_doc.left;
    user_votes = Math.ceil(widht_votes/16.4);  
    // обратите внимание переменная  user_votes должна задаваться без var, т.к. в этом случае она будет глобальной и мы сможем к ней обратиться когда юзер кликнет по оценке.
    $('#raiting_hover').width(user_votes*16.4);

    });


id_arc = $('#id_film').val();
   
$('#raiting').click(function(){
  
    if (unlog == 1){
         var rate = current_raiting,
                us_vote = user_votes,
                avrange_vote = 0;

            if (current_raiting > 0){

                db_raiting = (((us_vote*1 + rate*1)/2));

            }else{
                db_raiting = (((us_vote*1 + rate*1)/1));
            }
        $('#raiting_info h5, #raiting_info img').toggle(); // скрываем текст и показываем иконку загрузки
        $.get( 
        "raiting.php",
        {id_arc: id_arc, user_votes: user_votes, db_raiting: db_raiting, id_user_db: id_user_db, count_vote: count_vote, last: last}, // id_arc - id статьи, user_votes - оценка юзера, котор. мы вычилили выше
        function(data){ // когда пришел ответ от сервера
            tot_rait = current_raiting;

            
            $("#raiting_info h5").html(data); // сюда вставить ответ от сервера


                // console.log($('#count_vote').val());
                // console.log(us_vote);
                // console.log(rate);
                // console.log(count_vote);

            $('#last_vote').text('Вы уже голосовали, ваша прошлая оценка: ' + user_votes);
            if (current_raiting > 0){
                $('#raiting_votes').width(((us_vote*1 + rate*1)/2) * 17);// у блока #raiting_votes 
                                     //установится новая ширина с учетом последнего голоса
            }else{
                $('#raiting_votes').width(((us_vote*1 + rate*1)/1) * 17);
            }
            $('#raiting_info h5, #raiting_info img').toggle(); // прячем иконку загрузки и показываем ответ, пришедш. от сервера
            
            }
               )
}else{
    alert('Вы должны войти в свой профиль');
}

});

///




    $('#go_like').click(function(){
    	var id_user = $('#id_user').val();
    	var id_film = $('#id_film').val();    
		$.ajax({
			type: "POST",
		 	url: "ajax/likes.php",
		 	data: {id_user: id_user,
		 		   id_film: id_film},
			 success: function(msg){ 

			  	location.reload();
			 
			  },
  			error: function(){
  				alert('ошибка');
  			}

  		}); 
  		return false;   	



	   	
    });
     
    $('#go_dislike').click(function(){
    	var id_user = $('#id_user').val();
    	var id_film = $('#id_film').val();
		$.ajax({
			type: "POST",
		 	url: "ajax/dislikes.php",
		 	data: {id_user: id_user,
		 		   id_film: id_film},
			 success: function(msg){
			    location.reload();
			  },
  			error: function(){
  				alert('ошибка');
  			}

  		});    	



		return false;   	
    });

    $('#go_later').click(function(){
    	var id_user = $('#id_user').val();
    	var id_film = $('#id_film').val();
		$.ajax({
			type: "POST",
		 	url: "ajax/later.php",
		 	data: {id_user: id_user,
		 		   id_film: id_film},
			 success: function(msg){
			    location.reload();
			  },
  			error: function(){
  				alert('ошибка');
  			}

  		});    	



		return false;   	
    });
     
});

function setVote(type, element){
    // получение данных из полей
    var id_user = $('#id_user').val();
    var id_film = $('#id_film').val();
    	$.post('http://kin.os:83/movie.php',id_user.serialize(),function(data){
    		//при успехе
			alert('success');
				
			});

    // $.ajax({
    //     // метод отправки 
    //     type: "POST",
    //     // путь до скрипта-обработчика
    //     url: "http://kin.os:83/movie.php",
    //     // какие данные будут переданы
    //     data: {
    //         'id_user': id_user, 
    //         'id_film': id_film,
    //         'type': type
    //     },
    //     // тип передачи данных
    //     dataType: "json",
    //     // действие, при ответе с сервера
    //     success: function(data){
    //         // в случае, когда пришло success. Отработало без ошибок
    //         if(data.result == 'success'){   
    //             // Выводим сообщение
    //             alert('Голос засчитан');
    //             // увеличим визуальный счетчик
    //             // var count = parseInt(element.find('b').html());
    //             // element.find('b').html(count+1);
    //         }else{
    //             // вывод сообщения об ошибке
    //             alert(data.msg);
    //         }
    //     }
    // });
}
</script>
<?php 

require_once "footer.php";
