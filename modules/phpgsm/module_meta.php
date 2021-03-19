<?php
/**
 * SimpleLikes admin module meta information.
 *
 * @package Simple Likes
 * @author  Euan T. <euan@euantor.com>
 * @license http://opensource.org/licenses/mit-license.php MIT license
 * @version 2.0.0
 */

// Disallow direct access to this file for security reasons
if (!defined('IN_MYBB')) {
    die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

function phpgsm_meta()
{
    global $page, $lang, $plugins, $cache;

   
    if (!isset($lang->phpgsm)) {
        $lang->load('phpgsm');
    }

    $subMenu = [
      11 => [
            'id' => 'stats',
            'title' => 'Overview',
            'link' => 'index.php?module=phpgsm-stats',
        ],
         10 => [
            'id' => 'servers',
            'title' => 'Parent Server Status',
            'link' => 'index.php?module=phpgsm-servers',
        ],
        20 => [
            'id' => 'import',
            'title' => 'Installed Games',
            'link' => 'index.php?module=phpgsm-import',
        ],
        30 => [
            'id' => 'g_server',
            'title' => 'Installed Servers',
            'link' => 'index.php?module=phpgsm-g_server',
        ],
        40 => [
            'id' => 'console',
            'title' => 'Console',
            'link' => 'index.php?module=phpgsm-console',
        ],
        
    ];

    $subMenu = $plugins->run_hooks('admin_phpgsm_menu', $subMenu);
   

    $page->add_menu_item('PHPgsm', 'phpgsm', 'index.php?module=phpgsm', 100, $subMenu);

    return true;
}

function phpgsm_action_handler($action)
{
    global $page, $plugins, $mybb;

    $page->active_module = 'phpgsm';
	
	

		//$page->sidebar .= $sidebar->get_markup();
    $actions = [
		'stats' => ['active' => 'stats', 'file' => 'stats.php'],
        'import' => ['active' => 'import', 'file' => 'import.php'],
         'g_server' => ['active' => 'g_server', 'file' => 'g_server.php'],
         'console' => ['active' => 'console', 'file' => 'console.php'],
         'servers' => ['active' => 'servers', 'file' => 'servers.php'],
    ];

    $actions = $plugins->run_hooks('admin_phpgsm_action_handler', $actions);
	$sub_menu = array();
	$sub_menu['10'] = array("id" => "adminlog", "title" => 'Modify Parent Servers', "link" => "index.php?module=tools-adminlog");
	$sub_menu['20'] = array("id" => "modlog", "title" => 'Modify Servers', "link" => "index.php?module=tools-modlog");
	$sub_menu['30'] = array("id" => "maillogs", "title" => 'Check Dependencies', "link" => "index.php?module=tools-maillogs");
	//$sub_menu['40'] = array("id" => "mailerrors", "title" => $lang->system_mail_log, "link" => "index.php?module=tools-mailerrors");
	//$sub_menu['50'] = array("id" => "warninglog", "title" => $lang->user_warning_log, "link" => "index.php?module=tools-warninglog");
	//$sub_menu['60'] = array("id" => "spamlog", "title" => $lang->spam_log, "link" => "index.php?module=tools-spamlog");
	//$sub_menu['70'] = array("id" => "statistics", "title" => $lang->statistics, "link" => "index.php?module=tools-statistics");

	//$sub_menu = $plugins->run_hooks("admin_tools_menu_logs", $sub_menu);
	
    if (!isset($actions[$action])) {
        $page->active_action = $actions[$action]['active'];
          }
          
          $sidebar = new SidebarItem("Tools");
	$sidebar->add_menu_items($sub_menu, $actions[$action]['active']);

	$page->sidebar .= $sidebar->get_markup();

	if(isset($actions[$action]))
	{
		$page->active_action = $actions[$action]['active'];
		return $actions[$action]['file'];
	}
          
           
    else {
        $page->active_action = 'stats';

        return 'stats.php';
    }
}


