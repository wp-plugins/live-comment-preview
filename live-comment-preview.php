<?php
/*
Plugin Name: Live Comment Preview
Plugin URI: http://dev.wp-plugins.org/wiki/LiveCommentPreview
Description: Activate to supply users with a live comment preview. Use the function &lt;?php live_preview() ?&gt; to display the live preview in a different location.
Author: <a href="http://thecodepro.com/">Jeff Minard</a> &amp; <a href="http://www.softius.net/">Iacovos Constantinou</a>
Version: 1.6
*/ 

// Customize this string if you want to modify the preview output
// %1 - author's name (as hyperlink if available)
// %2 - comment text
$previewFormat         = "<p><strong>Preview:</strong></p><p><em>%1:</em></p><p>%2</p>";

// If you have changed the ID's on your form field elements
// You should make them match here
$commentFrom_commentID = 'comment';
$commentFrom_authorID  = 'author';
$commentFrom_urlID     = 'url';


// You shouldn't need to edit anything else.

$livePreviewDivAdded == false;

if( stristr($_SERVER['REQUEST_URI'], 'commentPreview.js') ) {
	header('Content-type: text/javascript');
	?>

function wptexturize(text) {
	text 		= ' '+text+' ';
	var textarr = text.split(/(<[^>]+?>)/g)
	var istop	= textarr.length;
	var next 	= true;
	var output 	= '';
	for ( var i=0; i<istop; i++ ) {
		var curl = textarr[i];			
		if ( curl.substr(0,1) != '<' && next == true ) {
			curl = curl.replace(/---/g, '&#8212;');
			curl = curl.replace(/--/g, '&#8211;');			
			curl = curl.replace(/\.{3}/g, '&#8230;');			
			curl = curl.replace(/``/g, '&#8220;');						
			
			curl = curl.replace(/'s/g, '&#8217;s');
			curl = curl.replace(/'(\d\d(?:&#8217;|')?s)/g, '&#8217;$1');
			curl = curl.replace(/([\s"])'/g, '$1&#8216;');			
			curl = curl.replace(/(\d+)"/g, '$1&Prime;');						
			curl = curl.replace(/(\d+)'/g, '$1&prime;');									
			curl = curl.replace(/([^\s])'([^'\s])/g, '$1&#8217;$2');	
			curl = curl.replace(/(\s)"([^\s])/g, '$1&#8220;$2');				
			curl = curl.replace(/"(\s)/g, '&#8221;$1');						
			curl = curl.replace(/'(\s|.)/g, '&#8217;$1');	
			curl = curl.replace(/\(tm\)/ig, '&#8482;');	
			curl = curl.replace(/\(c\)/ig, '&#169;');
			curl = curl.replace(/\(r\)/ig, '&#174;');
			curl = curl.replace(/''/g, '&#8221;');	
			
			curl = curl.replace(/(\d+)x(\d+)/g, '$1&#215;$2');	
		} else if ( curl.substr(0,5) == '<code' ) {
			next = false;
		} else {
			next = true;
		}
		output += curl; 
	}
	return output.substr(1, output.length-2);
}

function wpautop(pee) {
	pee = pee + '\n\n';
	
	pee = pee.replace(/(<blockquote[^>]*>)/g, '\n$1');
	pee = pee.replace(/(<\/blockquote[^>]*>)/g, '$1\n');
		
	pee = pee.replace(/\r\n/g, '\n');
	pee = pee.replace(/\r/g, '\n');
	pee = pee.replace(/\n\n+/g, '\n\n');
	pee = pee.replace(/\n?(.+?)(?:\n\s*\n)/g, '<p>$1</p>');
	pee = pee.replace(/<p>\s*?<\/p>/g, '');

	pee = pee.replace(/<p>\s*(<\/?blockquote[^>]*>)\s*<\/p>/g, '$1');
	pee = pee.replace(/<p><blockquote([^>]*)>/ig, '<blockquote$1><p>');
	pee = pee.replace(/<\/blockquote><\/p>/ig, '<p></blockquote>');	
	pee = pee.replace(/<p>\s*<blockquote([^>]*)>/ig, '<blockquote$1>');
	pee = pee.replace(/<\/blockquote>\s*<\/p>/ig, '</blockquote>');			
	
	pee = pee.replace(/\s*\n\s*/g, '<br />');
	return pee;
}

function updateLivePreview() {
	
	var cmntArea = document.getElementById('<?php echo $commentFrom_commentID ?>');
	var pnmeArea = document.getElementById('<?php echo $commentFrom_authorID ?>');
	var purlArea = document.getElementById('<?php echo $commentFrom_urlID ?>');
	
	if( cmntArea )
		var cmnt = wpautop(wptexturize(cmntArea.value));

	if( pnmeArea )
		var pnme = pnmeArea.value;
	
	if( purlArea )
		var purl = purlArea.value;
	
		
	if(purl && pnme) {
		var name = '<a href="' + purl + '">' + pnme + '</a> says';
	} else if(!purl && pnme) {
		var name = pnme + ' says';
	} else if(purl && !pnme) {
		var name = '<a href="' + purl + '">You</a> say';
	} else {
		var name = "You say";
	}
	
    <?php
    $previewFormat = str_replace("'", "\'", $previewFormat);    
    $previewFormat = str_replace("%1", "' + name + '", $previewFormat);
    $previewFormat = str_replace("%2", "' + cmnt + '", $previewFormat);
    $previewFormat = "'" . $previewFormat . "';\n";
    ?>
    document.getElementById('commentPreview').innerHTML = <?php echo $previewFormat; ?>
}

function initLivePreview() {
	if(!document.getElementById)
		return false;

	var cmntArea = document.getElementById('<?php echo $commentFrom_commentID ?>');
	var pnmeArea = document.getElementById('<?php echo $commentFrom_authorID ?>');
	var purlArea = document.getElementById('<?php echo $commentFrom_urlID ?>');
	
	if ( cmntArea )
		cmntArea.onkeyup = updateLivePreview;
	
	if ( pnmeArea )
		pnmeArea.onkeyup = updateLivePreview;
	
	if ( purlArea )
		purlArea.onkeyup = updateLivePreview;	
}

//========================================================
// Event Listener by Scott Andrew - http://scottandrew.com
// edited by Mark Wubben, <useCapture> is now set to false
//========================================================
function addEvent(obj, evType, fn){
	if(obj.addEventListener){
		obj.addEventListener(evType, fn, false); 
		return true;
	} else if (obj.attachEvent){
		var r = obj.attachEvent('on'+evType, fn);
		return r;
	} else {
		return false;
	}
}

addEvent(window, "load", initLivePreview);

<?php die(); }


function live_preview($before='', $after='') {
	global $livePreviewDivAdded;
	if($livePreviewDivAdded == false) {
		echo $before.'<div id="commentPreview"></div>'.$after;
		$livePreviewDivAdded = true;
	}
}

function lcp_add_preview_div($post_id) {
	global $commentFrom_commentID, $livePreviewDivAdded;
	if($livePreviewDivAdded == false) {
		echo '<div id="commentPreview"></div>';
		$livePreviewDivAdded = true;
	}
	return $post_id;
}
function lcp_add_js($ret) {
	echo('<script src="' . get_settings('siteurl') . '/wp-content/plugins/live-comment-preview.php/commentPreview.js" type="text/javascript"></script>');
	return $ret;
}

add_action('comment_form', 'lcp_add_preview_div');
add_action('wp_head', 'lcp_add_js');

?>
