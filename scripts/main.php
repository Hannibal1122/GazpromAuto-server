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
            $out_rights = recodeRights($rights, 5);
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
                        case 59: // Запрос списка таблиц
                            request("SELECT id, name_table, name_template, info, rights FROM table_initialization", []);
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
                            query("INSERT INTO table_initialization (name_table, name_template, info, rights) VALUES(%s, %s, %s, %i)", $param);
                            $id =  $mysqli->insert_id;
                            query("CREATE TABLE table_init_$id ($str)", []);
                            break;   
                        case 61: // Изменение таблицы
                            request("UPDATE table_initialization SET info = %s, rights = %i WHERE id = %i", $param);
                            break;
                        case 62: // Удаление таблицы
                            $id_table = (int)$param[0];
                            query("DROP TABLE table_init_$id_table", []);
                            query("DELETE FROM table_initialization WHERE id = %i", [$id_table]); 
                            break;
                        case 63: // Загрузка таблицы
                            $id = (int)($param[0]);
                            $Head = [];
                            $Template = [];
                            $Table = [];
                            $Type = [];
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
                            echo json_encode(["head" => $Head, "data" => $Table, "template" => $Template, "type" => $Type, "rights" => [15]]);
                            break;
                        case 64: // Добавление строк в таблицу
                            $id = (int)($param[0]);
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
                            //if(!checkRightsForTable(1, $paramL, $id)) { break; }
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
                    }
            if($nQuery >= 100 && $nQuery < 150) // Работа с Данными
                if($out_rights[1])
                    switch($nQuery)
                    {
                        case 100: // Запрос списка таблиц
                            request("SELECT id, name_table, name_template, info, rights FROM table_big WHERE id IN (SELECT table_id FROM rights WHERE login = %s AND rights & 1)", [$paramL]);
                            break;   
                        case 101: // Добавление таблицы
                            query("INSERT INTO table_big (name_table, name_template, info, rights) VALUES(%s, %s, %s, %i)", $param);
                            $id =  $mysqli->insert_id;
                            query("INSERT INTO rights (table_id, login, rights) VALUES(%i, %s, %i)", [$id, $paramL, 15]);
                            break;   
                        /* case 102: // Изменение таблицы
                            request("UPDATE bind_template SET id_parent = %i, id_parent_cell = %i, info = %s, rights = %i, _default = %i, status = %s, person = %s, terms = %s WHERE id = %i", $param);
                            break;*/
                        case 103: // Удаление таблицы
                            $id_table = (int)$param[0];
                            query("DELETE FROM table_big WHERE id = %i", [$id_table]);
                            query("DELETE FROM table_tree_big WHERE id_table = %i", [$id_table]);
                            break;
                        case 104: // Загрузка таблицы
                            $id = (int)($param[0]);
                            $Head = [];
                            $Table = [];
                            $OutTable = new stdClass();
                            $Type = [];
                            $Rights = getRightsForTable($paramL, $id);
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
                                                "templates" => $templates]);
                            break;
                        case 105: // Добавление строк в таблицу
                            $id = (int)($param[0]);
                            if(!checkRightsForTable(1, $paramL, $id)) { break; };
                            query("INSERT INTO table_tree_big (id_table, template, parent, fields) VALUES(%i, %s, %i, %s)", $param);
                            break;
                        case 106: // Обновление таблицы
                            $id = (int)($param[0]);
                            if(!checkRightsForTable(1, $paramL, $id)) { break; };
                            query("UPDATE table_tree_big SET fields = %s WHERE id = %i", [$param[1], $param[2]]);
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
                            if(!checkRightsForTable(1, $paramL, $id)) { break; };
                            $out = (int)$param[1];
                            getAllChildrenForRemove((int)$param[1]);
                            query("DELETE FROM table_tree_big WHERE id IN ($out)", []);
                            break;
                        case 110: // Автоматическое заполнение таблицы
                            $id = (int)($param[0]);
                            $id_init_table = (int)$param[3];
                            if(!checkRightsForTable(1, $paramL, $id)) { break; };
                            
                            $out = "-1";
                            getAllChildrenForRemove((int)$param[2]);
                            query("DELETE FROM table_tree_big WHERE id IN ($out)", []);

                            if($result = query("SELECT * FROM table_init_$id_init_table", []))
                                while($row = $result->fetch_array(MYSQLI_NUM)) 
                                {
                                    array_shift($row);
                                    query("INSERT INTO table_tree_big (id_table, template, parent, fields) VALUES(%i, %s, %i, %s)", [$id, $param[1], $param[2], json_encode($row)]);
                                }
                            /* echo "template = ".$param[1]."\n";
                            echo "parent = ".$param[2]."\n"; */
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
    function recodeRights($_rights, $n)
    {
        $out = [];
        for($i = 0; $i < $n; $i++)
        {
            $out[] = $_rights & 1;  
            $_rights >>= 1;
        }
        return $out;
    }
    function checkRightsForTable($i, $login, $id)
    {
        $Rights = getRightsForTable($login, $id);
        if($Rights[$i] == 0) return false;
        return true;
    }
    function getRightsForTable($login, $id)
    {
        $Rights = [];
        if($result = query("SELECT rights FROM rights WHERE login = %s AND table_id = %i", [$login, $id]))
            while($row = $result->fetch_array(MYSQLI_NUM)) 
                $Rights = (int)$row[0];
        $Rights = recodeRights($Rights, 4);
        return $Rights;
    }
    function getAllChildren($data, $parent, $dataParent)
    {
        $c = count($data);
        for($i = 0; $i < $c; $i++)
            if($data[$i][3] == $parent) 
            {
                $dataParent->{'children'}->{$data[$i][0]} = (object) array("template" => $data[$i][2], "data" => json_decode($data[$i][4]), "children" => new stdClass());
                getAllChildren($data, $data[$i][0], $dataParent->{'children'}->{$data[$i][0]});
            }
    }
    function getAllChildrenForRemove($parent)
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