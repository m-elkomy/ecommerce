<?php

function lang($phrase){
	static $lang = array(
		'HOME'       => 'Home',
		'CATEGORIES' => 'Categories',
		'ITEMS'      => 'Items',
		'MEMBERS'    => 'Members',
		'COMMENTS'   => 'Comments',
		'LOGS'       => 'Logs',
	);
	return $lang[$phrase];
}