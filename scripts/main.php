<?php
	include("config.php");
	include("query.php");
    include("functions.php");
    $excelNumber = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
    $countExcelNumber = count($excelNumber);
	$param = null;
	$a = null;
    $Result = "";
    $paramL = $paramC = $paramI = null;
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

    if($nQuery == -1) // Установка
    {
        $checkTable = false;
        $checkLogin = false;
        if($result = query("SHOW TABLES LIKE 'registration'", []))
            while ($row = $result->fetch_array(MYSQLI_NUM)) $checkTable = true;
        if(!$checkTable) 
        { 
            $sql = file_get_contents("../gazprom_auto.sql");
            $mysqli->multi_query($sql);
        }
        if($result = query("SELECT login FROM registration WHERE login = 'admin'", []))
            while ($row = $result->fetch_array(MYSQLI_NUM)) $checkLogin = true;
        if(!$checkLogin) 
        { 
            query("INSERT INTO registration VALUES(%s, %s, %i, %s, %s)", ["admin", "mwork92@gmail.com", "1", "@DATE@", "admin"]);
            query("INSERT INTO password VALUES(%s, %s)", ["admin", "$2a$10$644bb3233e1ff251b4b4eumdZjoiZjWjFLyol.Ad7uUoNWlWCpz.u"]);
        }
        exit();
    }
    //file_get_contents
    if($nQuery < 50)
        switch($nQuery)
        {
            case 0: // Запрос версии
                include("./version/versions.php");
                $project = [];	
                $project['main'] = getVersion(		$_main["name"], 		$_main["data"]);
                $project['php'] = getVersion(		$_php["name"], 			$_php["data"]);
                echo json_encode($project);
                break;
            case 1: // Возвращает информацию о текущем пользователе
                request("SELECT * FROM signin WHERE id = %s", [$paramI]);
                break;
            case 2: // Фиксирует вход на сайт нового пользователя
                query("INSERT INTO signin VALUES(%s, %s, %s, %i, %s, %s)", $param);
                break;
            case 3: // Обновление времени нахождения пользователя на сайте
                query("UPDATE signin SET date = %s WHERE id = %s", $param);
                break;
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
  
            case 9: // Проверка правильности введенного логина
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
            $out_rights = recodeRights($rights, 8);
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
                        request("SELECT name_table, id FROM table_big", []);//
                        break;
                    case 43: // Запрос списка таблиц
                        request("SELECT name_table, id FROM table_initialization", []);
                        break;
                    case 44: // Количество задач
                        request("SELECT COUNT(*) FROM tasks WHERE responsible = %s AND status != 2", [$paramL]);
                        break;
                    case 45:
                        /* $param = ["тест", "", "{}", "[]", "2017-12-08 13:05:08", "2017-12-08 13:05:08", 1, "", "", ""];
                        $array1 = ["admin", "admin2", "admin3"];
                        for($i = 0; $i < 100000; $i++)
                        {
                            $param[7] = $array1[random_int(0, 2)];
                            $param[8] = $array1[random_int(0, 2)]; 
                            $param[9] = [$array1[random_int(0, 2)]];
                            $param[9] = json_encode($param[9]);
                            query("INSERT INTO tasks(name, info, file_list, check_list, date_begin, dead_line, no_dead_line, director, responsible, observer, status) VALUES(%s, %s, %s, %s, %s, %s, %i, %s, %s, %s, 0)", $param);
                        } */
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
                            $fields = [];
                            $newFields = json_decode($param[2]);
                            $changesAdd = [];
                            print_r($param);
                            if($result = query("SELECT fields FROM template WHERE name = %s", [$param[3]]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $fields = json_decode($row[0]);// = $row;//
                            $c1 = count($newFields);
                            $c2 = count($fields);
                            for($i = 0; $i < $c1; $i++)
                            {
                                for($j = 0; $j < $c2; $j++)
                                    if($newFields[$i]->name == $fields[$j]->name) break;
                                if($j != $c2) $newFields[$i]->old_position = $j;
                            }
                            request("UPDATE template SET status = %s, status_color = %s, fields = %s WHERE name = %s", $param);
                            if($result = query("SELECT id, fields FROM table_tree_big WHERE template = %s", [$param[3]]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) 
                                {
                                    $old_fields = json_decode($row[1]);
                                    $new_fields = [];
                                    for($i = 0; $i < $c1; $i++)
                                        if(property_exists($newFields[$i],"old_position")) $new_fields[$i] = $old_fields[$newFields[$i]->old_position];
                                        else // В зависимости от типа
                                            switch($newFields[$i]->type) 
                                            {
                                                case "INT":
                                                case "DOUBLE":
                                                    $new_fields[$i] = 0;
                                                    break;
                                                default:
                                                    $new_fields[$i] = "";
                                                    break;
                                            }
                                    query("UPDATE table_tree_big SET fields = %s WHERE id = %i", [json_encode($new_fields), $row[0]]);
                                }
                            break;
                        case 55: // Изменение типа
                            request("UPDATE type SET _default = %s WHERE name = %s", [$param[1], $param[0]]);
                            break;
                        case 56: // Удаление шаблона
                            query("DELETE FROM template WHERE name = %s", $param); 
                            break;
                        case 57: // Удаление типа
                            query("DELETE FROM type WHERE name = %s", $param); 
                            break;
                        case 58: // Запрос списка шаблонов
                            request("SELECT name, status FROM template", []);
                            break;  
                        case 59: // Запрос списка таблиц
                            request("SELECT id, name_table, name_template, info FROM table_initialization WHERE id IN (SELECT id_table FROM rights_template WHERE login = %s AND rights & 1)", [$paramL]);
                            break; 
                        case 60: // Добавление таблицы
                            if($result = query("SELECT fields FROM template WHERE name = %s", [$param[1]]))
                            {
                                $Result;
                                while ($row = $result->fetch_array(MYSQLI_NUM)) 
                                    $Result = $row;
                                $fields = json_decode($Result[0]);
                            }
                            $str = "id int NOT NULL AUTO_INCREMENT,";
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
                                        $str .= "f_$i  VARCHAR(256)";
                                        break;
                                }
                                $i++;
                            }
                            $str .= ", PRIMARY KEY (id)";
                            query("INSERT INTO table_initialization (name_table, name_template, info) VALUES(%s, %s, %s)", $param);
                            $id =  $mysqli->insert_id;
                            query("CREATE TABLE table_init_$id ($str)", []);
                            query("INSERT INTO rights_template (id_table, login, rights) VALUES(%i, %s, %i)", [$id, $paramL, 15]);
                            break;   
                        case 61: // Изменение таблицы
                            $id = (int)$param[1];
                            if(!checkRightsForTableTemplate(1, $paramL, $id)) { break; }
                            request("UPDATE table_initialization SET info = %s WHERE id = %i", $param);
                            break;
                        case 62: // Удаление таблицы
                            $id = (int)$param[0];
                            if(!checkRightsForTableTemplate(2, $paramL, $id)) { break; }
                            query("DROP TABLE table_init_$id", []);
                            query("DELETE FROM table_initialization WHERE id = %i", [$id]); 
                            break;
                        case 63: // Загрузка таблицы
                            $id = (int)($param[0]);
                            $Head = [];
                            $Template = [];
                            $Table = [];
                            $Type = [];
                            $Rights = getRightsForTable("rights_template", $paramL, $id);
                            if($Rights[0] == 0) { echo json_encode([-1]); break; }

                            if($result = query("SELECT * FROM table_init_$id", []))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $Table[] = $row;
                            if($result = query("SELECT * FROM table_initialization WHERE id = %i", [$id]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $Head = $row;
                            if($result = query("SELECT * FROM template WHERE name = %s", [$Head[2]]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $Template = $row;
                            $types = json_decode($Template[3]);
                            $c = count($types);
                            $value = [];
                            $col_name = "";
                            $k = 0;
                            for($i = 0; $i < $c; $i++)
                                if($types[$i]->type != "INT" && $types[$i]->type != "VARCHAR" && $types[$i]->type != "DOUBLE")
                                {
                                    if($k++ > 0) $col_name .= ",";
                                    $value[] = $types[$i]->type;
                                    $col_name .= "%s";
                                }
                            if($col_name != "")
                            if($result = query("SELECT * FROM type WHERE name IN ($col_name)", $value))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $Type[$row[0]] = $row;
                            echo json_encode(["head" => $Head, "data" => $Table, "template" => $Template, "type" => $Type, "rights" => $Rights]);
                            break;
                        case 64: // Добавление строк в таблицу
                            $id = (int)($param[0]);
                            if(!checkRightsForTableTemplate(1, $paramL, $id)) { break; }
                            $value = "";
                            $col_name = "";
                            $i = 0;
                            if($result = query("SHOW COLUMNS FROM table_init_$id", []))
                                while($row = $result->fetch_array(MYSQLI_NUM)) 
                                {
                                    if($i > 1) 
                                    {
                                        $value .= ",";
                                        $col_name .= ",";
                                    }
                                    if($i > 0) 
                                    {
                                        $value .= "''";
                                        $col_name .= $row[0];
                                    }
                                    $i++;
                                }
                            for($i = 0; $i < (int)$param[1]; $i++)
                                query("INSERT INTO table_init_$id ($col_name) VALUES($value)", []);
                            break;
                        case 65: // Обновление таблицы
                            $id = (int)($param[0]);
                            $col_name = "";
                            $i = 0;
                            if(!checkRightsForTableTemplate(1, $paramL, $id)) { break; }
                            if($result = query("SHOW COLUMNS FROM table_init_$id", []))
                                while($row = $result->fetch_array(MYSQLI_NUM)) 
                                {
                                    if($i > 1) $col_name .= ",";
                                    if($i > 0) $col_name .= $row[0]." = ".(strripos($row[1], "int") === false ? "%s" : "%i");
                                    $i++;
                                }
                            query("UPDATE table_init_$id SET $col_name WHERE id = %i", $param[2]);
                            break;
                        case 66: // Запрос списка шаблонов группы вся информация
                            request("SELECT name, hierarchy, rights FROM big_template", []);
                            break;
                        case 67: // Добавление шаблона группы
                            request("INSERT INTO big_template (name, hierarchy) VALUES(%s, %s)", $param);
                            break;
                        case 68: // Изменение шаблона группы
                            request("UPDATE big_template SET hierarchy = %s WHERE name = %s", $param);
                            break;
                        case 69: // Удаление записей из таблицы
                            $id = (int)($param[0]);
                            if(!checkRightsForTableTemplate(2, $paramL, $id)) { break; };
                            query("DELETE FROM table_init_$id WHERE id = %i", [$param[1]]);
                            break;
                        case 70:
                            query("DELETE FROM big_template WHERE name = %s", $param); 
                            break;
                    }
            if($nQuery >= 100 && $nQuery < 150) // Работа с Данными
                if($out_rights[1])
                    switch($nQuery)
                    {
                        case 100: // Запрос списка таблиц
                            request("SELECT id, name_table, name_template, info FROM table_big WHERE id IN (SELECT id_table FROM rights WHERE login = %s AND rights & 1)", [$paramL]);
                            break;   
                        case 101: // Добавление таблицы
                            query("INSERT INTO table_big (name_table, name_template, info) VALUES(%s, %s, %s)", $param);
                            $id =  $mysqli->insert_id;
                            query("INSERT INTO rights (id_table, login, rights) VALUES(%i, %s, %i)", [$id, $paramL, 15]);
                            break;   
                        /* case 102: // Изменение таблицы
                            $id = (int)($param[0]);
                            if(!checkRightsForTable(1, $paramL, $id)) { break; };
                            request("UPDATE table_big SET info = %s WHERE id = %i", $param);
                            break; */
                        case 103: // Удаление таблицы
                            $id = (int)($param[0]);
                            if(!checkRightsForTable(2, $paramL, $id)) { break; };
                            query("DELETE FROM table_big WHERE id = %i", [$id]);
                            query("DELETE FROM table_tree_big WHERE id_table = %i", [$id]);
                            query("DELETE FROM rights WHERE id_table = %i", [$id]);
                            break;
                        case 104: // Загрузка таблицы
                            $id = (int)($param[0]);
                            $Head = [];
                            $Table = [];
                            $OutTable = new stdClass();
                            $Type = [];
                            $Rights = getRightsForTable("rights", $paramL, $id);
                            if($Rights[0] == 0) { echo json_encode([-1]); break; }
                            /************************************************ */
                            if($result = query("SELECT * FROM table_tree_big WHERE id_table = %i ORDER by parent", [$id]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $Table[] = $row;
                            $OutTable->{'0'} = (object) array("children" => new stdClass());
                            getAllChildren($Table, 0, $OutTable->{'0'});
                            /************************************************ */
                            if($result = query("SELECT * FROM table_big WHERE id = %i", [$id]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $Head = $row;
                            if($result = query("SELECT hierarchy FROM big_template WHERE name = %s", [$Head[2]]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $hierarchy = json_decode($row[0]);
                            $fields = [];
                            $templates = [];
                            $str = "";
                            $c = count($hierarchy);
                            for($i = 0; $i < $c; $i++)
                            {
                                if($i != 0) $str .= ",";
                                $str .= "'".$hierarchy[$i]."'";
                            }
                            if($result = query("SELECT fields, name FROM template WHERE name IN ($str) ORDER BY FIELD(name, $str)", []))
                                while ($row = $result->fetch_array(MYSQLI_NUM)) 
                                {
                                    $fields[] = json_decode($row[0]);
                                    $templates[] = $row[1];
                                }
                            $_value = [];
                            $col_name = "";
                            $j = 0;
                            for($i = 0; $i < $c; $i++)
                                foreach ($fields[$i] as $value)
                                {
                                    if($value->type != "INT" && $value->type != "VARCHAR" && $value->type != "DOUBLE")
                                    {
                                        if($j++ > 0) $col_name .= ",";
                                        $_value[] = $value->type;
                                        $col_name .= "%s";
                                    }
                                }
                            if($col_name != "")
                                if($result = query("SELECT * FROM type WHERE name IN ($col_name)", $_value))
                                    while($row = $result->fetch_array(MYSQLI_NUM)) $Type[$row[0]] = $row;
                            echo json_encode(["head" => $fields, 
                                                "data" => $OutTable, 
                                                "type" => $Type, 
                                                "rights" => $Rights, 
                                                "templates" => $templates,
                                                "name" => $Head[1]]);
                            break;
                        case 105: // Добавление строк в таблицу
                            $id = (int)($param[0]);
                            if(!checkRightsForTable(1, $paramL, $id)) { break; };
                            query("INSERT INTO table_tree_big (id_table, template, parent, fields) VALUES(%i, %s, %i, %s)", $param);
                            break;
                        case 106: // Обновление таблицы
                            $id = (int)($param[0]);
                            if(!checkRightsForTable(1, $paramL, $id)) { break; };
                            query("UPDATE table_tree_big SET fields = %s WHERE id = %i AND id_table = %i", [$param[1], $param[2], $id]);
                            break;
                        case 107: // Запрос списка шаблонов группы
                            request("SELECT name FROM big_template", []);
                            break;
                        case 108: // Поиск таблиц инициализации по имени поля
                            $id_table = -1;
                            $type_table = -1;
                            if($result = query("SELECT id, name_template FROM table_initialization WHERE name_table = %s", [$param[0]]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) 
                                {
                                    $id_table = $row[0];
                                    $type_table = $row[1];
                                }
                            if($id_table != -1 && $type_table == $param[1])
                                echo json_encode([$id_table]);//request("SELECT * FROM table_init_$id_table", []);
                            else echo json_encode(["empty"]);
                            break;
                        case 109: // Удаление записей из таблицы
                            $id = (int)($param[0]);
                            if(!checkRightsForTable(2, $paramL, $id)) { break; };
                            $out = (int)$param[1];
                            getAllChildrenForRemove((int)$param[1]);
                            query("DELETE FROM table_tree_big WHERE id IN ($out) AND id_table = %i", [$id]);
                            break;
                        case 110: // Автоматическое заполнение таблицы
                            $id = (int)($param[0]);
                            $id_init_table = (int)$param[3];
                            if(!checkRightsForTable(1, $paramL, $id)) { break; };
                            
                            $out = "-1";
                            getAllChildrenForRemove((int)$param[2]);
                            query("DELETE FROM table_tree_big WHERE id IN ($out) AND id_table = %i", [$id]);

                            if($result = query("SELECT * FROM table_init_$id_init_table", []))
                                while($row = $result->fetch_array(MYSQLI_NUM)) 
                                {
                                    array_shift($row);
                                    query("INSERT INTO table_tree_big (id_table, template, parent, fields) VALUES(%i, %s, %i, %s)", [$id, $param[1], $param[2], json_encode($row)]);
                                }
                            /* echo "template = ".$param[1]."\n";
                            echo "parent = ".$param[2]."\n"; */
                            break;
                        case 111: // Экспорт таблицы
                            require_once './exportToExcel.php';
                            break;
                        case 112: // Загрузка файлов на сервер
                            echo loadFile(10, ['xlsx']);
                            break;
                        case 113: // Удаление временных файлов с сервера
                            unlink("../tmp/".$param[0]); 
                            break;
                        case 114: // Импорт таблицы с проверкой на права
                            require_once './importFromExcel.php';
                            break;
                        case 115: // Получение ссылки на таблицу по id
                            $id = (int)($param[0]);
                            $Rights = getRightsForTable("rights", $paramL, $id);
                            $out = false;
                            if($Rights[0] == 0) { echo json_encode([-1]); break; }
                            if($result = query("SELECT name_table FROM table_big WHERE id = %i", [$id]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) { echo json_encode([$row[0]]); $out = true; }
                            if(!$out) echo json_encode([""]);
                            break;
                    }
            if($nQuery >= 150 && $nQuery < 200) // Работа с Пользователями
                if($out_rights[2])
                    switch($nQuery)
                    {
                        case 150: // Запрос списка пользователей
                            //request("SELECT login, role, name, mail FROM registration", []);
                            getAllNameForLogin();
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
                            request("SELECT id, id_table, login, rights FROM rights", []);
                            break;   
                        case 201: // Добавление права
                            request("INSERT INTO rights (id_table, login, rights) VALUES(%i, %s, %i)", $param);
                            break;   
                        case 202: // Изменение права
                            request("UPDATE rights SET id_table = %i, login = %s, rights = %i WHERE id = %i", $param);
                            break;
                        case 203: // Удаление права
                            break;
                        case 204: // Запрос списка прав на таблицы инициализации
                            request("SELECT id, id_table, login, rights FROM rights_template", []);
                            break;   
                        case 205: // Добавление права на таблицы инициализации
                            request("INSERT INTO rights_template (id_table, login, rights) VALUES(%i, %s, %i)", $param);
                            break;   
                        case 206: // Изменение права на таблицы инициализации
                            request("UPDATE rights_template SET id_table = %i, login = %s, rights = %i WHERE id = %i", $param);
                            break;
                        case 207: // Список всех логинов с фамилией
                            getAllNameForLogin();
                            break;
                    }
            if($nQuery >= 250 && $nQuery < 300) // Работа с Событиями
                if($out_rights[4])
                    switch($nQuery)
                    {

                    }
            if($nQuery >= 300 && $nQuery < 350) // Работа с Задачами
                if($out_rights[6])
                    switch($nQuery)
                    {
                        case 300: // Запрос списка задач по логину все три типа
                            switch($param[0])
                            {
                                case "responsible":
                                    request("SELECT id, name, director, responsible, date_begin, dead_line, no_dead_line, status FROM tasks WHERE responsible = %s", [$paramL]);
                                    break;
                                case "director":
                                    request("SELECT id, name, director, responsible, date_begin, dead_line, no_dead_line, status FROM tasks WHERE director = %s", [$paramL]);
                                    break;
                                case "observer":
                                    request("SELECT id, name, director, responsible, date_begin, dead_line, no_dead_line, status FROM tasks WHERE observer LIKE '%\"$paramL\"%'", []);
                                    break;
                            }
                            break;
                        case 301: // Добавить новую задачу
                            query("INSERT INTO tasks(name, info, file_list, check_list, date_begin, dead_line, no_dead_line, responsible, observer, status) VALUES(%s, %s, %s, %s, %s, %s, %i, %s, %s, %i)", $param);
                            $insertID = $mysqli->insert_id;
                            query("UPDATE tasks SET director = %s WHERE id = %i", [$paramL, $insertID]);
                            echo json_encode(["Index", $insertID]);
                            break;
                        case 302: // Добавить связь с задачами по логинам
                            break;
                        case 303: // Изменить существующую задачу (проверка на роль)
                            query("UPDATE tasks SET info = %s, check_list = %s, dead_line = %s, no_dead_line = %i, status = %i WHERE id = %i AND director = %s", [$param[1], $param[3], $param[4], $param[5], $param[6], $param[7], $paramL]);
                            break;
                        case 304: // Изменить статус задачи (проверка на роль)
                            query("UPDATE tasks SET status = %i WHERE id = %i AND (director = %s OR responsible = %s)", [$param[1], $param[0], $paramL, $paramL]);
                            break;
                        case 305: // Изменить чек-лист
                            query("UPDATE tasks SET check_list = %s WHERE id = %i AND (director = %s OR responsible = %s)", [$param[1], $param[0], $paramL, $paramL]);
                            break;
                        case 306: // Количество задач по роли
                            $out = [];
                            if($result = query("SELECT COUNT(*) FROM tasks WHERE responsible = %s AND status != 2", [$paramL]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $out["responsible"] = $row[0];
                            if($result = query("SELECT COUNT(*) FROM tasks WHERE director = %s AND status != 2", [$paramL]))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $out["director"] = $row[0];
                            if($result = query("SELECT COUNT(*) FROM tasks WHERE observer LIKE '%\"$paramL\"%' AND status != 2", []))
                                while($row = $result->fetch_array(MYSQLI_NUM)) $out["observer"] = $row[0];
                            echo json_encode($out);
                            break;
                        case 307:
                            break;
                        case 308: // Список всех логинов с фамилией
                            getAllNameForLogin();
                            break;
                        case 309: // Запрос списка проектов доступных по логину на просмотр
                            request("SELECT id, name_table FROM table_big WHERE id IN (SELECT id_table FROM rights WHERE login = %s AND rights & 1)", [$paramL]);
                            break;
                        case 310: // Загрузка задачи по id проверка на роль
                            $Result = [];
                            $Result["task"] = [];    
                            $Result["tasks_people"] = [];
                            $errorRights = true;  
                            $nameFields = getTemplatePosition("Имя пользователя");
                            if($result = query("SELECT director, responsible, observer FROM tasks WHERE id = %i", $param))
                                while ($row = $result->fetch_array(MYSQLI_NUM)) 
                                {
                                    $logins = json_decode($row[2]);
                                    array_unshift($logins, $row[0], $row[1]);
                                    $c = count($logins);
                                    for($i = 0; $i < $c; $i++)
                                    {
                                        if($logins[$i] == $paramL) $errorRights = false;
                                        $Result["tasks_people"][] = [getNameForLogin($logins[$i], $nameFields), ($i == 0 ? "director" : ($i == 1 ? "responsible" : "observer")), 0, $logins[$i]];
                                    }
                                }
                            if(!$errorRights)
                            {
                                if($result = query("SELECT id, name, info, file_list, check_list, date_begin, dead_line, no_dead_line, status FROM tasks WHERE id = %i", $param))
                                    while ($row = $result->fetch_array(MYSQLI_NUM)) $Result["task"] = $row;
                                echo json_encode($Result);
                            }
                            break;
                        case 311: // Установка прав на проект с данными
                            $id_table = (int)$param[0];
                            if($id_table == -1) break;
                            $id_right = -1;
                            if($result = query("SELECT id FROM rights WHERE id_table = %i AND login = %s", [$id_table, $param[1]]))
                                while ($row = $result->fetch_array(MYSQLI_NUM)) $id_right = $row[0];
                            if($id_right == -1)
                                request("INSERT INTO rights (id_table, login, rights) VALUES(%i, %s, %i)", [$id_table, $param[1], 3]); // Добавление права на просмотр и редактирование
                            else request("UPDATE rights SET id_table = %i, login = %s, rights = %i WHERE id = %i", [$id_table, $param[1], 3, $id_right]); // Обновление права на просмотр и редактирование
                            $c = count($param[2]);
                            for($i = 0; $i < $c; $i++)
                            {
                                $id_right = -1;
                                if($result = query("SELECT id FROM rights WHERE id_table = %i AND login = %s", [$id_table, $param[2][$i]]))
                                    while ($row = $result->fetch_array(MYSQLI_NUM)) $id_right = $row[0];
                                if($id_right == -1)
                                    request("INSERT INTO rights (id_table, login, rights) VALUES(%i, %s, %i)", [$id_table, $param[2][$i], 1]); // Добавление права на просмотр
                                else request("UPDATE rights SET id_table = %i, login = %s, rights = %i WHERE id = %i", [$id_table, $param[2][$i], 1, $id_right]); // Обновление права на просмотр
                            }
                            break;
                        case 312: // Загрузка файлов на сервер
                            echo loadFile(10, ['gif','jpeg','png','jpg']);
                            break;
                        case 313: // Удаление файлов из временной папки
                            unlink("../tmp/".$param[0]); 
                            break;
                        case 314: // Загрузка списка(ссылки) файлов на клиента
                            $idTask = $param[0];
                            $listFile = json_decode($param[1]);
                            if (!file_exists("../files/tasks/$idTask")) mkdir("../files/tasks/$idTask", 0700);
                            $c = count($listFile);
                            for($i = 0; $i < $c; $i++)
                                rename("../tmp/".$listFile[$i], "../files/tasks/$idTask/".$listFile[$i]); 
                            break;
                    }
        }
    }
    $mysqli->close();
    
    function getAllNameForLogin() // Получает список всех логинов с именами, если имеется
    {
        $listLogin = []; // Выходной список логинов с ролями и именами
        $in = []; // временный массив
        
        if($result = query("SELECT login, role FROM registration", []))
            while($row = $result->fetch_array(MYSQLI_NUM)) 
                $listLogin[$row[0]] = (object) array("role" => $row[1], "name" => "");
        $nameFields = getTemplatePosition("Имя пользователя");
        if($result = query("SELECT fields FROM table_tree_big WHERE template = 'Имя пользователя'", []))
            while($row = $result->fetch_array(MYSQLI_NUM))
            {
                $in = json_decode($row[0]);
                if(array_key_exists($in[$nameFields["Логин"]], $listLogin))
                {
                    $name = $in[$nameFields["Имя"]];
                    $name2 = $in[$nameFields["Отчество"]];
                    $listLogin[$in[$nameFields["Логин"]]]->name = $in[$nameFields["Фамилия"]]." ".mb_substr($name, 0, 1, "UTF-8").". ".mb_substr($name2, 0, 1, "UTF-8").".";
                    if(array_key_exists("Почта", $nameFields)) 
                        $listLogin[$in[$nameFields["Логин"]]]->mail = $in[$nameFields["Почта"]];
                } 
            }
        echo json_encode($listLogin);
    }
    function getTemplatePosition($name) // Положение полей в таблице
    {
        $nameFields = []; // Массив с положением полей в шаблоне Имя пользователя
        $in = []; // временный массив
        if($result = query("SELECT fields FROM template WHERE name = 'Имя пользователя'", []))
            while($row = $result->fetch_array(MYSQLI_NUM))
            {
                $in = json_decode($row[0]);
                $c = count($in);
                for($i = 0; $i < $c; $i++)
                    $nameFields[$in[$i]->name] = $i;
            }
        return $nameFields;
    }
    function getNameForLogin($login, $nameFields) // Получение полного имени из таблицы Пользователи по логину
    {
        $in = []; // временный массив
        if($result = query("SELECT fields FROM table_tree_big WHERE template = 'Имя пользователя'", []))
            while($row = $result->fetch_array(MYSQLI_NUM))
            {
                $in = json_decode($row[0]);
                if($login == $in[$nameFields["Логин"]])
                {
                    $name = $in[$nameFields["Имя"]];
                    $name2 = $in[$nameFields["Отчество"]];
                    return $in[$nameFields["Фамилия"]]." ".mb_substr($name, 0, 1, "UTF-8").". ".mb_substr($name2, 0, 1, "UTF-8").".";
                } 
            }
        return $login;
    }
    function request($_query, $param) // Отправка запроса и получение от вета в формате JSON
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
    function checkRightsForTableTemplate($i, $login, $id) // Проверка прав на таблицы с шаблонами
    {
        $Rights = getRightsForTable("rights_template", $login, $id);
        if($Rights[$i] == 0) return false;
        return true;
    }
    function checkRightsForTable($i, $login, $id) // Проверка прав на таблицы с данными
    {
        $Rights = getRightsForTable("rights", $login, $id);
        if($Rights[$i] == 0) return false;
        return true;
    }
    function getRightsForTable($table, $login, $id) // Проверка прав у таблицы
    {
        $Rights = [];
        if($result = query("SELECT rights FROM $table WHERE login = %s AND id_table = %i", [$login, $id]))
            while($row = $result->fetch_array(MYSQLI_NUM)) 
                $Rights = (int)$row[0];
        $Rights = recodeRights($Rights, 4);
        return $Rights;
    }
    function getAllChildren($data, $parent, $dataParent) // Получить дерево всех данных
    {
        $c = count($data);
        for($i = 0; $i < $c; $i++)
            if($data[$i][3] == $parent) 
            {
                $dataParent->{'children'}->{$data[$i][0]} = (object) array("template" => $data[$i][2], "data" => json_decode($data[$i][4]), "children" => new stdClass());
                getAllChildren($data, $data[$i][0], $dataParent->{'children'}->{$data[$i][0]});
            }
    }
    function getAllChildrenForRemove($parent) // Получить список id для удаления ветки
    {
        global $out;
        if($result = query("SELECT id FROM table_tree_big WHERE parent = %i", [$parent]))
            while($row = $result->fetch_array(MYSQLI_NUM)) 
            {
                $out .= ",".(int)$row[0];
                getAllChildrenForRemove((int)$row[0]);
            }
    }
?>				