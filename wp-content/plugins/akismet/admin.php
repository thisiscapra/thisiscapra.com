<?php
add_action( 'admin_menu', 'akismet_config_page' );
add_action( 'admin_menu', 'akismet_stats_page' );
akismet_admin_warnings();

function akismet_admin_init() {
    global $wp_version;
    
    // all admin functions are disabled in old versions
    if ( version_compare( $wp_version, '3.0', '<' ) ) {
        
        function akismet_version_warning() {
            echo "
            <div id='akismet-warning' class='updated fade'><p><strong>".sprintf(__('Akismet %s required WordPress 3.0 or higher.'), AKISMET_VERSION) ."</strong> ".sprintf(__('Please <a href="%s">upgrade WordPress</a> to a current version, or <a href="%s">downgrade to version 2.4 of the Akismet plugin</a>.'), 'http://codex.wordpress.org/Upgrading_WordPress', 'http://wordpress.org/extend/plugins/akismet/download/'). "</p></div>
            ";
        }
        add_action('admin_notices', 'akismet_version_warning'); 
        
        return; 
    }

    if ( function_exists( 'get_plugin_page_hook' ) )
        $hook = get_plugin_page_hook( 'akismet-stats-display', 'index.php' );
    else
        $hook = 'dashboard_page_akismet-stats-display';
    add_action('admin_head-'.$hook, 'akismet_stats_script');
    add_meta_box('akismet-status', __('Akismet Status'), 'akismet_comment_status_meta_box', 'comment', 'normal');
}
add_action('admin_init', 'akismet_admin_init');

function akismet_nonce_field($action = -1) { return wp_nonce_field($action); }
$akismet_nonce = 'akismet-update-key';

function akismet_config_page() {
	if ( function_exists('add_submenu_page') )
		add_submenu_page('plugins.php', __('Akismet Configuration'), __('Akismet Configuration'), 'manage_options', 'akismet-key-config', 'akismet_conf');

}

