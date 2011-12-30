=== Twitter for Wordpress ===
Tags: twitter
Requires at least: 2.1
Tested up to: 3.0
Stable tag: trunk
Donate link: http://rick.jinlabs.com/donate/

Twitter for WordPress displays yours latest tweets in your WordPress blog.

== Description ==

Twitter for WordPress displays yours latest tweets in your WordPress blog.

**Features**

    *  Simply
    *  Customizable
    *  Widget support
    *  No options page (yes, its a feature)
    *  Uses Wordpress resources (no extra files needed)
    *  Detects URLs, e-mail address and @username replies

**Usage**

If you use WordPress widgets, just drag the widget into your sidebar and configure. If widgets are not your thing, use the following code to display your public Twitter messages:

`<?php twitter_messages("username"); ?>`

For more info (options, customization, etc.) visit [the plugin homepage](http://rick.jinlabs.com/code/twitter "Twitter for Wordpress").

**Customization**

The plug in provides the following CSS classes:

    * ul.twitter: the main ul (if list is activated)
    * li.twitter-item: the ul items (if list is activated)
    * p.twitter-message: each one of the paragraphs (if msgs > 1)
    * .twitter-timestamp: the timestamp span class
    * a.twitter-link: the tweet link class
    * a.twitter-user: the @username reply link class

== Installation ==

Drop twitter-for-Wordpress folder (or even twitter.php) into /wp-content/plugins/ and activate the plug in the Wordpress admin area.

== Credits ==

[Ronald Heft](http://cavemonkey50.com/) - The plugin is highly based in his Pownce for Wordpress, so the major part of the credits goes to him.

[Michael Feichtinger](http://bohuco.net/blog) - For the multi-widget feature.

Michael Voigt - #trendingtopics regexp

Allen Shaw - link's regexp finetune

== Contact ==

Suggestion, fixes, rants, congratulations, gifts et al to rick[at]jinlabs.com