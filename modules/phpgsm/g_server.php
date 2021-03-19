<?php

// Disallow direct access to this file for security reasons
defined(
    'IN_MYBB'
) or die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');



if (!isset($mybb->input['id'])) {
	require_once( 'inc/class.dbquick.php' ); // load the class
	define( 'DB_HOST', $settings['phpgsm_host'] ); // set database host
    define( 'DB_USER', $settings['phpgsm_user'] ); // set database user
    define( 'DB_PASS', $settings['phpgsm_password'] ); // set database password
    define( 'DB_NAME', $settings['phpgsm_db'] ); // set database name
    define( 'DB_COMMA',  '`'); // back tick 

	$rdb = new db();
	$sql = 'select * from server1 order by server_name ASC';
	$servers = $rdb->get_results($sql);
	
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
			$("#"+id+"stat").removeClass("color_cell_red");
			$("#"+id+"stat").addClass("color_color");
			
			$("#"+id+"stat").text("Running");
			//$("#"+id+"stat").load(location.href+" #"+id+"stat")
			$("#"+id+"restart").show();
			break;
			
			case "q":
			$("#"+id).attr("cmd","s");
			$("#"+id).addClass("button_yes");
			$("#"+id).removeClass("button_no");
			$("#"+id).text("Start");
			console.log("changed to s");
			$("#"+id+"stat").removeClass("color_cell");
			$("#"+id+"stat").addClass("color_cell_red");
			
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
    $table->construct_header('Actions', ['class' => 'align_center']);

    foreach ($servers as $server) {
			$id = trim($server['host_name']);
		if ($server['running']) {
			$running = 'Running';
			$button1 = 'Stop';
			$cmd ='q';
			$button1class = 'class="button_no setting_boardclosed"';
			$button2class = 'class="button2"';
			$button3 = 'Restart';
			$oclass = 'color_cell';
			//$(selector).attr(attribute)
			$button3 ='<button '.$button2class.' id="'.$server['host_name'].'restart" cmd="r" url="'.$server['url'].':'.$server['bport'].'" sid="'.$id.'" onclick="buttonclick(id)">'.$button3.'</button>';
			}
		else {
			$running = 'Stopped';
			$button1 = 'Start';
			$cmd='s';
			$button1class = 'class="button_yes setting_boardclosed"';
			$button3 ='';
			$oclass = 'color_cell_red';
		}	
		$table->construct_cell($server['host_name'], ['style' => 'width: 7%']);
        $table->construct_cell($server['server_name'], ['style' => 'width: 10%']);
        $table->construct_cell($server['host'].':'.$server['port'], ['style' => 'width: 10%']);
         $table->construct_cell($server['game'], ['style' => 'width: 7%']);
        $table->construct_cell($server['location'], ['style' => 'width: 15%']);
         $table->construct_cell($running, ['style' => 'width: 4%','id' =>$server['host_name'].'stat','class' => $oclass] );
        $table->construct_cell(
            '<button '.$button1class.' id= "'.$server['host_name'].'" cmd="'.$cmd.'" url="'.$server['url'].':'.$server['bport'].'" sid="'.$id.'"  onclick="buttonclick(id)">'.$button1.'</button>'.
            $button3.'<button '.$button2class.' id= "'.$server['host_name'].'edit">Edit</button><button '.$button2class.' id= "'.$server['host_name'].'update">Update</button>',['style' => 'width: 15%', 'class' => 'align_center']);
        $table->construct_row();
    } 

    //$table->output('Installed Servers',0,"button_yes",false);
	$table->output('Installed Servers',0,'general',false);
    $page->output_footer();
   
} else {
	print_r($mybb->input);
    $importerId = (int)$mybb->input['id'];
    echo 'hello world '.$importerId;
    if (!isset($importers[$importerId])) {
        die('Invalid importer ID!');
    }

   
}