function akismet_conf() {
	global $akismet_nonce, $wpcom_api_key;

	if ( isset($_POST['submit']) ) {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?'));

		check_admin_referer( $akismet_nonce );
		$key = preg_replace( '/[^a-h0-9]/i', '', $_POST['key'] );

		if ( empty($key) ) {
			$key_status = 'empty';
			$ms[] = 'new_key_empty';
			delete_option('wordpress_api_key');
		} else {
			$key_status = akismet_verify_key( $key );
		}

		if ( $key_status == 'valid' ) {
			update_option('wordpress_api_key', $key);
			$ms[] = 'new_key_valid';
		} else if ( $key_status == 'invalid' ) {
			$ms[] = 'new_key_invalid';
		} else if ( $key_status == 'failed' ) {
			$ms[] = 'new_key_failed';
		}

		if ( isset( $_POST['akismet_discard_month'] ) )
			update_option( 'akismet_discard_month', 'true' );
		else
			update_option( 'akismet_discard_month', 'false' );
	} elseif ( isset($_POST['check']) ) {
		akismet_get_server_connectivity(0);
	}

	if ( empty( $key_status) ||  $key_status != 'valid' ) {
		$key = get_option('wordpress_api_key');
		if ( empty( $key ) ) {
			if ( empty( $key_status ) || $key_status != 'failed' ) {
				if ( akismet_verify_key( '1234567890ab' ) == 'failed' )
					$ms[] = 'no_connection';
				else
					$ms[] = 'key_empty';
			}
			$key_status = 'empty';
		} else {
			$key_status = akismet_verify_key( $key );
		}
		if ( $key_status == 'valid' ) {
			$ms[] = 'key_valid';
		} else if ( $key_status == 'invalid' ) {
			delete_option('wordpress_api_key');
			$ms[] = 'key_empty';
		} else if ( !empty($key) && $key_status == 'failed' ) {
			$ms[] = 'key_failed';
		}
	}

	$messages = array(
		'new_key_empty' => array('color' => 'aa0', 'text' => __('Your key has been cleared.')),
		'new_key_valid' => array('color' => '2d2', 'text' => __('Your key has been verified. Happy blogging!')),
		'new_key_invalid' => array('color' => 'd22', 'text' => __('The key you entered is invalid. Please double-check it.')),
		'new_key_failed' => array('color' => 'd22', 'text' => __('The key you entered could not be verified because a connection to akismet.com could not be established. Please check your server configuration.')),
		'no_connection' => array('color' => 'd22', 'text' => __('There was a problem connecting to the Akismet server. Please check your server configuration.')),
		'key_empty' => array('color' => 'aa0', 'text' => sprintf(__('Please enter an API key. (<a href="%s" style="color:#fff">Get your key.</a>)'), 'http://akismet.com/get/')),
		'key_valid' => array('color' => '2d2', 'text' => __('This key is valid.')),
		'key_failed' => array('color' => 'aa0', 'text' => __('The key below was previously validated but a connection to akismet.com can not be established at this time. Please check your server configuration.')));
?>
<?php if ( !empty($_POST['submit'] ) ) : ?>
<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
<h2><?php _e('Akismet Configuration'); ?></h2>
<div class="narrow">
<form action="" method="post" id="akismet-conf" style="margin: auto; width: 400px; ">
<?php if ( !$wpcom_api_key ) { ?>
	<p><?php printf(__('For many people, <a href="%1$s">Akismet</a> will greatly reduce or even completely eliminate the comment and trackback spam you get on your site. If one does happen to get through, simply mark it as "spam" on the moderation screen and Akismet will learn from the mistakes. If you don\'t have an API key yet, you can get one at <a href="%2$s">Akismet.com</a>.'), 'http://akismet.com/', 'http://akismet.com/get/'); ?></p>

<h3><label for="key"><?php _e('Akismet API Key'); ?></label></h3>
<?php foreach ( $ms as $m ) : ?>
	<p style="padding: .5em; background-color: #<?php echo $messages[$m]['color']; ?>; color: #fff; font-weight: bold;"><?php echo $messages[$m]['text']; ?></p>
<?php endforeach; ?>
<p><input id="key" name="key" type="text" size="15" maxlength="12" value="<?php echo get_option('wordpress_api_key'); ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;" /> (<?php _e('<a href="http://akismet.com/get/">What is this?</a>'); ?>)</p>
<?php if ( isset( $invalid_key) && $invalid_key ) { ?>
<h3><?php _e('Why might my key be invalid?'); ?></h3>
<p><?php _e('This can mean one of two things, either you copied the key wrong or that the plugin is unable to reach the Akismet servers, which is most often caused by an issue with your web host around firewalls or similar.'); ?></p>
<?php } ?>
<?php } ?>
<?php akismet_nonce_field($akismet_nonce) ?>
<p><label><input name="akismet_discard_month" id="akismet_discard_month" value="true" type="checkbox" <?php if ( get_option('akismet_discard_month') == 'true' ) echo ' checked="checked" '; ?> /> <?php _e('Automatically discard spam comments on posts older than a month.'); ?></label></p>
	<p class="submit"><input type="submit" name="submit" value="<?php _e('Update options &raquo;'); ?>" /></p>
</form>

<form action="" method="post" id="akismet-connectivity" style="margin: auto; width: 400px; ">

<h3><?php _e('Server Connectivity'); ?></h3>
<?php
	if ( !function_exists('fsockopen') || !function_exists('gethostbynamel') ) {
		?>
			<p style="padding: .5em; background-color: #d22; color: #fff; font-weight:bold;"><?php _e('Network functions are disabled.'); ?></p>
			<p><?php echo sprintf( __('Your web host or server administrator has disabled PHP\'s <code>fsockopen</code> or <code>gethostbynamel</code> functions.  <strong>Akismet cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about Akismet\'s system requirements</a>.'), 'http://blog.akismet.com/akismet-hosting-faq/'); ?></p>
		<?php
	} else {
		$servers = akismet_get_server_connectivity();
		$fail_count = count($servers) - count( array_filter($servers) );
		if ( is_array($servers) && count($servers) > 0 ) {
			// some connections work, some fail
			if ( $fail_count > 0 && $fail_count < count($servers) ) { ?>
				<p style="padding: .5em; background-color: #aa0; color: #fff; font-weight:bold;"><?php _e('Unable to reach some Akismet servers.'); ?></p>
				<p><?php echo sprintf( __('A network problem or firewall is blocking some connections from your web server to Akismet.com.  Akismet is working but this may cause problems during times of network congestion.  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about Akismet and firewalls</a>.'), 'http://blog.akismet.com/akismet-hosting-faq/'); ?></p>
			<?php
			// all connections fail
			} elseif ( $fail_count > 0 ) { ?>
				<p style="padding: .5em; background-color: #d22; color: #fff; font-weight:bold;"><?php _e('Unable to reach any Akismet servers.'); ?></p>
				<p><?php echo sprintf( __('A network problem or firewall is blocking all connections from your web server to Akismet.com.  <strong>Akismet cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about Akismet and firewalls</a>.'), 'http://blog.akismet.com/akismet-hosting-faq/'); ?></p>
			<?php
			// all connections work
			} else { ?>
				<p style="padding: .5em; background-color: #2d2; color: #fff; font-weight:bold;"><?php  _e('All Akismet servers are available.'); ?></p>
				<p><?php _e('Akismet is working correctly.  All servers are accessible.'); ?></p>
			<?php
			}
		} else {
			?>
				<p style="padding: .5em; background-color: #d22; color: #fff; font-weight:bold;"><?php _e('Unable to find Akismet servers.'); ?></p>
				<p><?php echo sprintf( __('A DNS problem or firewall is preventing all access from your web server to Akismet.com.  <strong>Akismet cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about Akismet and firewalls</a>.'), 'http://blog.akismet.com/akismet-hosting-faq/'); ?></p>
			<?php
		}
	}
	
	if ( !empty($servers) ) {
?>
<table style="width: 100%;">
<thead><th><?php _e('Akismet server'); ?></th><th><?php _e('Network Status'); ?></th></thead>
<tbody>
<?php
		asort($servers);
		foreach ( $servers as $ip => $status ) {
			$color = ( $status ? '#2d2' : '#d22');
	?>
		<tr>
		<td><?php echo htmlspecialchars($ip); ?></td>
		<td style="padding: 0 .5em; font-weight:bold; color: #fff; background-color: <?php echo $color; ?>"><?php echo ($status ? __('No problems') : __('Obstructed') ); ?></td>
		
	<?php
		}
	}
?>
</tbody>
</table>
	<p><?php if ( get_option('akismet_connectivity_time') ) echo sprintf( __('Last checked %s ago.'), human_time_diff( get_option('akismet_connectivity_time') ) ); ?></p>
	<p class="submit"><input type="submit" name="check" value="<?php _e('Check network status &raquo;'); ?>" /></p>
</form>

</div>
</div>
<?php
}

