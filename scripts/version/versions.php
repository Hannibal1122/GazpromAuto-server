<?php 
	/* $_PATH = "E:/Work/GazpromAuto/"; */
	$_PATH = "E:/Projects/GazpromAuto/";
	$_main = array(
		"name" => "main",
		"data" => array(
            $_PATH."src/app/pages/editor-table/editor-table.component.css",
			$_PATH."src/app/pages/editor-table/editor-table.component.html",
            $_PATH."src/app/pages/editor-table/editor-table.component.ts",
            $_PATH."src/app/pages/editor-big-table/editor-big-table.component.css",
			$_PATH."src/app/pages/editor-big-table/editor-big-table.component.html",
            $_PATH."src/app/pages/editor-big-table/editor-big-table.component.ts",
			$_PATH."src/app/pages/enter/enter.component.css",
			$_PATH."src/app/pages/enter/enter.component.html",
            $_PATH."src/app/pages/enter/enter.component.ts",
            $_PATH."src/app/pages/events/events.component.css",
			$_PATH."src/app/pages/events/events.component.html",
            $_PATH."src/app/pages/events/events.component.ts",
            $_PATH."src/app/pages/home/home.component.css",
			$_PATH."src/app/pages/home/home.component.html",
			$_PATH."src/app/pages/home/home.component.ts",
			$_PATH."src/app/lib/query.service.ts",
			$_PATH."src/app/lib/cookies.service.ts",
			$_PATH."src/app/lib/functions.service.ts",
			$_PATH."src/app/main/main.component.css",
			$_PATH."src/app/main/main.component.html",
            $_PATH."src/app/main/main.component.ts",
            $_PATH."src/app/system/error-table/error-table.component.css",
			$_PATH."src/app/system/error-table/error-table.component.html",
            $_PATH."src/app/system/error-table/error-table.component.ts",
            $_PATH."src/app/system/modalwindow/modalwindow.component.css",
			$_PATH."src/app/system/modalwindow/modalwindow.component.html",
            $_PATH."src/app/system/modalwindow/modalwindow.component.ts",
            $_PATH."src/app/system/modalwindow/modalwindow.module.ts",
            $_PATH."src/app/system/datetimepicker/datetimepicker.component.css",
			$_PATH."src/app/system/datetimepicker/datetimepicker.component.html",
            $_PATH."src/app/system/datetimepicker/datetimepicker.component.ts",
            $_PATH."src/app/system/datetimepicker/datetimepicker.module.ts",
            $_PATH."src/app/system/file-uploader/file-uploader.component.css",
			$_PATH."src/app/system/file-uploader/file-uploader.component.html",
            $_PATH."src/app/system/file-uploader/file-uploader.component.ts",
            $_PATH."src/app/pages/rights/rights.component.css",
			$_PATH."src/app/pages/rights/rights.component.html",
            $_PATH."src/app/pages/rights/rights.component.ts",
            $_PATH."src/app/pages/tasks/tasks.component.css",
			$_PATH."src/app/pages/tasks/tasks.component.html",
            $_PATH."src/app/pages/tasks/tasks.component.ts",
            $_PATH."src/app/pages/tasks/task.component.css",
			$_PATH."src/app/pages/tasks/task.component.html",
            $_PATH."src/app/pages/tasks/task.component.ts",
            $_PATH."src/app/pages/template-and-type/template-and-type.component.css",
			$_PATH."src/app/pages/template-and-type/template-and-type.component.html",
            $_PATH."src/app/pages/template-and-type/template-and-type.component.ts",
            $_PATH."src/app/pages/type/type.component.css",
			$_PATH."src/app/pages/type/type.component.html",
            $_PATH."src/app/pages/type/type.component.ts",
            $_PATH."src/app/pages/users-and-role/users-and-role.component.css",
			$_PATH."src/app/pages/users-and-role/users-and-role.component.html",
            $_PATH."src/app/pages/users-and-role/users-and-role.component.ts",
            $_PATH."src/app/pages/view-table/view-table.component.css",
			$_PATH."src/app/pages/view-table/view-table.component.html",
			$_PATH."src/app/pages/view-table/view-table.component.ts",
			$_PATH."src/app/app.component.css",
			$_PATH."src/app/app.component.html",
			$_PATH."src/app/app.component.ts",
			$_PATH."src/app/app.module.ts"
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
			"exportToExcel.php",
			"importFromExcel.php",
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
        if(!file_exists("version/noncontrol.ver"))
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