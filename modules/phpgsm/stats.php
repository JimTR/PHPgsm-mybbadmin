<?php
/*
 * stats.php
 * 
 * Copyright 2021 Jim Richardson <jim@noideersoftware.co.uk>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
defined(
    'IN_MYBB'
) or die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
require_once( 'inc/class.dbquick.php' ); // load the class
require 'inc/Emoji.php';
	    define( 'DB_HOST', "46.32.237.232" ); // set database host
        define( 'DB_USER', 'nod' ); // set database user
        define( 'DB_PASS', 'ahamay900' ); // set database password
        define( 'DB_NAME', 'gsm' ); // set database name
        //define( 'SEND_ERRORS_TO', $config['database']['errors'] ); //set email notification email address
        //define( 'DISPLAY_DEBUG', $config['database']['display_error'] ); //display db errors?
        define( 'DB_COMMA',  '`'); // back tick 
        $ip = file_get_contents("http://ipecho.net/plain");
    global $mybb,$templates,$lang;
	$settings = $mybb->settings;
	$user = $mybb->user;
	$config = $mybb->config;
	$rdb = new db();
	$sql = 'select * from server1 order by server_name ASC';
	$games = $rdb->get_results($sql);
	foreach ($games as $game) {
		// get game detail
		if(empty($game['starttime'])) { $game['starttime']=0;}
		$start = date("d-m-y  h:i:s a",$game['starttime']);
		
		$fname = $game['host_name'];
		$disp ='style="display:none;"';
		$gd .='<tr id="'.$fname.'" '.$disp.'><td id="host'.$fname.'"><span style="text-align:center;min-width:100%;font-weight:bold;">Starting</span></td><td id="cmap'.$fname.'"></td><td style="text-align:center;"><span id="gol'.$fname.'" style="float:right;padding-right:25%;"></span style="padding-left:19%;"></td><td style="text-align:center;">'.$start.'</td></tr>'; 
	} 
	$sql = 'SELECT * FROM base_servers where enabled = 1 and extraip=0';
	$bases = $rdb->get_results($sql);
	foreach ($bases as $base) {
		// parent servers
		if ($base['ip'] == $ip) {
			//
			$location = 'local';
		}
		else {
			$location ='remote';
		}
		if ($base['reboot'] ){
			//
			$base['fname'] = '<span style="color:red;">'.$base['fname'].'</span>';
		}
		$bs .= '<tr><td>'.$base['fname'].'</td><td>'.$base['url'].':'.$base['port'].'<td>'.$location.'</td></tr>';
	}
	$sql = $sql = "select servers.server_name,player_history.*,players.name,players.country_code from player_history left join players on `steam_id` = players.steam_id64 left join servers on player_history.`game` = servers.host_name  ORDER BY `player_history`.`log_ons` DESC LIMIT 10";
	$players = $rdb->get_results($sql);
	foreach ($players as $player) {
		// top 10 players
		$playerN2 = Emoji::Decode($player['name']);
		$player['last_play'] = date('d-m-Y h:i:s a',$player['last_play']);
		$map = '<img style="width:10%;vertical-align: middle;" src="https://ipdata.co/flags/'.trim(strtolower($player['country_code'])).'.png">';
		$pd.='<tr><td style="vertical-align: middle;">'.$map.'  '.$playerN2.'</td><td>'.$player['server_name'].'</td><td style="text-align:right;"><span style="padding-right:70%;">'.$player['log_ons'].'</span></td><td>'.$player['last_play'].'</td></tr>';
	}
	$sql = 'select * from logins limit 10';
	$countries =  $rdb->get_results($sql);
	foreach ($countries as $country) {
		//
		
		$map = '<img style="width:6%;vertical-align: middle;" src="https://ipdata.co/flags/'.trim(strtolower($country['country_code'])).'.png">';
		$ct .= '<tr><td>'.$map.'  '.$country['country'].'</td><td>'.$country['players'].'</td><td style="text-align:right;"><span style="padding-right:70%;">'.$country['logins'].'</span></td><td></td></tr>'; 
	}	
	$table = new Table();
	$page->add_breadcrumb_item('PHPgsm', 'index.php?module=phpgsm');
    $page->add_breadcrumb_item('Statistics', 'index.php?module=phpgsm-stats');
    $page->output_header('PHPgsm Statistics');
	
	eval("\$content .=  \"".$templates->get("admin_phpgsm_stats")."\";"); 
	echo $content;
	$page->output_footer();
?>