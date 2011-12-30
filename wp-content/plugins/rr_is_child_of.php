<?php
/*
Plugin Name: Is Child Of
Version: 0.5
Plugin URI: http://www.schloebe.de/wordpress/is-subpage-of-plugin/
Description: This plugin can check if a page is a descendant page of a top level page, regardless of how many levels there are in between them
Author: Luke Williams
Author URI: http://www.red-root.com
*/
/*  Copyright 2009  Luke Williams (email : luke@red-root.com)

	is_child_of is released under the GNU General Public
	License: http://www.gnu.org/licenses/gpl.txt

	This is a WordPress plugin (http://wordpress.org). WordPress is
	free software; you can redistribute it and/or modify it under the
	terms of the GNU General Public License as published by the Free
	Software Foundation; either version 2 of the License, or (at your
	option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
	General Public License for more details.

	For a copy of the GNU General Public License, write to:

	Free Software Foundation, Inc.
	59 Temple Place, Suite 330
	Boston, MA  02111-1307
	USA

	You can also view a copy of the HTML version of the GNU General
	Public License at http://www.gnu.org/copyleft/gpl.html
*/
function is_child_of($topid, $thispageid = null)
{
	global $post;
	
	if($thispageid == null)
		$thispageid = $post->ID; # no id set so get the post object's id.
		
	$current = get_page($thispageid);
	
	if($current->post_parent != 0) # so there is a parent
	{
		if($current->post_parent != $topid)
			return is_child_of($topid, $current->post_parent); # not that page, run again
		else
			return true; # are so it is	
	}
	else
	{
		return false; # no parent page so return false
	}	
}
?>