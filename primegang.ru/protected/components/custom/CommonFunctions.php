<?php

class CommonFunctions {
	public function transliterate($text) {
		$s_text = strtolower($text);

		$transl = array();
		$transl['а']='a';		$transl['б']='b';		$transl['в']='v';		$transl['г']='g';
		$transl['д']='d';		$transl['е']='e';		$transl['ё']='yo';		$transl['ж']='zh';
		$transl['з']='z';		$transl['и']='i';		$transl['й']='j';		$transl['к']='k';
		$transl['л']='l';		$transl['м']='m';		$transl['н']='n';		$transl['о']='o';
		$transl['п']='p';		$transl['р']='r';		$transl['с']='s';		$transl['т']='t';
		$transl['у']='u';		$transl['ф']='f';		$transl['х']='x';		$transl['ц']='c';
		$transl['ч']='ch';		$transl['ш']='sh';		$transl['щ']='sch';		$transl['ы']='y';
		$transl['э']='e';		$transl['ю']='u';		$transl['я']='ya';		$transl['ь']='';
		$transl['ъ']='';
	
		$transl['a']='a';		$transl['n']='n';		$transl['b']='b';		$transl['o']='o';
		$transl['c']='c';		$transl['p']='p';		$transl['d']='d';		$transl['q']='q';
		$transl['e']='e';		$transl['r']='r';		$transl['f']='f';		$transl['s']='s';
		$transl['g']='g';		$transl['t']='t';		$transl['h']='h';		$transl['u']='u';
		$transl['i']='i';		$transl['v']='v';		$transl['j']='j';		$transl['w']='w';
		$transl['k']='k';		$transl['x']='x';		$transl['l']='l';		$transl['y']='y';
		$transl['m']='m';		$transl['z']='z';
			
		$transl['1']='1';		$transl['5']='5';		$transl['2']='2';		$transl['6']='6';
		$transl['3']='3';		$transl['7']='7';		$transl['4']='4';		$transl['8']='8';
		$transl['9']='9';		$transl['0']='0';		$transl[' ']='-';
	
		$result = str_replace(array_keys($transl), $transl, $s_text);
		return $result;
	}
		
	public static function getSiteOption($optionName) {
		$option = SiteOptions::model()->find("alias=:alias",array('alias'=>$optionName));
		if (!$option) {
			return false;
		} else return $option->value;
	}
	
	public static function truncateText($text,$length=0) {
		if ($length == 0) $length = 500;
		$text = strip_tags($text);
		if (strlen($text) <= $length) return $text;
		
		$text = substr($text,0,$length);
		
		$lastspace = strrpos($text," ");
		$text = substr($text, 0, $lastspace);
		$text .= "...";
		return $text; 
	}
	
	public static function getAdmins() {
		$users = array();
		$_users = User::model()->findAll("superuser=:superuser",array("superuser"=>1));
		foreach($_users as $_u) $users[] = $_u->username;
		return $users;
	}
	
}

?>