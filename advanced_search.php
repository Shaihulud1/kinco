<?php 
	session_start();
?>
<?php 
require_once "header.php";
?>  <br><br><br>
    <form method="GET" name = "advanced_search">
        <input type="text" name = "fname" placeholder = "Название"><br>
        <input type="text" name = "fcat" placeholder = "Жанр"><br>
        <input type = "text" name ="fcountry" placeholder = "Страна"><br>
        <input type="text" name = "fyear" placeholder = "Год"><br>
        <input type="submit" name="advanced_go" value = "Найти">
    </form>
    <?php
$array1 = array("a" => "green", "red", "red");
$array2 = array("b" => "green", "yellow", "red");
$array3 = array("b" => "blue", "yellow", "red");
$result = array_intersect($array1, $array2,$array3);
// print_r($result);
?>

<?php
if (isset($_GET['advanced_go'])){
    $fname = htmlspecialchars($_GET['fname']);
    $fcat = htmlspecialchars($_GET['fcat']);
    $fcountry = htmlspecialchars($_GET['fcountry']);
    $fyear = htmlspecialchars($_GET['fyear']);
    
    $where_ar = array();

    if($fname != ''){
        $where_ar[] = "fi.fname LIKE '%$fname%'";
    }
    if($fcat != ''){
        //$where_ar[] ="ca.cname = '".$fcat."'";
        $where_ar[] ="ca.cname ='".$fcat."'";

    }
    if($fcountry != ''){
        $where_ar[] ="fi.fcountry ='".$fcountry."'";
    }
    if($fyear != ''){
        $where_ar[] ="fi.fyear = '".$fyear."'";
    }

    $final_where = implode(' AND ', $where_ar);

    //для пагинации..
        $sql = ("SELECT fi.fname, fi.fposter FROM films_categories fc 
               INNER JOIN films fi ON fc.id_film = fi.id
               INNER JOIN categorys ca ON fc.id_cat = ca.id 
               WHERE ".$final_where."" 
            );
        $result = $pdo->prepare($sql);
        $result ->execute();
        $zetta_row= $result->fetchAll(PDO::FETCH_ASSOC);
        $zet_row= array_map("unserialize", array_unique( array_map("serialize", $zetta_row) ));  
        $arr_count = count($zet_row);


        //pagination///
        $num = 20;///тут указывается кол-во записей на странице
        $page = $_GET['page'];
        $posts = $arr_count;
        $total = (($posts - 1) / $num) + 1;
        $total = intval($total);
        $page = intval($page);
        if(empty($page) or $page < 0) $page = 1;
        if ($page > $total) $page = $total;
        $start = $page * $num - $num;

    ////////////////////////////////////////////////

    $sql = ("SELECT fi.fname, fi.fposter FROM films_categories fc 
               INNER JOIN films fi ON fc.id_film = fi.id
               INNER JOIN categorys ca ON fc.id_cat = ca.id 
               WHERE ".$final_where." ORDER BY fi.id DESC LIMIT $start, $num" 
            );
        $result = $pdo->prepare($sql);
        $result ->execute();
        $betta_row= $result->fetchAll(PDO::FETCH_ASSOC);
        $row= array_map("unserialize", array_unique( array_map("serialize", $betta_row) ));   

    
    foreach ($row as $row):?>
    
        <br/><br/><br/>
        <a href='movie.php?n=<?php echo $row ['fname']?>'><?php echo $row['fname']?></a><br>
        <?php
        //вывод жанров
        $sql = ("SELECT fi.fname, ca.cname FROM films_categories fc
                    INNER JOIN films fi ON fc.id_film = fi.id
                    INNER JOIN categorys ca ON fc.id_cat = ca.id
                    WHERE fi.fname = '".$row['fname']."' AND fi.fname LIKE '%$searchit%'");
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
        if ($page != 1) $pervpage = '<a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page=1>Первая</a> | <a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page-1).'>Предыдущая</a> | ';
        //нужны ли стрелки вперед
        if ($page != $total) $nextpage = ' | <a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page + 1). '>Следующая</a> | <a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.$total.'>Последняя</a>';


        if($page - 5 > 0) $page4left = '<a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page-5).'>'.($page-5).'</a> | ';
        if($page - 4 > 0) $page4left = '<a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page-4).'>'.($page-4).'</a> | ';
        if($page - 3 > 0) $page3left = '<a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page-3).'>'.($page-3).'</a> | ';
        if($page - 2 > 0) $page2left = '<a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page-2).'>'.($page-2).'</a> | ';
        if($page - 1 > 0) $page1left = '<a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page-1).'>'.($page-1).'</a> | ';

        if($page + 5 <= $total) $page5right = ' | <a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page+5).'>'.($page+5).'</a>';
        if($page + 4 <= $total) $page4right = ' | <a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page+4).'>'.($page+4).'</a>';
        if($page + 3 <= $total) $page3right = ' | <a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page+3).'>'.($page+3).'</a>';
        if($page + 2 <= $total) $page2right = ' | <a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page+2).'>'.($page+2).'</a>';
        if($page + 1 <= $total) $page1right = ' | <a href=advanced_search.php?fname='.$_GET['fname'].'&fcat='.$_GET['fcat'].'&fcountry='.$_GET['fcountry'].'&fyear='.$_GET['fyear'].'&advanced_go='.$_GET['advanced_go'].'&page='.($page+1).'>'.($page+1).'</a>';

    ///вывод
        if($total>1){
            echo "<div class = \"pstrnav\">";
            echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
            echo "</div>";
        }

     ////

}



require_once "footer.php";