function akismet_stats_page() {
	if ( function_exists('add_submenu_page') )
		add_submenu_page('index.php', __('Akismet Stats'), __('Akismet Stats'), 'manage_options', 'akismet-stats-display', 'akismet_stats_display');

}

function akismet_stats_script() {
	?>
<script type="text/javascript">
function resizeIframe() {
    var height = document.documentElement.clientHeight;
    height -= document.getElementById('akismet-stats-frame').offsetTop;
    height += 100; // magic padding
    
    document.getElementById('akismet-stats-frame').style.height = height +"px";
    
};
function resizeIframeInit() {
	document.getElementById('akismet-stats-frame').onload = resizeIframe;
	window.onresize = resizeIframe;
}
addLoadEvent(resizeIframeInit);
</script><?php
}


function akismet_stats_display() {
	global $akismet_api_host, $akismet_api_port, $wpcom_api_key;
	$blog = urlencode( get_option('home') );

	$url = 'http://';
	if ( is_ssl() )
		$url = 'https://';

	$url .= 'akismet.com/web/1.0/user-stats.php';
	$url .= "?blog={$blog}&api_key=" . akismet_get_key();
	?>
	<div class="wrap">
	<iframe src="<?php echo $url; ?>" width="100%" height="100%" frameborder="0" id="akismet-stats-frame"></iframe>
	</div>
	<?php
}

