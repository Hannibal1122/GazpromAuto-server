<?php
    function preCheckL($login)
	{
		global $charPermit;
		if (strlen($login) < 3 || strlen($login) > 32)
			return true;
		for ($i = 0;  $i < strlen($login); $i++)
		{
			for ($j = 0;  $j < strlen($charPermit) - 4; $j++)
				if ($login[$i] == $charPermit[$j]) 
					break;
			if($j == strlen($charPermit) - 4) break;
		}
		if($i != strlen($login))
			return true;
		return false;
	}
	function preCheckP($post)
	{
		global $charPermit;
		if (strlen($post) < 5 || strlen($post) > 32)
			return true;
		for ($i = 0;  $i < strlen($post); $i++)
		{
			for ($j = 0;  $j < strlen($charPermit); $j++)
				if ($post[$i] == $charPermit[$j]) 
					break;
			if($j == strlen($charPermit)) break;
		}
		if($i == strlen($post))
		{
			for($i = 0; $i < strlen($post); $i++)
				if($post[$i] == '@') break;
			if($i != strlen($post))
			{
				for($j = $i + 2; $j < strlen($post); $j++)
					if($post[$j] == ".") break;
				if($j < strlen($post) - 2)
					return false;
				else return true;
			}
			else return true;
		} else return true;
	}
	function preCheckPass($pass)
	{
		global $charPermit;
		if (strlen($pass) < 6 || strlen($pass) > 32)
			return true;
		for ($i = 0;  $i < strlen($pass); $i++)
		{
			for ($j = 0;  $j < strlen($charPermit) - 4; $j++)
				if ($pass[$i] == $charPermit[$j]) 
					break;
			if($j == strlen($charPermit) - 4) break;
		}
		if($i != strlen($pass))
			return true;
		return false;
	}	
	function checkL2($array, $login)
	{
		for ($i = 0;  $i < count($array); $i++)
			if($array[$i] == $login) 
				return true;
		return false;
	}
	function checkP2($array, $post)
	{
		for ($i = 0;  $i < count($array); $i++)
			if($array[$i] == $post) 
				return true;
		return false;
    }
    function unique_md5() 
	{
		mt_srand((int)(microtime(true) * 100000 + memory_get_usage(true)));
		return md5(uniqid(mt_rand(), true));
    }
	function myhash($password, $unique_salt) 
	{  
		return crypt($password, '$2a$10$'.$unique_salt); 
    }
?>	