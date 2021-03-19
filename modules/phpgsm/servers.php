<?php
/*
 * servers.php
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
    global $mybb,$templates,$lang;
	$settings = $mybb->settings;
	$user = $mybb->user;
	$config = $mybb->config;
	
    require_once( 'inc/class.dbquick.php' ); // load the class
	define( 'DB_HOST', $settings['phpgsm_host'] ); // set database host
    define( 'DB_USER', $settings['phpgsm_user'] ); // set database user
    define( 'DB_PASS', $settings['phpgsm_password'] ); // set database password
    define( 'DB_NAME', $settings['phpgsm_db'] ); // set database name
        
   
	$rdb = new db();
	$page->add_breadcrumb_item('PHPgsm', 'index.php?module=phpgsm');
    $page->add_breadcrumb_item('Parent Servers', 'index.php?module=phpgsm-servers');
    $page->output_header('PHPgsm Parent Servers');
    $sql = 'SELECT * from base_servers where enabled = 1 and extraip=0';
	$bases = $rdb->get_results($sql);
	foreach ($bases as $base) {
		// init templates
		 $fname = $base['fname'];	
		 eval("\$hardware .=  \"".$templates->get("phpgsm_base_server_hardware")."\";"); 
		 eval("\$software .=  \"".$templates->get("phpgsm_base_server_software")."\";"); 
		 eval("\$disk .=  \"".$templates->get("phpgsm_base_server_disk")."\";"); 
		 eval("\$memory .=  \"".$templates->get("phpgsm_base_server_memory")."\";"); 
		 eval("\$games .=  \"".$templates->get("phpgsm_base_server_games")."\";");
		 eval("\$base_servers .= \"".$templates->get("phpgsm_base_server_bit")."\";"); 
		 unset ($hardware);
		 unset($software);
		 unset($disk);
		 unset($memory);
		 unset($games);
	}
    eval("\$content .=  \"".$templates->get("admin_phpgsm_parents")."\";"); 
	echo $content;
	$page->output_footer();
?>
