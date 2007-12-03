<?php
/*
Plugin Name: Live Comment Preview
Plugin URI: http://wordpress.org/extend/plugins/live-comment-preview/
Description: Supply users with a live comment preview. Use the function &lt;?php live_preview() ?&gt; to display the live preview in a different location. Based on version 1.7 by <a href="http://jm.cc/">Jeff Minard</a>.
Author: Brad Touesnard
Author URI: http://bradt.ca/
Version: 1.8.1

	Copyright 2007  Brad Touesnard  (http://bradt.ca/)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 

function lcp_output_js() {

	// Customize this string if you want to modify the preview output
	// %1 - author's name (as hyperlink if available)
	// %2 - comment text
	// %3 - gravatar image url
	$previewFormat = '
		<ol class="commentlist" style="clear: both; margin-top: 3em;">
			<li id="comment-preview" class="alt" style="overflow: hidden;">
				<img src="%3" alt="" class="gravatar" style="float: left; margin-right: 10px;"/>
				<cite>%1</cite> Says:
				<br />
				%2
			</li>
		</ol>';

	// If you have changed the ID's on your form field elements
	// You should make them match here
	$commentFrom_commentID = 'comment';
	$commentFrom_authorID  = 'author';
	$commentFrom_urlID     = 'url';
	$commentFrom_emailID     = 'email';

	// Default gravatar image
	$gravatar_default = get_option('siteurl') . '/wp-content/plugins/live-comment-preview/gravatar.png';

	// You shouldn't need to edit anything else.

	header('Content-type: text/javascript');
	?>

function wptexturize(text) {
	text = ' '+text+' ';
	var next 	= true;
	var output 	= '';
	var prev 	= 0;
	var length 	= text.length;
	while ( prev < length ) {
		var index = text.indexOf('<', prev);
		if ( index > -1 ) {
			if ( index == prev ) {
				index = text.indexOf('>', prev);
			}
			index++;
		} else {
			index = length;
		}
		var s = text.substring(prev, index);
		prev = index;
		if ( s.substr(0,1) != '<' && next == true ) {
			s = s.replace(/---/g, '&#8212;');
			s = s.replace(/--/g, '&#8211;');
			s = s.replace(/\.{3}/g, '&#8230;');
			s = s.replace(/``/g, '&#8220;');
			s = s.replace(/'s/g, '&#8217;s');
			s = s.replace(/'(\d\d(?:&#8217;|')?s)/g, '&#8217;$1');
			s = s.replace(/([\s"])'/g, '$1&#8216;');
			s = s.replace(/(\d+)"/g, '$1&Prime;');
			s = s.replace(/(\d+)'/g, '$1&prime;');
			s = s.replace(/([^\s])'([^'\s])/g, '$1&#8217;$2');
			s = s.replace(/(\s)"([^\s])/g, '$1&#8220;$2');
			s = s.replace(/"(\s)/g, '&#8221;$1');
			s = s.replace(/'(\s|.)/g, '&#8217;$1');
			s = s.replace(/\(tm\)/ig, '&#8482;');
			s = s.replace(/\(c\)/ig, '&#169;');
			s = s.replace(/\(r\)/ig, '&#174;');
			s = s.replace(/''/g, '&#8221;');
			s = s.replace(/(\d+)x(\d+)/g, '$1&#215;$2');
		} else if ( s.substr(0,5) == '<code' ) {
			next = false;
		} else {
			next = true;
		}
		output += s; 
	}
	return output.substr(1, output.length-2);	
}

function wpautop(p) {
	p = p + '\n\n';
	p = p.replace(/(<blockquote[^>]*>)/g, '\n$1');
	p = p.replace(/(<\/blockquote[^>]*>)/g, '$1\n');
	p = p.replace(/\r\n/g, '\n');
	p = p.replace(/\r/g, '\n');
	p = p.replace(/\n\n+/g, '\n\n');
	p = p.replace(/\n?(.+?)(?:\n\s*\n)/g, '<p>$1</p>');
	p = p.replace(/<p>\s*?<\/p>/g, '');
	p = p.replace(/<p>\s*(<\/?blockquote[^>]*>)\s*<\/p>/g, '$1');
	p = p.replace(/<p><blockquote([^>]*)>/ig, '<blockquote$1><p>');
	p = p.replace(/<\/blockquote><\/p>/ig, '<p></blockquote>');	
	p = p.replace(/<p>\s*<blockquote([^>]*)>/ig, '<blockquote$1>');
	p = p.replace(/<\/blockquote>\s*<\/p>/ig, '</blockquote>');	
	p = p.replace(/\s*\n\s*/g, '<br />');
	return p;
}

function updateLivePreview() {
	
	var cmntArea = document.getElementById('<?php echo $commentFrom_commentID ?>');
	var pnmeArea = document.getElementById('<?php echo $commentFrom_authorID ?>');
	var purlArea = document.getElementById('<?php echo $commentFrom_urlID ?>');
	var emlArea = document.getElementById('<?php echo $commentFrom_emailID ?>');
	
	if( cmntArea )
		var cmnt = wpautop(wptexturize(cmntArea.value));

	if( pnmeArea )
		var pnme = pnmeArea.value;
	
	if( purlArea )
		var purl = purlArea.value;
		
	if ( emlArea )
		var eml = emlArea.value;
		
	if(purl && pnme) {
		var name = '<a href="' + purl + '">' + pnme + '</a>';
	} else if(!purl && pnme) {
		var name = pnme;
	} else if(purl && !pnme) {
		var name = '<a href="' + purl + '">You</a> say';
	} else {
		var name = "You say";
	}
	
	var gravatar = '<?php echo $gravatar_default; ?>';
	if (eml != '') {
		gravatar = 'http://www.gravatar.com/avatar.php?gravatar_id=' + hex_md5(eml) + '&amp;default=<?php echo urlencode($gravatar_default); ?>';
	}
	
    <?php
    $previewFormat = str_replace("\r\n", "", $previewFormat);
    $previewFormat = str_replace("'", "\'", $previewFormat);
    $previewFormat = str_replace("%1", "' + name + '", $previewFormat);
    $previewFormat = str_replace("%2", "' + cmnt + '", $previewFormat);
    $previewFormat = str_replace("%3", "' + gravatar + '", $previewFormat);
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

	<?php
	// Add the MD5 functions using PHP so we only 
	// need to make 1 request to the web server for JS
	$plugin_path = dirname(__FILE__);
	$md5_file = $plugin_path . '/md5.js';
	@include($md5_file);
	
	// We're done outputting JS
	die();
}

function live_preview($before='', $after='') {
	global $livePreviewDivAdded;
	if($livePreviewDivAdded == false) {
		// We don't want this included in every page 
		// so we add it here instead of using the wphead filter
		echo '<script src="' . get_option('blogurl') . '/?live-comment-preview.js" type="text/javascript"></script>';
		echo $before.'<div id="commentPreview"></div>'.$after;
		$livePreviewDivAdded = true;
	}
}

function lcp_add_preview_div($post_id) {
	live_preview();
	return $post_id;
}

$livePreviewDivAdded == false;

if( stristr($_SERVER['REQUEST_URI'], 'live-comment-preview.js') ) {
	add_action('template_redirect', 'lcp_output_js');
}

add_action('comment_form', 'lcp_add_preview_div');
?>
