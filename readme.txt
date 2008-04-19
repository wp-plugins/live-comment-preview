=== Live Comment Preview ===
Contributors: bradt
Tags: comment, comments, preview
Requires at least: 1.5
Tested up to: 2.5
Stable tag: 1.9

Live Comment Preview is the simplest way to get live comment previews on your site. Simply activate the plugin -- That's it!

== Description ==

Live Comment Preview is the simplest way to get live comment previews on your site. Simply activate the plugin -- That's it!

This plugin uses only client-side Javascript to format a preview, it does not make any Ajax requests to the server. This provides a smooth live preview as you type.

== Installation ==

Installing Live Comment Preview is ridiculously simple:

1. Download live-comment-preview.zip
2. Unzip the archive
2. Upload the live-comment-preview folder to your wp-content/plugins directory
3. Activate the plugin through the WordPress admin interface

Enjoy!

== Screenshots ==

1. What the comment preview looks like in Kubrick after being activated. Easy!

== Usage / Caveats ==

The plugin will work automatically on either of the default templates. However, if you're using a 3rd party or you've customized it, the commentPreview DIV may not be showing up in the source code. Should that be the case:

You can have the commentPreview div show up where ever you want if you use the code

&lt;?php live_preview() ?&gt;

And this is where the div will show up in both of the WP default templates:

&lt;?php do_action('comment_form', $post->ID); ?&gt;

Finally, if you've seriously changed the markup to your comment form (messing with ID's), then you may need to edit the plugin itself to match the ID's that you changed. They are stored in easy to change variables at the top of the plugin.

However, you most likely will not need to change any of this.

== To Do ==

* Add an WP Options page to manage plugin settings and make updates easier
* Add support for smiley icons

== Release Notes ==

* 1.9 - 2008-04-19<br />
  Added support for Wordpress 2.5's gravatar settings.<br />
  [Several Bug Fixes](http://dev.wp-plugins.org/log/live-comment-preview?action=stop_on_copy&rev=41675&stop_rev=28426&mode=stop_on_copy&verbose=on)
* 1.8.2 - 2007-12-03<br />
  Bug fix: Only works if blog url is the web site root.
* 1.8.1 - 2007-12-02<br />
  Bug fix: Javascript doesn't load for users who have WP in a subdirectory.
* 1.8 - 2007-11-29<br />
  First release by Brad Touesnard<br />
  Added [Gravatar](http://www.gravatar.com/) support
* 1.7 - 2005-06-05<br />
  Last release by Jeff Minard


== Thanks! ==

Thanks to [Jeff Minard](http://jrm.cc/) for developing this plugin originally and also to [Iacovos Constantinou](http://www.softius.net/) for his JS functions for parsing the comment text.
