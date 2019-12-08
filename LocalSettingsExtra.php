<?php
$wgSitename         = "Уики на Читанка";

wfLoadSkin( 'MonoBook' );
wfLoadSkin( 'Vector' );
wfLoadSkin( 'Timeless' );
wfLoadSkin( 'Tweeki' );

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'vector', 'monobook':
$wgDefaultSkin = 'tweeki';

$wgLocaltimezone = 'Europe/Sofia';
date_default_timezone_set( $wgLocaltimezone );
$wgLocalTZoffset = date('Z') / 60;

$wgFavicon = '/favicon.png';

$wgNamespacesWithSubpages[NS_MAIN] = true;

$wgExtraNamespaces = array(
	100 => 'Личност',
	101 => 'Личност_беседа',
);

# Establish namespace protection for all but discussion and user pages
$wgNamespaceProtection[NS_MAIN] =
$wgNamespaceProtection[NS_PROJECT] =
$wgNamespaceProtection[NS_FILE] =
$wgNamespaceProtection[NS_IMAGE] =
$wgNamespaceProtection[NS_TEMPLATE] =
$wgNamespaceProtection[NS_HELP] =
$wgNamespaceProtection[NS_CATEGORY] =
$wgNamespaceProtection[100] = array('editarticles');

$wgGroupPermissions['*']['createaccount']    = false;
$wgGroupPermissions['*']['edit']             = false;
$wgGroupPermissions['*']['createpage']       = false;
$wgGroupPermissions['*']['createtalk']       = false;
$wgGroupPermissions['*']['writeapi']         = false;

$wgGroupPermissions['user']['move']             = false;
$wgGroupPermissions['user']['move-subpages']    = false;
$wgGroupPermissions['user']['move-rootuserpages'] = false;
$wgGroupPermissions['user']['writeapi']         = false;
$wgGroupPermissions['user']['reupload']         = false;
$wgGroupPermissions['user']['reupload-shared']  = false;

$wgGroupPermissions['sysop']['createaccount']    = false;
$wgGroupPermissions['sysop']['writeapi']         = true;
$wgGroupPermissions['sysop']['editarticles']     = true;

$wgRawHtml = true;

// $wgDebugLogFile = dirname(__FILE__) . '/debug.log';
// $wgDBerrorLog = dirname(__FILE__) . '/db-error.log';
// $wgDebugDumpSql = true;
// $wgShowExceptionDetails = true;
// $wgShowDebug = true;
// $wgShowSQLErrors = true;

wfLoadExtension('Cite');
wfLoadExtension('InputBox');

wfLoadExtension('ParserFunctions');
$wgPFEnableStringFunctions = true;

require_once $IP . '/../mylib-single-login/get_user.php';
require_once $IP . '/extensions/auth_mylib/auth_mylib.php';

/*
$wgHooks['ResourceLoaderRegisterModules'][] = 'registerMylibModule';

function registerMylibModule($loader) {
	$loader->register(array(
		'skins.mylib' => new ResourceLoaderFileModule(
			array( 'styles' => array( 'skins/mylib/screen.css' => array( 'media' => 'screen' ) ) )
		),
	));
	return true;
}
*/
