<?php
/*
Plugin Name: Live Comment Preview
Plugin URI: http://dev.wp-plugins.org/wiki/LiveCommentPreview
Description: Activate to supply users with a live comment preview.
Author: Jeff Minard
Version: 1.0.1
Author URI: http://thecodepro.com/
*/ 

// If you have changed the ID's on your form field elements
// You should make them match here

$commentFrom_commentID = 'comment';
$commentFrom_authorID  = 'author';
$commentFrom_urlID     = 'url';

// You shouldn't need to edit anything else.

if( stristr($_SERVER['REQUEST_URI'], 'commentPreview.js') ) {
	header('Content-type: text/javascript');
	?>
function preview() {

	if(document.getElementById('<?php echo $commentFrom_commentID ?>')) {
		var cmnt = document.getElementById('<?php echo $commentFrom_commentID ?>').value;
	} else {
		return false;
	}

	if(document.getElementById('<?php echo $commentFrom_authorID ?>')) {
		var pnme = document.getElementById('<?php echo $commentFrom_authorID ?>').value;
	}
	
	if(document.getElementById('<?php echo $commentFrom_urlID ?>')) {
		var purl = document.getElementById('<?php echo $commentFrom_urlID ?>').value;
	}
	
	var NewText = cmnt.replace(/\\n\\n/g, '</p><p>');
	NewText = NewText.replace(/\\n/g, '<br />');
	
	if(purl) {
		var name = '<a href="' + purl + '">' + pnme + '</a> says:';
	} else if(pnme) {
		var name = pnme + " says";
	} else {
		var name = "You say";
	}
	
	NewText = '<p><strong>Preview:</strong></p><p><em>' + name + ':</em></p><p>' + NewText + '</p>';
	document.getElementById('commentPreview').innerHTML = NewText;
}
<?php die(); }

add_action('comment_form', 'add_lp');
add_action('wp_head', 'add_lp_js');

function add_lp($post_id) {
	global $commentFrom_commentID;
	echo('<div id="commentPreview"></div>');
	echo('<script type="text/javascript"><!-- 
	document.getElementById("' . $commentFrom_commentID . '").onkeyup = function() { preview(); }; 
	//--></script>');
	return $post_id;
}

function add_lp_js($ret) {
	echo('<script src="' . get_settings('home') . '/wp-content/plugins/live-comment-preview.php/commentPreview.js" type="text/javascript"></script>');
	return $ret;
}

?>