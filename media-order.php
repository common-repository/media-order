<?php
/*
Plugin Name: Media Order
Plugin URI: http://drewcovi.com
Description: This plugin enhances the existing WordPress Media Library; allowing you to set the menu_order of attachments.
Version: 0.2
Author: Drew Covi
Author URI: http://drewcovi.com
*/
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

define( 'MEDIA_ORDER_URL', WP_PLUGIN_URL . '/media-order' );

if (!class_exists("Media_Order")) :

class Media_Order{
	var $media_ids= array();
	
	function Media_Order(){
		add_action('wp_headers', array(&$this, 'update_order'),30, 2);
		add_filter('manage_media_columns', array(&$this,'add_admin_columns'), 33);
		add_action('manage_media_custom_column', array(&$this,'manage_admin_columns'), 34, 2);
		wp_register_script('enhancementScript', MEDIA_ORDER_URL . '/extra.js');
		wp_enqueue_script('enhancementScript');
	}
	
	function update_order($headers, $current){
		if ( $referer = wp_get_referer() ) {
			if ( false !== strpos($referer, 'upload.php') || false !== strpos($referer, 'post.php') ){	
				if(isset($_REQUEST['attachments'])){
					global $wpdb;
					foreach($_REQUEST['attachments'] as $key=>$value){
						$value = $value['menu_order'];
						$updated = $wpdb->query( $wpdb->prepare("UPDATE $wpdb->posts SET menu_order = %d WHERE post_type = 'attachment' AND ID = %d", $value, $key) );
						if($updated){
							$response[success] = true;
							echo json_encode($response);
						}
						exit;
					}
				}
			}
		}
	}

	function add_admin_columns($posts_columns) {
		$new_columns = $posts_columns;
		$new_columns['menu_order'] = _x( 'Order', 'column name' );
		return $new_columns;
	}
	
	function manage_admin_columns($column_name, $id) {
		global $post;
		array_push($this->media_ids, $id);
		switch($column_name) {
			case 'menu_order':
				echo '<input name="attachments['.$post->ID.'][menu_order]" value="'.$post->menu_order.'" />';
				echo $screen->id;
				break;
			default:
				break;
		
			}
	}	
}

endif;

if (class_exists("Media_Order")) {
    $media_order = new Media_Order();	
}
?>