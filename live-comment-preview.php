<?php
/*
Plugin Name: Live Comment Preview
Plugin URI: http://dev.wp-plugins.org/wiki/LiveCommentPreview
Description: Activate to supply users with a live comment preview.
Author: Jeff Minard
Version: 1.0
Author URI: http://thecodepro.com/
*/ 

// If you have changed the ID's on your form field elements
// You should make them match here

$commentFrom_commentID = 'comment';
$commentFrom_authorID  = 'author';
$commentFrom_urlID     = 'url';

// You shouldn't need to edit anything else.

add_action('comment_form', 'add_lp');
add_action('wp_head', 'add_lp_js');

function add_lp($post_id) {
	global $commentFrom_commentID, $commentFrom_authorID, $commentFrom_urlID;
	echo('<div id="commentPreivew"></div>');
	echo('<script type="text/javascript"><!-- 
	document.getElementById("' . $commentFrom_commentID . '").onkeyup = function() { preview(); }; 
	//--></script>');
	return $post_id;
}

function add_lp_js($ret) {
	global $commentFrom_commentID, $commentFrom_authorID, $commentFrom_urlID;

	echo("<!-- Script header added -->\n");
	
echo <<<ENDJS
<script type="text/javascript">
<!--

function preview() {
	var cmnt = document.getElementById('$commentFrom_commentID').value;
	var pnme = document.getElementById('$commentFrom_authorID').value;
	var purl = document.getElementById('$commentFrom_urlID').value;
	
	var NewText = cmnt.replace(/\\n\\n/g, '</p><p>');
	NewText = NewText.replace(/\\n/g, '<br />');
	
	if(purl) {
		name = '<a href="' + purl + '">' + pnme + '</a>';
	} else {
		name = pnme;
	}
	
	NewText = '<p><strong>Preview:</strong></p><p><em>' + name + ' says:</em></p><p>' + NewText + '</p>';
	document.getElementById('commentPreivew').innerHTML = NewText;
}
//-->
</script>
ENDJS;

/* bad code-highlighter fix <? */

	echo $javascript;

	return $ret;
}


?>