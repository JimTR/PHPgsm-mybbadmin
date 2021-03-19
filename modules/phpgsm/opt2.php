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



if (!isset($mybb->input['id'])) {
	require_once( 'inc/class.dbquick.php' ); // load the class
	    define( 'DB_HOST', "46.32.237.232" ); // set database host
        define( 'DB_USER', 'nod' ); // set database user
        define( 'DB_PASS', 'ahamay900' ); // set database password
        define( 'DB_NAME', 'gsm' ); // set database name
        //define( 'SEND_ERRORS_TO', $config['database']['errors'] ); //set email notification email address
        //define( 'DISPLAY_DEBUG', $config['database']['display_error'] ); //display db errors?
        define( 'DB_COMMA',  '`'); // back tick 

	$rdb = new db();
	$sql = 'select * from server1 order by server_name ASC';
	$users = $rdb->get_results($sql);
	
    $page->add_breadcrumb_item('PHPgsm', 'index.php?module=phpgsm');
    $page->add_breadcrumb_item('Installed Servers', 'index.php?module=phpgsm-opt2');
    $page->output_header('Installed Games');
 echo '<script>
    $(document).ready(function() {
    console.log(\'hello world\');
});

function buttonclick(id) {
				var url = $("#"+id).attr("url");
				var exe = $("#"+id).attr("sid");
				var cmd = $("#"+id).attr("cmd");
				//console.log ("click "+id+" - "+url+" - "+cmd);
				var Url = url+"/ajaxv2.php?action=exescreen&server="+exe+"&cmd="+cmd;
				console.log(Url);
				$.get(Url, function(data, status){
    //alert("Data: " + data + "\nStatus: " + status);
                
     if(status == "success" ) {
		 $("#"+id).blur();
		 console.log(data);
		 switch(cmd) {
			case "s":
			$("#"+id).attr("cmd","q");
			$("#"+id).addClass("button_no");
			$("#"+id).removeClass("button_yes");
			$("#"+id).text("Stop");
			console.log("changed to q");
			$("#"+id+"stat").text("Running");
			$("#"+id+"restart").show();
			break;
			
			case "q":
			$("#"+id).attr("cmd","s");
			$("#"+id).addClass("button_yes");
			$("#"+id).removeClass("button_no");
			$("#"+id).text("Start");
			console.log("changed to s");
			$("#"+id+"stat").text("Stopped");
			$("#"+id+"restart").hide();
			break;
			
			case "r":
				console.log("restart");
		}
			
	 }
  });
 
			}
			

			

    </script>';
    $table = new Table();

    $table->construct_header('Internal Name');
    $table->construct_header('Server Name');
    $table->construct_header('IP/Port');
    $table->construct_header('Type');
    $table->construct_header('Location');
     $table->construct_header('Status');
    $table->construct_header($lang->simplelikes_importer_actions, ['class' => 'align_center']);

    foreach ($users as $user) {
			$id = trim($user['host_name']);
		if ($user['running']) {
			$running = 'Running';
			$button1 = 'Stop';
			$cmd ='q';
			$button1class = 'class="button_no setting_boardclosed"';
			$button2class = 'class="button2"';
			$button3 = 'Restart';
			//$(selector).attr(attribute)
			$button3 ='<button '.$button2class.' id="'.$user['host_name'].'restart" cmd="r" url="'.$user['url'].':'.$user['bport'].'" sid="'.$id.'" onclick="buttonclick(id)">'.$button3.'</button>';
			}
		else {
			$running = 'Stopped';
			$button1 = 'Start';
			$cmd='s';
			$button1class = 'class="button_yes setting_boardclosed"';
			$button3 ='';
		}	
		$table->construct_cell($user['host_name'], ['style' => 'width: 7%']);
        $table->construct_cell($user['server_name'], ['style' => 'width: 10%']);
        $table->construct_cell($user['host'].':'.$user['port'], ['style' => 'width: 10%']);
         $table->construct_cell($user['game'], ['style' => 'width: 7%']);
        $table->construct_cell($user['location'], ['style' => 'width: 15%']);
         $table->construct_cell($running, ['style' => 'width: 4%','id' =>$user['host_name'].'stat'] );
        $table->construct_cell(
            '<button '.$button1class.' id= "'.$user['host_name'].'" cmd="'.$cmd.'" url="'.$user['url'].':'.$user['bport'].'" sid="'.$id.'"  onclick="buttonclick(id)">'.$button1.'</button>'.
            $button3.'<button '.$button2class.' id= "'.$user['host_name'].'edit">Edit</button>',
            ['style' => 'width: 15%', 'class' => 'align_center', 'id' =>'test'],
        );
        $table->construct_row();
    } 

    $table->output('Installed Servers');

    $page->output_footer();
   
} else {
	print_r($mybb->input);
    $importerId = (int)$mybb->input['id'];
    echo 'hello world '.$importerId;
    if (!isset($importers[$importerId])) {
        die('Invalid importer ID!');
    }

   
}
