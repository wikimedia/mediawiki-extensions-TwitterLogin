<?php
/**
 * TwitterLogin.php
 * Written by David Raison, based on the guideline published by Dave Challis at http://blogs.ecs.soton.ac.uk/webteam/2010/04/13/254/
 * @license: LGPL (GNU Lesser General Public License) http://www.gnu.org/licenses/lgpl.html
 *
 * @file TwitterLogin.php
 * @ingroup TwitterLogin
 *
 * @author David Raison
 *
 * Uses the twitter oauth library by Abraham Williams from https://github.com/abraham/twitteroauth
 *
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This is a MediaWiki extension, and must be run from within MediaWiki.' );
}

$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'TwitterLogin',
	'version' => '0.01',
	'author' => array( 'Dave Challis', '[http://www.mediawiki.org/wiki/User:Clausekwis David Raison]' ), 
	'url' => 'http://www.mediawiki.org/wiki/Extension:TwitterLogin',
	'descriptionmsg' => 'twitterlogin-desc'
);

// Create a twiter group
$wgGroupPermissions['twitter'] = $wgGroupPermissions['user'];

$wgAutoloadClasses['SpecialTwitterLogin'] = dirname(__FILE__) . '/SpecialTwitterLogin.php';
$wgAutoloadClasses['TwitterOAuth'] = dirname(__FILE__) . '/twitteroauth/twitteroauth.php';
$wgAutoloadClasses['TwitterSigninUI'] = dirname(__FILE__) . '/TwitterLogin.body.php';

$wgExtensionMessagesFiles['TwitterLogin'] = dirname(__FILE__) .'/TwitterLogin.i18n.php';
$wgSpecialPages['TwitterLogin'] = 'SpecialTwitterLogin';
$wgSpecialPageGroups['TwitterLogin'] = 'login';

$wgHooks['LanguageGetMagic'][] = 'wfTwitterLoginLanguageGetMagic';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'efSetupTwitterLoginSchema';

$tsu = new TwitterSigninUI;
$wgHooks['BeforePageDisplay'][] = array( $tsu, 'efAddSigninButton' );

$stl = new SpecialTwitterLogin;
$wgHooks['UserLoadFromSession'][] = array($stl,'efTwitterAuth');
$wgHooks['UserLogoutComplete'][] = array($stl,'efTwitterLogout');

function wfTwitterLoginLanguageGetMagic( &$magicWords, $langCode = 'en' ) {
	$magicWords['twitterlogin'] = array( 0, 'twitterlogin' );
	return true;
}

function efSetupTwitterLoginSchema() {
	$updater->addExtensionUpdate( array( 'addTable', 'twitter_user',
		dirname(__FILE__) . '/twitter_user.sql', true ) );
	return true;
}