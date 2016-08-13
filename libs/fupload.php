<?php
  function fcan_upload($file){
	// если имя пустое, значит файл не выбран
    if($file['name'] == '')
		return 'Вы не выбрали файл.';
	
	/* если размер файла 0, значит его не пропустили настройки 
	сервера из-за того, что он слишком большой */

	
	// разбиваем имя файла по точке и получаем массив
	$getMime = explode('.', $file['name']);
	// нас интересует последний элемент массива - расширение
	$mime = strtolower(end($getMime));
	// объявим массив допустимых расширений
	$types = array('mp4', 'avi', 'gif', 'bmp', 'jpeg');
	
	// если расширение не входит в список допустимых - return
	if(!in_array($mime, $types))
		return 'Недопустимый тип файла, файл может быть только со следующими расширениями: mp4, avi, gif, bmp, jpeg';
	
	return true;
  }
  
  function fmake_upload($file){	
	// формируем уникальное имя картинки: случайное число и name
		// разбиваем имя файла по точке и получаем массив
	$getMime = explode('.', $file['name']);
	// нас интересует последний элемент массива - расширение
	$mime = strtolower(end($getMime));

	$ftrance	= get_in_translate_to_en($_POST['fname'], $gost=false);

	$name = $ftrance.'.'.$mime;


	copy($file['tmp_name'], 'films/'.$name);


	return $name;

  }


    

