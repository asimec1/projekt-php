<?php 
	function pickerDateToMysql($pickerDate){
		$date = DateTime::createFromFormat('Y-m-d H:i:s', $pickerDate);
		return $date->format('d. m. Y H:i:s');
	}  
	function newsExcerpt($text, $limit = 300) {
		$text = trim(strip_tags(html_entity_decode($text, ENT_QUOTES, 'UTF-8')));

		if (mb_strlen($text, 'UTF-8') <= $limit) {
			return $text;
		}

		return mb_substr($text, 0, $limit, 'UTF-8') . '...';
	}
?>