<?php
	$charPermit = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-@.";
	$login = $param[0];
	$name = $param[1];
	$pass = $param[2];
	$mail = $param[3];
	$role = $param[4];
	
    if (!preCheckL($login) && !preCheckP($mail) && !preCheckPass($pass)) // Далее проверяется правильность написания логина пароля и почты
    {
        $array = [];
        $j = 0;
        if($result = query("SELECT login FROM registration", []))
            while ($row = $result->fetch_array(MYSQLI_NUM))
                for ($i = 0;  $i < count($row); $i++)
                {
                    $array[$j] = $row[$i];
                    $j++;
                }
        $bool1 = false;
        $bool2 = false;
        if(!checkL2($array, $login))
        {
            $array = [];
            $j = 0;
            if($result = query("SELECT mail FROM registration", [])) 
                while ($row = $result->fetch_array(MYSQLI_NUM))
                    for ($i = 0;  $i < count($row); $i++)
                    {
                        $array[$j] = $row[$i];
                        $j++;
                    }
            $bool2 = !checkP2($array, $mail);
            $bool1 = true;
            if(!$bool2) echo json_encode(["yes", "no", "yes"]);
        } 
        else echo json_encode(["no"]);
        if ($bool1 && $bool2)
        {
            $sult = unique_md5();
            $hash = myhash($pass, $sult);
            query("INSERT INTO password VALUES(%s,%s)", [$login, $hash]);
            query("INSERT INTO registration VALUES(%s, %s, %i, %s, %s)", [$login, $mail, $role, $current_time, $name]);
            echo json_encode(["yes", "yes", "yes"]);
        }
    }
?>