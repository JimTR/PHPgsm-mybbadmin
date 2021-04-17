<?php
/*
 * ajax.php
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
 *  mybb  local ajax
 * 
 */
		require_once( dirname(__FILE__,5).'/inc/settings.php');
		require_once(  dirname(__FILE__,5).'/inc/class.dbquick.php' ); // load the class
		require  dirname(__FILE__,4).'/inc/Emoji.php';
	    $ip = file_get_contents("http://ipecho.net/plain");
	  
		define( 'DB_HOST', $settings['phpgsm_host'] ); // set database host
		define( 'DB_USER', $settings['phpgsm_user'] ); // set database user
		define( 'DB_PASS', $settings['phpgsm_password'] ); // set database password
		define( 'DB_NAME', $settings['phpgsm_db'] ); // set database name
		define( 'DB_COMMA',  '`'); // back tick 
		$rdb = new db();
		if (isset($_GET)) {
			$cmds = $_GET;
		}
		else {
				$cmds = $_POST;
			}
		$pd = '';
		$server = '';
		if ($cmds['server'] == 'all') {
			unset($cmds['server']);
		}
		//$server = $cmds['server'];
		if (isset($cmds['server'])) {
			$server = " where servers.host_name like '".$cmds['server']."'";
		}
		$sql = "select servers.server_name,player_history.*,players.name,players.country_code from player_history left join players on `steam_id` = players.steam_id64 left join servers on player_history.`game` = servers.host_name  $server ORDER BY `player_history`.`log_ons` DESC LIMIT 10";
		echo $sql.'<br>';
		$players = $rdb->get_results($sql);
		foreach ($players as $player) {
		$playerN2 = Emoji::Decode($player['name']);
		$player['last_play'] = date('d-m-Y h:i:s a',$player['last_play']);
		$map = '<img style="width:10%;vertical-align: middle;" src="https://ipdata.co/flags/'.trim(strtolower($player['country_code'])).'.png">';
		$pd.='<tr><td style="vertical-align: middle;">'.$map.'  '.$playerN2.'</td><td>'.$player['server_name'].'</td><td style="text-align:right;"><span style="padding-right:70%;">'.$player['log_ons'].'</span></td><td>'.$player['last_play'].'</td></tr>';
		}
		echo $pd;
?>
