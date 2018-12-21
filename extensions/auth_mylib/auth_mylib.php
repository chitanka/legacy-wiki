<?php
/**
* Authentication in MediaWiki through Mylib
*/

if ( ! defined('MEDIAWIKI') ) {
	die('Authentication through Mylib');
}

$wgExtensionCredits['media'][] = array(
	'path'           => __FILE__,
	'name'           => 'AuthMylib',
	'author'         => 'Borislav Manolov',
	//'url'            => 'http://chitanka.info',
	'description'    => 'Authentication through Mylib',
	//'descriptionmsg' => '',
);


$wgHooks['UserLoadFromSession'][] = 'doAuthWithMylib';
$wgHooks['PersonalUrls'][] = 'removeAuthUrls';


function doAuthWithMylib($user, &$result) {
	wfSetupSession();

	$mylibUser = getValidMylibUser();

	// Force usernames to capital
	$name = $GLOBALS['wgContLang']->ucfirst( $mylibUser['username'] );

	// Clean up name according to title rules
	$t = Title::newFromText($name);
	if ( is_null($t) ) {
		return true;
	}

	$canonicalName = $t->getText();

	if ( ! User::isValidUserName($canonicalName) ) {
		return true;
	}

	//$id = User::idFromName($canonicalName);
	$id = mwUserIdFromName($canonicalName);

	$user->setId($id);
	$user->setName($canonicalName);

	if ( $user->getId() == 0 ) { // new user
		$user->addToDatabase();

		$user->setEmail( $mylibUser['email'] );
		$user->setRealName( $mylibUser['realname'] );
		$user->setToken();

		$user->saveSettings();
	} else {
		$user->loadFromId();
	}

	$result = true; // skip the rest of the authentication process
	return true;
}


function removeAuthUrls(&$personal_urls, $title) {
	foreach (array(/*'login', */'anonuserpage', 'anontalk') as $action) {
		if ( isset($personal_urls[$action]) ) {
			unset($personal_urls[$action]);
		}
	}

	if ( isset($personal_urls['login']) ) {
		$returnto = urlencode('//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		$personal_urls['login'] = array(
			'text' => 'Вход',
			'href' => '//chitanka.info/login?returnto='.$returnto,
		);
		$personal_urls['register'] = array(
			'text' => 'Регистрация',
			'href' => '//chitanka.info/register?returnto='.$returnto,
		);
	}

	if ( isset($personal_urls['logout']) ) {
		$personal_urls['logout'] = array(
			'text' => 'Изход',
			'href' => '//chitanka.info/signout'
		);
	}

	return true;
}


/**
* User::idFromName is broken at r74245:
* uses Title::getText() which includes the namespace name too
* and always results in unknown user name
*/
function mwUserIdFromName($name) {
	$dbr = wfGetDB( DB_SLAVE );
	$s = $dbr->selectRow( 'user', array( 'user_id' ), array( 'user_name' => $name ), __METHOD__ );

	if ( $s === false ) {
		$result = null;
	} else {
		$result = $s->user_id;
	}

	return $result;
}
