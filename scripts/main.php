<?php
	include("config.php");
	include("query.php");
	include("functions.php");
	$param = null;
	$a = null;
	$Result = "";
	if (array_key_exists('nquery', $_GET) || array_key_exists('nquery', $_POST))
	{
		if (array_key_exists('nquery', $_GET))
		{
			$nQuery = $_GET['nquery'];
			if(array_key_exists('param', $_GET))
				$param = $_GET['param'];
			if(array_key_exists('paramL', $_GET))
				$paramL = $_GET['paramL']; 
			if(array_key_exists('paramC', $_GET))
				$paramC = $_GET['paramC']; 
			if(array_key_exists('paramI', $_GET))
				$paramI = $_GET['paramI'];
		}
		if (array_key_exists('nquery', $_POST))
		{
			$nQuery = $_POST['nquery'];
			if(array_key_exists('param', $_POST))
				$param = $_POST['param'];
			if(array_key_exists('paramL', $_POST))
				$paramL = $_POST['paramL']; 
			if(array_key_exists('paramC', $_POST))
				$paramC = $_POST['paramC']; 
			if(array_key_exists('paramI', $_POST))
				$paramI = $_POST['paramI']; 
        }
    }

    $year = explode("T", date("c"));
    $time = explode("+", $year[1]);
    $current_time = $year[0]." ".$time[0];
            
    $mysqli = new mysqli('localhost', $username, $password, $dbName);
    if (mysqli_connect_errno()) { echo("Не могу создать соединение"); exit(); }
    $mysqli->set_charset("utf8");

    if($nQuery < 50)
        switch($nQuery)
        {
            case 0:
                include("./version/versions.php");
                $project = [];	
                $project['main'] = getVersion(		$_main["name"], 		$_main["data"]);
                $project['php'] = getVersion(		$_php["name"], 			$_php["data"]);
                echo json_encode($project);
                break;
            case 1: // Возвращает информацию о текущем пользователе
            {
                request("SELECT * FROM signin WHERE id = %s", [$paramI]);
                break;
            }
            case 2: // Фиксирует вход на сайт нового пользователя
            {
                query("INSERT INTO signin VALUES(%s, %s, %s, %i, %s, %s)", $param);
                break;
            }
            case 3: // Обновление времени нахождения пользователя на сайте
            {
                query("UPDATE signin SET date = %s WHERE id = %s", $param);
                break;
            }
            case 4: // вход
                require_once("enter.php");
                break;
            case 5: // выход
                break;
            case 6: // автовход
                $checkKey = "";
                $rights = -1;
                $login = $paramL;
                if($result = query("SELECT checkkey FROM signin WHERE id = %s AND login = %s", [$paramI, $paramL]))
                    while ($row = $result->fetch_array(MYSQLI_NUM)) $checkKey = $row[0];
                if ($checkKey != "" && $checkKey == $paramC) 
                {
                    if($result = query("SELECT rights FROM roles WHERE id IN (SELECT role FROM registration WHERE login = %s)", [$paramL]))
                        while ($row = $result->fetch_array(MYSQLI_NUM)) $rights = (int)$row[0];
                    echo json_encode([$rights]);
                }
                else echo json_encode([$rights]); 
                break;
            case 7: // Очищает логин и ключ при выходе пользователя с сайта
                query("UPDATE signin SET checkkey = '', login = '' WHERE id = %s", [$paramC]);
                break;
            /* case 8: // Запуск скрипта регистрации
            {
                require_once("registration.php"); 
                break;
            } */
            case 9: // Проверка правильности введенного логина
            {
                $login = $paramL;
                $array = [];
                $j = 0;
                if($result = query("SELECT login FROM registration", []))
                    while ($row = $result->fetch_array(MYSQLI_NUM))
                        for ($i = 0;  $i < count($row); $i++)
                        {
                            $array[$j] = $row[$i];
                            $j++;
                        }
                echo checkL($array, $login);
                break;
            }
            
        }
    if($nQuery >= 40) // Требуется логин
    {
        $checkKey = "";
        $rights = -1;
        $out_rights = [];
        $login = $paramL;
        if($result = query("SELECT checkkey FROM signin WHERE id = %s AND login = %s", [$paramI, $paramL]))
            while ($row = $result->fetch_array(MYSQLI_NUM)) $checkKey = $row[0];
        if ($checkKey != "" && $checkKey == $paramC) 
        {
            if($result = query("SELECT rights FROM roles WHERE id IN (SELECT role FROM registration WHERE login = %s)", [$paramL]))
                while ($row = $result->fetch_array(MYSQLI_NUM)) $rights = (int)$row[0];
            $out_rights = recodeRights($rights);
            if($nQuery >= 40 && $nQuery < 50)
                switch($nQuery)
                {
                    case 40: // Запрос глобальных прав
                        request("SELECT rights FROM roles WHERE id IN (SELECT role FROM registration WHERE login = %s)", [$paramL]);
                        break;
                    case 41: // Запрос списка пользователей
                        request("SELECT login FROM registration", []);
                        break;
                    case 42: // Запрос списка таблиц
                        request("SELECT name_table, id FROM bind_template", []);//
                        break;
                }
            if($nQuery >= 50 && $nQuery < 100) // Работа с шаблонами и типами
                if($out_rights[0])
                    switch($nQuery)
                    {
                        case 50: // Запрос списка шаблонов вся информация
                            request("SELECT name, status, status_color, fields, rights FROM template", []);
                            break;   
                        case 51: // Запрос списка типов
                            request("SELECT name, _default, rights FROM type", []);
                            break;  
                        case 52: // Добавление шаблона
                            request("INSERT INTO template (name, status, status_color, fields) VALUES(%s, %s, %s, %s)", $param);
                            break;   
                        case 53: // Добавление типа
                            request("INSERT INTO type (name, _default) VALUES(%s, %s)", $param);
                            break;
                        case 54: // Изменение шаблона
                            request("UPDATE template SET status = %s, status_color = %s, fields = %s WHERE name = %s", $param);
                            break;
                        case 55: // Изменение типа
                            request("UPDATE type SET _default = %s WHERE name = %s", [$param[1], $param[0]]);
                            break;
                        case 56: // Удаление шаблона
                            break;
                        case 57: // Удаление типа
                            break;
                        case 58: // Запрос списка шаблонов
                            request("SELECT name, status FROM template", []);
                            break;  
                    }
            if($nQuery >= 100 && $nQuery < 150) // Работа с Таблицами
                if($out_rights[1])
                    switch($nQuery)
                    {
                        case 100: // Запрос списка таблиц
                            request("SELECT id, name_table, name_template, id_parent, id_parent_cell, info, rights, _default, status, person, terms FROM bind_template WHERE id IN (SELECT table_id FROM rights WHERE login = %s AND rights & 1)", [$paramL]);
                            break;   
                        case 101: // Добавление таблицы
                            if($result = query("SELECT fields FROM template WHERE name = %s", [$param[1]]))
                            {
                                $Result;
                                while ($row = $result->fetch_array(MYSQLI_NUM)) 
                                    $Result = $row;
                                $fields = json_decode($Result[0]);
                            }
                            $str = "";//PRIMARY KEY
                            $i = 0;
                            $l = count($fields);
                            foreach ($fields as $value)
                            {
                                if($i != 0) $str .= ", ";
                                switch($value->type)
                                {
                                    case "INT":
                                    case "DOUBLE":
                                        $str .= "f_$i ".$value->type."(11)";
                                        break;
                                    default:
                                        $str .= "f_$i  VARCHAR(2048)";
                                        break;
                                }
                                $i++;
                            }
                            query("INSERT INTO bind_template (name_table, name_template, id_parent, id_parent_cell, info, rights, _default, status, person, terms) VALUES(%s, %s, %i, %i, %s, %i, %i, %s, %s, %s)", $param);
                            $id =  $mysqli->insert_id;
                            query("CREATE TABLE table_$id ($str)", []);
                            break;   
                        case 102: // Изменение таблицы
                            request("UPDATE bind_template SET id_parent = %i, id_parent_cell = %i, info = %s, rights = %i, _default = %i, status = %s, person = %s, terms = %s WHERE id = %i", $param);
                            /* request("UPDATE rights SET table_name = %s, login = %s, rights = %i WHERE id = %i", $param); */
                            break;
                        case 103: // Удаление таблицы
                            break;
                    }
            if($nQuery >= 150 && $nQuery < 200) // Работа с Пользователями
                if($out_rights[2])
                    switch($nQuery)
                    {
                        case 150: // Запрос списка пользователей
                            request("SELECT login, role, name, mail FROM registration", []);
                            break;
                        case 151: // Запрос списка ролей
                            request("SELECT id, name, rights FROM roles", []);
                            break;
                        case 152: // Добавление пользователя
                            require_once("registration.php");
                            break;
                        case 153: // Добавление роли
                            request("INSERT INTO roles (name, rights) VALUES(%s, %i)", $param);
                            break;
                        case 154: // Изменение пользователя
                            request("UPDATE registration SET role = %i, name = %s, mail = %s WHERE login = %s", [$param[4], $param[1], $param[3], $param[0]]);
                            if($param[2] != "")
                            {
                                $sult = unique_md5();
                                $hash = myhash($param[2], $sult);
                                query("UPDATE password SET hash = %s WHERE login = %s", [$hash, $param[0]]);
                            }
                            break;
                        case 155: // Изменение роли
                            request("UPDATE roles SET name = %s, rights = %i WHERE id = %i", $param);
                            break;
                        case 156: // Удаление пользователя
                            break;
                        case 157: // Удаление роли
                            break;
                    }
            if($nQuery >= 200 && $nQuery < 250) // Работа с Правами
                if($out_rights[3])
                    switch($nQuery)
                    {
                        case 200: // Запрос списка прав
                            request("SELECT id, table_id, login, rights FROM rights", []);
                            break;   
                        case 201: // Добавление права
                            request("INSERT INTO rights (table_id, login, rights) VALUES(%i, %s, %i)", $param);
                            break;   
                        case 202: // Изменение права
                            request("UPDATE rights SET table_id = %i, login = %s, rights = %i WHERE id = %i", $param);
                            break;
                        case 203: // Удаление права
                            break;
                    }
            if($nQuery >= 250 && $nQuery < 300) // Работа с Событиями
                if($out_rights[4])
                    switch($nQuery)
                    {

                    }
        }
    }
    $mysqli->close();
    
    function request($_query, $param)
    {
        global $mysqli;
        $Result = [];
        if($result = query($_query, $param))
        {
            while ($row = $result->fetch_array(MYSQLI_NUM)) 
                $Result[] = $row;
            echo json_encode($Result);
        }
        else if ($result == 0) echo json_encode(["Index", $mysqli->insert_id]);
        else echo json_encode(["Error", $mysqli->error]);
    }
    function checkL($array, $login)
	{
		for ($i = 0;  $i < count($array); $i++)
			if($array[$i] == $login) return json_encode(["yes"]);
		return json_encode(["no"]);
    }
    function recodeRights($_rights)
    {
        $out = [];
        $rights = $_rights;
        $out[] = $rights & 1;       // Шаблоны и типы 
        $rights = $rights >> 1;
        $out[] = $rights & 1;       // Таблицы 
        $rights = $rights >> 1;
        $out[] = $rights & 1;       // Пользователи и роли  
        $rights = $rights >> 1;
        $out[] = $rights & 1;       // Права 
        $rights = $rights >> 1;
        $out[] = $rights & 1;       // События
        return $out;
    }
?>				