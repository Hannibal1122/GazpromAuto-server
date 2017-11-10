<?php 
	$_main = array(
		"name" => "main",
		"data" => array(
            "E:/Projects/GazpromAuto/src/app/editor-table/editor-table.component.css",
			"E:/Projects/GazpromAuto/src/app/editor-table/editor-table.component.html",
            "E:/Projects/GazpromAuto/src/app/editor-table/editor-table.component.ts",
			"E:/Projects/GazpromAuto/src/app/enter/enter.component.css",
			"E:/Projects/GazpromAuto/src/app/enter/enter.component.html",
            "E:/Projects/GazpromAuto/src/app/enter/enter.component.ts",
            "E:/Projects/GazpromAuto/src/app/home/home.component.css",
			"E:/Projects/GazpromAuto/src/app/home/home.component.html",
			"E:/Projects/GazpromAuto/src/app/home/home.component.ts",
			"E:/Projects/GazpromAuto/src/app/lib/query.service.ts",
			"E:/Projects/GazpromAuto/src/app/lib/cookies.service.ts",
			"E:/Projects/GazpromAuto/src/app/lib/functions.service.ts",
			"E:/Projects/GazpromAuto/src/app/main/main.component.css",
			"E:/Projects/GazpromAuto/src/app/main/main.component.html",
            "E:/Projects/GazpromAuto/src/app/main/main.component.ts",
            "E:/Projects/GazpromAuto/src/app/modalwindow/modalwindow.component.css",
			"E:/Projects/GazpromAuto/src/app/modalwindow/modalwindow.component.html",
            "E:/Projects/GazpromAuto/src/app/modalwindow/modalwindow.component.ts",
            "E:/Projects/GazpromAuto/src/app/modalwindow/modalwindow.module.ts",
            "E:/Projects/GazpromAuto/src/app/rights/rights.component.css",
			"E:/Projects/GazpromAuto/src/app/rights/rights.component.html",
            "E:/Projects/GazpromAuto/src/app/rights/rights.component.ts",
            "E:/Projects/GazpromAuto/src/app/template-and-type/template-and-type.component.css",
			"E:/Projects/GazpromAuto/src/app/template-and-type/template-and-type.component.html",
			"E:/Projects/GazpromAuto/src/app/template-and-type/template-and-type.component.ts",
            "E:/Projects/GazpromAuto/src/app/users-and-role/users-and-role.component.css",
			"E:/Projects/GazpromAuto/src/app/users-and-role/users-and-role.component.html",
            "E:/Projects/GazpromAuto/src/app/users-and-role/users-and-role.component.ts",
            "E:/Projects/GazpromAuto/src/app/view-table/view-table.component.css",
			"E:/Projects/GazpromAuto/src/app/view-table/view-table.component.html",
			"E:/Projects/GazpromAuto/src/app/view-table/view-table.component.ts",
			"E:/Projects/GazpromAuto/src/app/app.component.css",
			"E:/Projects/GazpromAuto/src/app/app.component.html",
			"E:/Projects/GazpromAuto/src/app/app.component.ts",
			"E:/Projects/GazpromAuto/src/app/app.module.ts"
		)
	);
	$_php = array(
		"name" => "php",
		"data" => array(
			"config.php",
			"enter.php",
			"functions.php",
			"main.php",
			"query.php",
			"registration.php",
			"version/versions.php"
		)
	);
	function getVersion($nameFile, $hash)
	{
		$v = 0; // индекс версии
		$str2 = ""; 
		$array = file("version/".$nameFile.'.ver'); // построчный разбор файла с хэш-функциями
		$time_str = explode(".", $array[0]); // разбор текущей версии
		$change_files = array(); // Измененные файлы

		for ($i = 0; $i < count($hash); $i++)
		{
			$hash_time = hash_file("md5", $hash[$i]); // хэш-фукнция файла
			if (isset($array[$i + 1]) && strripos($array[$i + 1], $hash_time) === false) 
			{
				array_push($change_files, $i);
				$v++;
			}
			$str2 .= "\n".$hash_time;
		}
		$time_str[2] = (int)$time_str[2] + $v;
		if ($time_str[2] >= 1000) // формирование версии
		{
			$time_str[2] = 0;
			$time_str[1] = (int)$time_str[1] + 1;
		}
		if ($time_str[1] >= 100) 
		{
			$time_str[1] = 0;
			$time_str[0] = (int)$time_str[0] + 1;
		}
		$str = $time_str[0].".".$time_str[1].".".$time_str[2]; // полная версия 
		for ($i = 0; $i < count($change_files); $i++ ) // Сохраняем в бэкап измененные файлы
		{
			$path_parts = pathinfo($hash[$change_files[$i]]);
			$bName = basename($path_parts["basename"], ".".$path_parts["extension"]);
			copy($hash[$change_files[$i]], "../backup/".$bName."_".$str.".".$path_parts["extension"]);
		}
		file_put_contents("version/".$nameFile.'.ver', $str.$str2);
		return $str;
	}
?> 