function akismet_stats() {
	if ( !function_exists('did_action') || did_action( 'rightnow_end' ) ) // We already displayed this info in the "Right Now" section
		return;
	if ( !$count = get_option('akismet_spam_count') )
		return;
	$path = plugin_basename(__FILE__);
	echo '<h3>'.__('Spam').'</h3>';
	global $submenu;
	if ( isset( $submenu['edit-comments.php'] ) )
		$link = 'edit-comments.php';
	else
		$link = 'edit.php';
	echo '<p>'.sprintf(__('<a href="%1$s">Akismet</a> has protected your site from <a href="%2$s">%3$s spam comments</a>.'), 'http://akismet.com/', clean_url("$link?page=akismet-admin"), number_format_i18n($count) ).'</p>';
}
add_action('activity_box_end', 'akismet_stats');

function akismet_admin_warnings() {
	global $wpcom_api_key;
	if ( !get_option('wordpress_api_key') && !$wpcom_api_key && !isset($_POST['submit']) ) {
		function akismet_warning() {
			echo "
			<div id='akismet-warning' class='updated fade'><p><strong>".__('Akismet is almost ready.')."</strong> ".sprintf(__('You must <a href="%1$s">enter your Akismet API key</a> for it to work.'), "plugins.php?page=akismet-key-config")."</p></div>
			";
		}
		add_action('admin_notices', 'akismet_warning');
		return;
	} elseif ( get_option('akismet_connectivity_time') && empty($_POST) && is_admin() && !akismet_server_connectivity_ok() ) {
		function akismet_warning() {
			echo "
			<div id='akismet-warning' class='updated fade'><p><strong>".__('Akismet has detected a problem.')."</strong> ".sprintf(__('A server or network problem is preventing Akismet from working correctly.  <a href="%1$s">Click here for more information</a> about how to fix the problem.'), "plugins.php?page=akismet-key-config")."</p></div>
			";
		}
		add_action('admin_notices', 'akismet_warning');
		return;
	}
}

// FIXME placeholder

function akismet_comment_row_action( $a, $comment ) {
	
	
	$akismet_result = get_comment_meta( $comment->comment_ID, 'akismet_result', true );
	$user_result = get_comment_meta( $comment->comment_ID, 'akismet_user_result', true);
	$desc = null;
	if ( !$user_result || $user_result == $akismet_result ) {
		// Show the original Akismet result if the user hasn't overridden it, or if their decision was the same
		if ( $akismet_result == 'true' )
			$desc = 'Flagged as spam by Akismet';
		elseif ( $akismet_result == 'false' )
			$desc = 'Cleared by Akismet';
	} else {
		$who = get_comment_meta( $comment->comment_ID, 'akismet_user', true );
		if ( $user_result == 'true' )
			$desc = sprintf( __('Flagged as spam by %s'), $who );
		else
			$desc = sprintf( __('Un-spammed by %s'), $who );
	}
	
	if ( $desc )
		echo '<span style="background: #EEE; border: 1px solid #E4E4E4; margin-top: 3px; color: #999; padding: 1px 8px 2px 8px; -moz-border-radius:6px; border-radius:6px; -webkit-border-radius:6px; float: right; line-height: 1.2em;"><a href="comment.php?action=editcomment&amp;c='.$comment->comment_ID.'#akismet-status" title="' . esc_attr__( 'View comment history' ) . '">'.htmlspecialchars($desc).'</a></span>';
	
	return $a;
}

