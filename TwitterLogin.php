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
	'version' => '0.03',
	'author' => array( 'Dave Challis', '[https://www.mediawiki.org/wiki/User:Clausekwis David Raison]' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:TwitterLogin',
	'descriptionmsg' => 'twitterlogin-desc',
	'license-name' => 'LGPL-3.0-or-later'
);

// Create a twiter group
$wgGroupPermissions['twitter'] = $wgGroupPermissions['user'];

$wgAutoloadClasses['SpecialTwitterLogin'] = __DIR__ . '/SpecialTwitterLogin.php';
$wgAutoloadClasses['TwitterOAuth'] = __DIR__ . '/twitteroauth/twitteroauth.php';
$wgAutoloadClasses['MwTwitterOAuth'] = __DIR__ . '/TwitterLogin.twitteroauth.php';
$wgAutoloadClasses['TwitterSigninUI'] = __DIR__ . '/TwitterLogin.body.php';

$wgMessagesDirs['TwitterLogin'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['TwitterLoginAlias'] = __DIR__ . '/TwitterLogin.alias.php';

$wgSpecialPages['TwitterLogin'] = 'SpecialTwitterLogin';

$wgHooks['LoadExtensionSchemaUpdates'][] = 'efSetupTwitterLoginSchema';

$tsu = new TwitterSigninUI;
$wgHooks['BeforePageDisplay'][] = array( $tsu, 'efAddSigninButton' );

$stl = new SpecialTwitterLogin;
$wgHooks['UserLoadFromSession'][] = array($stl,'efTwitterAuth');
$wgHooks['UserLogoutComplete'][] = array($stl,'efTwitterLogout');

function efSetupTwitterLoginSchema( $updater ) {
	$updater->addExtensionUpdate( array( 'addTable', 'twitter_user',
		__DIR__ . '/schema/twitter_user.sql', true ) );
	$updater->addExtensionUpdate( array( 'modifyField', 'twitter_user','user_id',
		__DIR__ . '/schema/twitter_user.patch.user_id.sql', true ) );
	$updater->addExtensionUpdate( array( 'modifyField', 'twitter_user','twitter_id',
		__DIR__ . '/schema/twitter_user.patch.twitter_id.sql', true ) );
	return true;
}
