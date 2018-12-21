<?php
/**
* Authentication in MediaWiki through Mylib
*/

if ( ! defined('MEDIAWIKI') ) {
	die('Authentication through Mylib');
}


$wgHooks['UserLoadFromSession'][] = 'doAuthWithMylib';
$wgHooks['PersonalUrls'][] = 'removeAuthUrls';


function doAuthWithMylib($user, &$result)
{
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

	$user->setId( $user->idFromName($canonicalName) );
	$user->setName($canonicalName);

	if ( $user->getId() == 0 ) { // new user
		$user->addToDatabase();

		$user->setEmail( $mylibUser['email'] );
		$user->setRealName( $mylibUser['realname'] );
		$user->setToken();

		$user->saveSettings();
	} else {
		if ( ! $user->loadFromDatabase() ) {
			// Can't load from ID, user is anonymous
			return true;
		}
		$user->saveToCache();
	}

	$result = true; // skip the rest of the authentication process
	return true;
}


function removeAuthUrls(&$personal_urls, $title)
{
	foreach (array('logout', 'login', 'anonlogin') as $action) {
		if ( isset($personal_urls[$action]) ) {
			unset($personal_urls[$action]);
		}
	}

	return true;
}