add_filter( 'comment_row_actions', 'akismet_comment_row_action', 10, 2 );


function akismet_comment_status_meta_box($comment) {
	$history = akismet_get_comment_history( $comment->comment_ID );

	if ( $history ) {
		echo '<div class="akismet-history" style="margin: 13px;">';
		foreach ( $history as $row ) {
			$time = date( 'D d M Y @ h:i:m a', $row['time'] ) . ' GMT';
			echo '<div style="margin-bottom: 13px;"><span style="color: #999;" alt="' . $time . '" title="' . $time . '">' . sprintf( __('%s ago'), human_time_diff( $row['time'] ) ) . '</span> - ';
			echo htmlspecialchars( $row['message'] ) . '</div>';
		}
		
		echo '</div>';

	}
}


// add an extra column header to the comments screen
function akismet_comments_columns( $columns ) {
	$columns[ 'akismet' ] = __( 'Akismet' );
	return $columns;
}

#add_filter( 'manage_edit-comments_columns', 'akismet_comments_columns' );

// Show stuff in the extra column
function akismet_comment_column_row( $column, $comment_id ) {
	if ( $column != 'akismet' )
		return;
		
	$history = akismet_get_comment_history( $comment_id );
	
	if ( $history ) {
		echo '<dl class="akismet-history">';
		foreach ( $history as $row ) {
			echo '<dt>' . sprintf( __('%s ago'), human_time_diff( $row['time'] ) ) . '</dt>';
			echo '<dd>' . htmlspecialchars( $row['message'] ) . '</dd>';
		}
		
		echo '</dl>';
	}
}

#add_action( 'manage_comments_custom_column', 'akismet_comment_column_row', 10, 2 );

// END FIXME

// WP 2.5+
function akismet_rightnow() {
	global $submenu, $wp_db_version;

	$plural_func = '__ngettext';
	if ( function_exists( '_n' ) )
		$plural_func = '_n';

	// clean_url was deprecated in WP 3.0
	$esc_url = 'clean_url';
	if ( function_exists( 'esc_url' ) )
		$esc_url = 'esc_url';

	if ( 8645 < $wp_db_version  ) // 2.7
		$link = 'edit-comments.php?comment_status=spam';
	elseif ( isset( $submenu['edit-comments.php'] ) )
		$link = 'edit-comments.php?page=akismet-admin';
	else
		$link = 'edit.php?page=akismet-admin';

	if ( $count = get_option('akismet_spam_count') ) {
		$intro = sprintf( $plural_func(
			'<a href="%1$s">Akismet</a> has protected your site from %2$s spam comment already,',
			'<a href="%1$s">Akismet</a> has protected your site from %2$s spam comments already,',
			$count
		), 'http://akismet.com/', number_format_i18n( $count ) );
	} else {
		$intro = sprintf( __('<a href="%1$s">Akismet</a> blocks spam from getting to your blog,'), 'http://akismet.com/' );
	}

	if ( $queue_count = akismet_spam_count() ) {
		$queue_text = sprintf( $plural_func(
			'and there\'s <a href="%2$s">%1$s comment</a> in your spam queue right now.',
			'and there are <a href="%2$s">%1$s comments</a> in your spam queue right now.',
			$queue_count
		), number_format_i18n( $queue_count ), $esc_url($link) );
	} else {
		$queue_text = sprintf( __( " but there's nothing in your <a href='%1\$s'>spam queue</a> at the moment." ), $esc_url($link) );
	}

	// _c was deprecated in WP 2.9.0
	if ( function_exists( '_x' ) )
		$text = sprintf( _x( '%1$s%2$s', 'akismet_rightnow' ), $intro, $queue_text );
	else 
		$text = sprintf( _c( '%1$s%2$s|akismet_rightnow' ), $intro, $queue_text );

	echo "<p class='akismet-right-now'>$text</p>\n";
}
	
