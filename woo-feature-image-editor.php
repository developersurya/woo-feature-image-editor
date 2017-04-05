<?php
/*
Plugin Name: woo feature image editor
Description: Allow visiters to have ability to change the name/number etc in product image.
Version: 9.9.4
Author: Surya Manandhar
Author URI: http://ktmfreelancer.com.np
Plugin URI: https://wordpress.org/plugins/woo feature image editor/
Github URI: https://github.com/Mahjouba91/woo feature image editor
Text Domain: woo feature image editor
*/
define( 'WFIE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WFIE_URL', plugin_dir_url( __FILE__ ) );
//protection from direct access
if (!defined('ABSPATH')) {
exit;
}
/**
* Check if WooCommerce is active
**/
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
return;
}
/**
* Proper way to enqueue scripts and styles
*/
add_action('wp_enqueue_scripts','wpdocs_theme_name_scripts');
add_action('woocommerce_before_add_to_cart_button','add_image_editor_form_html');
add_action("wp_ajax_save_edited_data", "save_edited_data");
add_action("wp_ajax_nopriv_save_edited_data", "save_edited_data");
/**
* Load files and add hooks to get things rolling.
*/

function wpdocs_theme_name_scripts()
{
wp_enqueue_style('wfiestyle', plugins_url('woo-feature-image-editor/css/wfiestyle.css'));
wp_enqueue_script( 'html2canvas', plugins_url() . '/woo-feature-image-editor/js/html2canvas.js', array(), '1.0.0', true );
wp_enqueue_script( 'wfiescript', plugins_url() . '/woo-feature-image-editor/js/wfiescript.js', array(), '1.0.0', true );
wp_enqueue_script( 'jscolor', plugins_url() . '/woo-feature-image-editor/js/jscolor.js', array(), '1.0.0', true );
}
/**
* [add_image_editor_form_html description]
*/
function add_image_editor_form_html(){
	if( has_term( array('Editable', 'term2', 'term3'), 'product_cat' ) ) {
		 $fontfamily = get_post_meta( get_the_ID(), "_text_field_font", true ); 
		 $fontcolor = get_post_meta( get_the_ID(), "_text_field_color", true ); 
		 $fontsize = get_post_meta( get_the_ID(), "_number_field_font_size", true ); 
		 $fontsizename = get_post_meta( get_the_ID(), "_name_field_font_size", true ); 
		 $_customcolor_field_font_size = get_post_meta( get_the_ID(), "_customcolor_field_font_size", true ); 
		 
		 if($_customcolor_field_font_size=="yes"){?>
		 	<style>
			.customcolordiv{
				display:block !important;
			}
			</style>
		 	<?php }?> 
	<link href="https://fonts.googleapis.com/css?family=<?php echo $fontfamily;?>" rel="stylesheet">
			<style>
				.nameval{
					font-family: <?php echo $fontfamily;?>;
					color:<?php echo $fontcolor;?>;
					font-size:<?php echo $fontsizename;?>px;
				}
				.numval{
					font-family: <?php echo $fontfamily;?>;
					color:<?php echo $fontcolor;?>;
					font-size:<?php echo $fontsize;?>px;
				}
			</style>
	<?php $form_cont =   '
	
	<div class="image-editor">
							<div class="squadlist" style="display:none;">
									<label>Number:</label><input id="custom-number"  type="text" value="" >
									<label>Name:</label><input id="custom-name"  type="text" value="">
							</div>
					</div>
					<br/>
					
					<table class="variations" cellspacing="0">
				<tbody>
					<tr>
						<td class="label"><label for="color">Name</label></td>
						<td class="value">
							<input type="text" maxlength="7" class="name-on-tshirt" name="custom-name-on-tshirt" value="" />
						</td>
					</tr>
					<tr>
						<td class="label"><label for="color">Number</label></td>
						<td class="value">
							<input type="text" min="2"  class="number-on-tshirt" name="custom-number-on-tshirt" value="" />
						</td>
					</tr>
					<tr class="customcolordiv">
							<td class="label"><label for="color">Font Color</label></td>
						<td class="value">
							<input class="jscolor fontcolor-on-tshirt " name="custom-fontcolor-on-tshirt" value="" />
						</td>
					</tr>
					<tr class="image-input-tr">
						<td class="label"><label for="color">Custom Image</label></td>
						<td class="value">
							<input type="text" class="img-on-tshirt" name="custom-img-on-tshirt" value="" />
						</td>
					</tr>
				</tbody>
			</table>
			<div class="img-on-tshirt-show"></div>
			<div onclick="takeScreenShot()" class="add-image-btn">Add my custom image in cart.</div>
			<br/>
					<div class="" style="clear:both;"></div>';
		echo $form_cont;
	}
}
function save_name_tshirt($cart_item_data,$product_id){
	if( isset( $_REQUEST['custom-name-on-tshirt'] ) ) {
	$cart_item_data[ 'custom-name-on-tshirt' ] = $_REQUEST['custom-name-on-tshirt'];
	/* below statement make sure every add to cart action as unique line item */
	$cart_item_data['unique_key'] = md5( microtime().rand() );
	}
	return $cart_item_data;
	}
	function save_num_tshirt($cart_item_data,$product_id){
		if( isset( $_REQUEST['custom-number-on-tshirt'] ) ) {
	$cart_item_data[ 'custom-number-on-tshirt' ] = $_REQUEST['custom-number-on-tshirt'];
	/* below statement make sure every add to cart action as unique line item */
	$cart_item_data['unique_key'] = md5( microtime().rand() );
	}
	return $cart_item_data;
	}
	function save_img_tshirt($cart_item_data,$product_id){
		if( isset( $_REQUEST['custom-img-on-tshirt'] ) ) {
	$cart_item_data[ 'custom-img-on-tshirt' ] = $_REQUEST['custom-img-on-tshirt'];
	/* below statement make sure every add to cart action as unique line item */
	$cart_item_data['unique_key'] = md5( microtime().rand() );
	}
	return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_name_tshirt', 10, 2 );
add_action( 'woocommerce_add_cart_item_data', 'save_num_tshirt', 10, 2 );
add_action( 'woocommerce_add_cart_item_data', 'save_img_tshirt', 10, 2 );
function custom_name_render_meta_on_cart_and_checkout( $cart_data, $cart_item = null ) {
	$custom_items = array();
		/* Woo 2.4.2 updates */
		if( !empty( $cart_data ) ) {
		$custom_items = $cart_data;
		//var_dump($cart_data);
		}
		if( isset( $cart_item['custom-name-on-tshirt'] ) ) {
		$custom_items[] = array( "name" => 'Name', "value" => $cart_item['custom-name-on-tshirt']);
		}
		return $custom_items;
		}
		function custom_number_render_meta_on_cart_and_checkout( $cart_data, $cart_item = null ) {
		$custom_items = array();
		/* Woo 2.4.2 updates */
		if( !empty( $cart_data ) ) {
		$custom_items = $cart_data;
		}
		if( isset( $cart_item['custom-number-on-tshirt'] ) ) {
		$custom_items[] = array( "numbers" => 'Numbers', "value" => $cart_item['custom-number-on-tshirt'] );
		}
	return $custom_items;
}
function custom_img_render_meta_on_cart_and_checkout( $cart_data, $cart_item = null ) {
	$custom_items = array();
		/* Woo 2.4.2 updates */
		if( !empty( $cart_data ) ) {
			$custom_items = $cart_data;
		}
		if( isset( $cart_item['custom-name-on-tshirt'] ) ) {
			$custom_items[] = array( "name" => 'Name', "value" => $cart_item['custom-name-on-tshirt']);
		}
		if( isset( $cart_item['custom-number-on-tshirt'] ) ) {
			$custom_items[] = array( "name" => 'Numbers', "value" => $cart_item['custom-number-on-tshirt'] );
		}
		if( isset( $cart_item['custom-img-on-tshirt'] ) ) {
			$custom_items[] = array( "name" => 'Edited Image', "value" => $cart_item['custom-img-on-tshirt'] );
		}
			return $custom_items;
}
//add_filter( 'woocommerce_get_item_data', 'custom_name_render_meta_on_cart_and_checkout', 10, 2 );
//add_filter( 'woocommerce_get_item_data', 'custom_number_render_meta_on_cart_and_checkout', 10, 3 );
add_filter( 'woocommerce_get_item_data', 'custom_img_render_meta_on_cart_and_checkout', 10, 4 );

function custom_img_order_meta_handler( $item_id, $values, $cart_item_key ) {
    if( isset( $values['custom-name-on-tshirt'] ) ) {
        wc_add_order_item_meta( $item_id, "Name", $values['custom-name-on-tshirt'] );
    }
    if( isset( $values['custom-number-on-tshirt'] ) ) {
        wc_add_order_item_meta( $item_id, "Number", $values['custom-number-on-tshirt'] );
    }
    if( isset( $values['custom-img-on-tshirt'] ) ) {
        wc_add_order_item_meta( $item_id, "Edited Image", $values['custom-img-on-tshirt'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'custom_img_order_meta_handler', 1, 3 );


//function to save edited image data
function save_edited_data(){
		$imagedata = base64_decode($_POST['imgdata']);
		$filename = md5(uniqid(rand(), true));
		//path where you want to upload image
		$file = $_SERVER['DOCUMENT_ROOT'] . '/surya/demo/edited-images/'.$filename.'.png';
		$imageurl  = 'http://localhost/surya/demo/edited-images/'.$filename.'.png';
		file_put_contents($file,$imagedata);
		echo $imageurl;
		die;
		// $imagedata = base64_decode($_POST['imgdata']);
		//$filename = md5(uniqid(rand(), true));
		//var_dump($imagedata);
		//path where you want to upload image
		// $file =  plugins_url() . '/woo-feature-image-editor/images/'.$imagedata.'.png';
		// $imageurl  = 'http://example.com/uploads/'.$filename.'.png';
		// file_put_contents($file,$imagedata);
		//echo $file;
		// These files need to be included as dependencies when on the front end.
			// require_once( ABSPATH . 'wp-admin/includes/image.php' );
			// require_once( ABSPATH . 'wp-admin/includes/file.php' );
			// require_once( ABSPATH . 'wp-admin/includes/media.php' );
	// $a = http://localhost/surya/test/34b591af9b7973ebca693ab50a0fb71e.pngempty.png';
	// echo $a;
	// // Let WordPress handle the upload.
	// // Remember, 'my_image_upload' is the name of our file input in our form above.
	// $attachment_id = media_handle_upload($a, 55);
	
	// if ( is_wp_error( $attachment_id ) ) {
		// 	echo  'There was an error uploading the image.';
	// } else {
		// 	echo  'The image was uploaded successfully!';
	// }
		
}
//require_once( MDMR_PATH . 'index.php' );
//require_once( MDMR_PATH . 'controllers/checklist.php' );
//require_once( MDMR_PATH . 'controllers/column.php' );
//<img src="http://localhost/surya/demo/edited-images/835aaa5684d6b48b6fc928659e6059d6.png">
//
//


//Adding dashboard option for color and fontfamily 

add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );

// Save Fields
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );

function woo_add_custom_general_fields() {

  global $woocommerce, $post;
  
  echo '<div class="options_group">
  <p style="color:#0073aa;font-size:18px;">Add option only for editable products.</p>
  <p>Please test your values in frontend image editor.</p>';
  
  // Custom fields will be created here...
	  // // Text Field
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_text_field_font', 
				'label'       => __( 'Font family', 'woocommerce' ), 
				'placeholder' => 'Google font family',
				'desc_tip'    => 'true',
				'description' => __( 'Enter the custom google font.', 'woocommerce' ) 
			)
		);
		// // Text Field
		woocommerce_wp_text_input( 
			array( 
				'id'          => '_text_field_color', 
				'label'       => __( 'Font color', 'woocommerce' ), 
				'placeholder' => 'Font color eg  #000000 ',
				'desc_tip'    => 'true',
				'description' => __( 'Color ', 'woocommerce' ) 
			)
		);
	// Number Field
	 woocommerce_wp_text_input( 
			array( 
				'id'                => '_name_field_font_size', 
				'label'             => __( 'Name font size', 'woocommerce' ), 
				'placeholder'       => '', 
				'description'       => __( 'Enter the custom font size. eg 50px', 'woocommerce' ),
				'type'              => 'number', 
				'custom_attributes' => array(
						'step' 	=> 'any',
						'min'	=> '0'
					) 
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'                => '_number_field_font_size', 
				'label'             => __( 'Number font size', 'woocommerce' ), 
				'placeholder'       => '', 
				'description'       => __( 'Enter the custom font size. eg 50px', 'woocommerce' ),
				'type'              => 'number', 
				'custom_attributes' => array(
						'step' 	=> 'any',
						'min'	=> '0'
					) 
			)
		);
		
		woocommerce_wp_select( 
		array( 
			'id'      => '_customcolor_field_font_size', 
			'label'   => __( 'Custom color changing', 'woocommerce' ), 
			'options' => array(
				'no'   => __( 'No', 'woocommerce' ),
				'yes'   => __( 'Yes', 'woocommerce' ),
				
				)
			)
		);
  
  echo '</div>';
	
}

function woo_add_custom_general_fields_save( $post_id ){
	
	// Text Field
	$woocommerce_text_field = $_POST['_text_field_font'];
	if( !empty( $woocommerce_text_field ) )
		update_post_meta( $post_id, '_text_field_font', esc_attr( $woocommerce_text_field ) );
		
	// text Field
	$woocommerce_number_field = $_POST['_text_field_color'];
	if( !empty( $woocommerce_number_field ) )
		update_post_meta( $post_id, '_text_field_color', esc_attr( $woocommerce_number_field ) );

	// Number Field
	$woocommerce_number_field = $_POST['_name_field_font_size'];
	if( !empty( $woocommerce_number_field ) )
		update_post_meta( $post_id, '_name_field_font_size', esc_attr( $woocommerce_number_field ) );
	$woocommerce_number_field = $_POST['_number_field_font_size'];
	if( !empty( $woocommerce_number_field ) )
		update_post_meta( $post_id, '_number_field_font_size', esc_attr( $woocommerce_number_field ) );
	$woocommerce_number_field = $_POST['_customcolor_field_font_size'];
	if( !empty( $woocommerce_number_field ) )
		update_post_meta( $post_id, '_customcolor_field_font_size', esc_attr( $woocommerce_number_field ) );

}