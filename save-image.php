<?php 
$imagedata = base64_decode($_POST['imgdata']);
$filename = md5(uniqid(rand(), true));
var_dump($imagedata);
echo "ajaxxx runing";
//path where you want to upload image
 $file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/'.$filename.'.png';
// $imageurl  = 'http://example.com/uploads/'.$filename.'.png';
// file_put_contents($file,$imagedata);
//echo $imageurl;
// These files need to be included as dependencies when on the front end.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	// Let WordPress handle the upload.
	// Remember, 'my_image_upload' is the name of our file input in our form above.
	$attachment_id = media_handle_upload($file, $_POST['post_id'] );
	
	if ( is_wp_error( $attachment_id ) ) {
		echo  'There was an error uploading the image.';
	} else {
		echo  'The image was uploaded successfully!';
	}