add_action('rightnow_end', 'akismet_rightnow');


// For WP >= 2.5
function akismet_check_for_spam_button($comment_status) {
	if ( 'approved' == $comment_status )
		return;
	if ( function_exists('plugins_url') )
		$link = 'admin.php?action=akismet_recheck_queue';
	else
		$link = 'edit-comments.php?page=akismet-admin&amp;recheckqueue=true&amp;noheader=true';
	echo "</div><div class='alignleft'><a class='button-secondary checkforspam' href='$link'>" . __('Check for Spam') . "</a>";
}
add_action('manage_comments_nav', 'akismet_check_for_spam_button');

function akismet_submit_nonspam_comment ( $comment_id ) {
	global $wpdb, $akismet_api_host, $akismet_api_port, $current_user, $current_site;
	$comment_id = (int) $comment_id;

	$comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID = '$comment_id'");
	if ( !$comment ) // it was deleted
		return;
	$comment->blog = get_option('home');
	$comment->blog_lang = get_locale();
	$comment->blog_charset = get_option('blog_charset');
	$comment->permalink = get_permalink($comment->comment_post_ID);
	if ( is_object($current_user) ) {
	    $comment->reporter = $current_user->user_login;
	}
	if ( is_object($current_site) ) {
		$comment->site_domain = $current_site->domain;
	}

	$comment->user_role = '';
	if ( isset( $comment->user_ID ) )
		$comment->user_role = akismet_get_user_roles($comment->user_ID);

	$query_string = '';
	foreach ( $comment as $key => $data )
		$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';

	$response = akismet_http_post($query_string, $akismet_api_host, "/1.1/submit-ham", $akismet_api_port);
	if ( $comment->reporter ) {
		akismet_update_comment_history( $comment_id, sprintf( __('%s un-spammed this comment'), $comment->reporter ), 'report-ham' );
		update_comment_meta( $comment_id, 'akismet_user_result', 'false' );
		update_comment_meta( $comment_id, 'akismet_user', $comment->reporter );
	} else {
		akismet_update_comment_history( $comment_id, 'A plugin un-spammed this comment', 'report-ham' );
	}
	
	do_action('akismet_submit_nonspam_comment', $comment_id, $response[1]);
}

function akismet_submit_spam_comment ( $comment_id ) {
	global $wpdb, $akismet_api_host, $akismet_api_port, $current_user, $current_site;
	$comment_id = (int) $comment_id;

	$comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID = '$comment_id'");
	if ( !$comment ) // it was deleted
		return;
	if ( 'spam' != $comment->comment_approved )
		return;
	$comment->blog = get_option('home');
	$comment->blog_lang = get_locale();
	$comment->blog_charset = get_option('blog_charset');
	$comment->permalink = get_permalink($comment->comment_post_ID);
	if ( is_object($current_user) ) {
	    $comment->reporter = $current_user->user_login;
	}
	if ( is_object($current_site) ) {
		$comment->site_domain = $current_site->domain;
	}

	$comment->user_role = '';
	if ( !isset( $comment->user_id ) )
		$comment->user_role = akismet_get_user_roles($comment->user_ID);

	$query_string = '';
	foreach ( $comment as $key => $data )
		$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';

	$response = akismet_http_post($query_string, $akismet_api_host, "/1.1/submit-spam", $akismet_api_port);
	if ( $comment->reporter ) {
		akismet_update_comment_history( $comment_id, sprintf( __('%s spammed this comment'), $comment->reporter ), 'report-spam' );
		update_comment_meta( $comment_id, 'akismet_user_result', 'true' );
		update_comment_meta( $comment_id, 'akismet_user', $comment->reporter );
	} else
		akismet_update_comment_history( $comment_id, 'A plugin spammed this comment', 'report-ham' );
	do_action('akismet_submit_spam_comment', $comment_id, $response[1]);
}

