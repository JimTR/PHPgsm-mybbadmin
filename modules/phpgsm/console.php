<?php
/**
 * SimpleLikes admin import page.
 *
 * Allows the importing of likes from other like systems.
 *
 * @package Simple Likes
 * @author  Euan T. <euan@euantor.com>
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @version 2.0.0
 */

// Disallow direct access to this file for security reasons
defined(
    'IN_MYBB'
) or die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');

if (!isset($lang->phpgsm)) {
    $lang->load('phpgsm');
}
	$settings = $mybb->settings;
	$user = $mybb->user;
	$config = $mybb->config;
global $theme; 
require_once( 'inc/class.dbquick.php' ); // load the class
	define( 'DB_HOST', $settings['phpgsm_host'] ); // set database host
    define( 'DB_USER', $settings['phpgsm_user'] ); // set database user
    define( 'DB_PASS', $settings['phpgsm_password'] ); // set database password
    define( 'DB_NAME', $settings['phpgsm_db'] ); // set database name
    $rdb = new db();
    $sql = 'select * from server1 where running = 1 ORDER BY server_name';
    $servers = $rdb->get_results($sql);
    $sbox ='<option id ="" value="" path="" host ="">Choose Server</option>';
    foreach ($servers as $server) {
		//fill select box
		$sbox .='<option id ="'.$server['host_name'].'" value="'.$server['url'].':'.$server['bport'].'" path="'.$server['location'].'" host ="'.$server['host'].':'.$server['port'].'">'.$server['server_name'].'</option>';
	}
	$theme['templateset'] = 1;
if (!isset($mybb->input['id'])) {
    $page->add_breadcrumb_item('PHPgsm', 'index.php?module=phpgsm');
    $page->add_breadcrumb_item('Console', 'index.php?module=phpgsm-users');
    $page->output_header('Console');

    $table = new Table();

   
echo '<div style="border:#000 solid 1px;width:100%;border-radius: 5px 5px 0 0;">';
    $table->output('Console');
    echo '<label style="padding-left:2%;padding-right:7px;" for="servers">Choose a Server:</label><select id="servers">'.$sbox.'</select>';
  
eval("\$content .=  \"".$templates->get("phpgsm_console")."\";"); 
	echo $content;
	echo '</div></div>';
    $page->output_footer();
} else {
    //$importerId = (int)$mybb->input['id'];
    //$importers = $importManager->getImporters();
	echo 'hello world';
  
}
