<?php

function seo_link($s) {
    $tr = array('ş','Ş','ı','İ','ğ','Ğ','ü','Ü','ö','Ö','ç','Ç');
    // Türkçe karakterlerin çevirlecegi karakterler
    $en = array('s','s','i','i','g','g','u','u','o','o','c','c');
    $s = str_replace($tr,$en,$s);
    $s = strtolower($s);
    $s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '-', $s);
    $s = preg_replace('/[^%a-z0-9 _-]/', '-', $s);
    $s = preg_replace('/\s+/', '-', $s);
    $s = preg_replace('|-+|', '-', $s);
    $s = str_replace("--","-",$s);
    $s = trim($s, '-');
    return $s;
}

function getir($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT,'kavur_bot');
    $x = curl_exec($ch);
    curl_close($ch);
    return $x;
}

function latin1_to_utf8($gelen){
    $x = array('ş', 'Ş', 'ğ', 'Ğ', 'İ', 'ı');
    $y = array('þ', 'Þ', 'ð', 'Ð', 'Ý', 'ý');
    return str_replace($y, $x, $gelen);
}

function resimindir($file, $local_path, $newfilename){
    $out = fopen($local_path."/".$newfilename, 'wb');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FILE, $out);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $file);
    curl_exec($ch);
    curl_close($ch);
}



function q($x){
	return mysql_real_Escape_String($x);
}

function ayir($x,$y,$z){
	$m	= explode($x,$z);
	$m	= explode($y,$m[1]);
	return $m[0];
}

function one_cikan($post_id,$image_url,$baslik){

	$upload_dir = wp_upload_dir();
	$image_data = file_get_contents($image_url);
	//$filename = basename($image_url);
	$filename	= seo_link($baslik).'.jpg';
	if(wp_mkdir_p($upload_dir['path']))
		$file = $upload_dir['path'] . '/' . $filename;
	else
		$file = $upload_dir['basedir'] . '/' . $filename;
	file_put_contents($file, $image_data);

	$wp_filetype = wp_check_filetype($filename, null );
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title' => sanitize_file_name($filename),
		'post_content' => '',
		'post_status' => 'inherit'
	);
	$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	wp_update_attachment_metadata( $attach_id, $attach_data );

	set_post_thumbnail( $post_id, $attach_id );

}

?>
