<?php
	$url = $_SERVER['REQUEST_URI'];
	$parser_url = explode("/", $url);
	$parser_index = explode('.php', $parser_url[2]);
	switch ( $parser_index[0] ) {
		case 'index':
			$menu = "index";
			break;
		case 'submissions':
			$menu = "submissions";
			break;
		case 'scoreboard':
			$menu = "scoreboard";
			break;
		case 'account':
			$menu = "account";
			break;
		default:
			$menu = "index";
			break;
	}
?>