// For WP 2.7+
function akismet_transition_comment_status( $new_status, $old_status, $comment ) {
	if ( $new_status == $old_status )
		return;
		
	if ( $new_status == 'spam' ) {
		akismet_submit_spam_comment( $comment->comment_ID );
	} elseif ( $old_status == 'spam' && ( $new_status == 'approved' || $new_status == 'unapproved' ) ) {
		akismet_submit_nonspam_comment( $comment->comment_ID );
	}
}

add_action( 'transition_comment_status', 'akismet_transition_comment_status', 10, 3 );

// Total spam in queue
// get_option( 'akismet_spam_count' ) is the total caught ever
function akismet_spam_count( $type = false ) {
	global $wpdb;

	if ( !$type ) { // total
		$count = wp_cache_get( 'akismet_spam_count', 'widget' );
		if ( false === $count ) {
			if ( function_exists('wp_count_comments') ) {
				$count = wp_count_comments();
				$count = $count->spam;
			} else {
				$count = (int) $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = 'spam'");
			}
			wp_cache_set( 'akismet_spam_count', $count, 'widget', 3600 );
		}
		return $count;
	} elseif ( 'comments' == $type || 'comment' == $type ) { // comments
		$type = '';
	} else { // pingback, trackback, ...
		$type  = $wpdb->escape( $type );
	}

	return (int) $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = 'spam' AND comment_type='$type'");
}


function akismet_recheck_queue() {
	global $wpdb, $akismet_api_host, $akismet_api_port;

	if ( ! ( isset( $_GET['recheckqueue'] ) || ( isset( $_REQUEST['action'] ) && 'akismet_recheck_queue' == $_REQUEST['action'] ) ) )
		return;
		
	$moderation = $wpdb->get_results( "SELECT * FROM $wpdb->comments WHERE comment_approved = '0'", ARRAY_A );
	foreach ( (array) $moderation as $c ) {
		$c['user_ip']    = $c['comment_author_IP'];
		$c['user_agent'] = $c['comment_agent'];
		$c['referrer']   = '';
		$c['blog']       = get_option('home');
		$c['blog_lang']  = get_locale();
		$c['blog_charset'] = get_option('blog_charset');
		$c['permalink']  = get_permalink($c['comment_post_ID']);

		$c['user_role'] = '';
		if ( isset( $c['user_ID'] ) )
			$c['user_role']  = akismet_get_user_roles($c['user_ID']);

		$id = (int) $c['comment_ID'];

		$query_string = '';
		foreach ( $c as $key => $data )
		$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';

		$response = akismet_http_post($query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);
		if ( 'true' == $response[1] ) {
			wp_set_comment_status($c['comment_ID'], 'spam');
			update_comment_meta( $c['comment_ID'], 'akismet_result', 'true' );
			akismet_update_comment_history( $c['comment_ID'], __('Akismet re-checked and caught this comment as spam'), 'check-spam' );
		
		} elseif ( 'false' == $response[1] ) {
			update_comment_meta( $c['comment_ID'], 'akismet_result', 'false' );
			akismet_update_comment_history( $c['comment_ID'], __('Akismet re-checked and cleared this comment'), 'check-ham' );
		// abnormal result: error
		} else {
			update_comment_meta( $c['comment_ID'], 'akismet_result', 'error' );
			akismet_update_comment_history( $c['comment_ID'], sprintf( __('Akismet was unable to re-check this comment (response: %s)'), $response[1]), 'check-error' );
		}

	}
	wp_redirect( $_SERVER['HTTP_REFERER'] );
	exit;
}

add_action('admin_action_akismet_recheck_queue', 'akismet_recheck_queue');
