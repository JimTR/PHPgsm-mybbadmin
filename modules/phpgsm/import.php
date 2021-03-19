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

if (!isset($lang->simplelikes)) {
    //$lang->load('simplelikes');
}


if (!isset($mybb->input['id'])) {
    $page->add_breadcrumb_item('PHPgsm', 'index.php?module=phpgsm');
    $page->add_breadcrumb_item('Installed Games', 'index.php?module=phpgsm-import');
    $page->output_header('Installed Games');
	require_once( 'inc/class.dbquick.php' ); // load the class
	    define( 'DB_HOST', "46.32.237.232" ); // set database host
        define( 'DB_USER', 'nod' ); // set database user
        define( 'DB_PASS', 'ahamay900' ); // set database password
        define( 'DB_NAME', 'gsm' ); // set database name
        //define( 'SEND_ERRORS_TO', $config['database']['errors'] ); //set email notification email address
        //define( 'DISPLAY_DEBUG', $config['database']['display_error'] ); //display db errors?
        define( 'DB_COMMA',  '`'); // back tick 
			$button1class = 'class="button_no setting_boardclosed"';
			$button1 = 'Add';
	$rdb = new db();
    $table = new Table();
    $sql = 'SELECT game_servers.*, servers.server_update FROM `game_servers` JOIN servers on game_servers.server_id = servers.server_id group by server_id';
	$games = $rdb->get_results($sql);
	$table->construct_header('Game');
    $table->construct_header('Server ID');
    $table->construct_header('Installed at');
    $table->construct_header('Last Update');
    $table->construct_header('Installs',['class' => 'align_center']);
    $table->construct_header('Actions', ['class' => 'align_center']);

    foreach ($games as $game) { 
		$ud = intval($game['server_update']);
		$installs = $rdb->num_rows('select * from servers where server_id = '.$game['server_id']);
        $table->construct_cell($game['game_name'], ['style' => 'width: 25%']);
        $table->construct_cell($game['server_id'], ['style' => 'width: 10%']);
        $table->construct_cell($game['install_dir'], ['style' => 'width: 27%']);
        $table->construct_cell(date('d-m-Y H:i:s',$ud), ['style' => 'width: 10%']);
        $table->construct_cell('<div style="text-align:center;">'.$installs.'</div>', ['style' => 'width: 3%']);
        $table->construct_cell(
            '<button '.$button1class.' id= "'.$server['host_name'].'" cmd="'.$cmd.'" url="'.$server['url'].':'.$server['bport'].'" sid="'.$id.'"  onclick="buttonclick(id)">'.$button1.'</button>'
        );
        $table->construct_row();
    } 

    $table->output('Installed Games');

    $page->output_footer();
} else {
    //$importerId = (int)$mybb->input['id'];
    //$importers = $importManager->getImporters();
	echo 'hello world mod 1';
    if (!isset($importers[$importerId])) {
        die('Invalid importer ID!');
    }

    /** @var MybbStuff\SimpleLikes\Import\AbstractImporter $importer */
    $importer = $importers[$importerId];

    try {
        $numImported = $importer->importLikes();

        flash_message($lang->sprintf($lang->simplelikes_import_success_count_imported, $numImported), 'success');
        admin_redirect('index.php?module=mybbstuff_likes-import');
    } catch (Exception $e) {
        flash_message($lang->sprintf($lang->simplelikes_import_error, $e->getMessage()), 'error');
        admin_redirect('index.php?module=mybbstuff_likes-import');
    }
}
