=== Live Comment Preview ===
Tags: Comments
Contributors: chuyskywalker

Live Comment Preview is the simplest way to get live comment previews on your site. Simply activate the plugin -- That's it!

== Installation ==

Installing Live Comment Preview is rediculously simple:

1. Download live-comment-preview.php
2. Upload live-comment-preview.php into your wp-content/plugins directory.
3. Activate the plugin through the WordPress admin interface. 

Enjoy!

== Usage / Caveats ==

The plugin will work automatically on either of the default templates. However, if you're using a 3rd party or you've customized it, the commentPreview DIV may not be showing up in the source code. Should that be the case:

You can have the commentPreview div show up where ever you want if you use the code

<?php live_preview() ?>

And this is where the div will show up in both of the WP default templates:

<?php do_action('comment_form', $post->ID); ?>

Finally, if you've seriously changed the markup to your comment form (messing with ID's), then you may need to edit the plugin itself to match the ID's that you changed. They are stored in easy to change variables at the top of the plugin.

However, you most likely will not need to change any of this.

== Thanks! ==

Big time thanks to Iacovos Constantinou ( http://www.softius.net/ ) for his wicked cool JS functions for parsing the comment text. 

== Screenshots ==

1. What the comment preview looks like in Kubrick after being activated. Easy!
