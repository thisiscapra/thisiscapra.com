<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 */
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
  <meta charset="utf-8">

  <!-- www.phpied.com/conditional-comments-block-downloads/ -->
  <!--[if IE]><![endif]-->

  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <!-- Does not currently validate. Known issue with the Boilerplate. -->

  <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!--  Mobile Viewport Fix
        j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag
  device-width : Occupy full width of the screen in its current orientation
  initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
  maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
  -->
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">

  <!-- Place favicon.ico and apple-touch-icon.png in the root of your domain and delete these references -->
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">

  <!-- CSS : implied media="all" -->
  <?php versioned_stylesheet($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/css/style.css") ?>

  <!-- For the less-enabled mobile browsers like Opera Mini -->
  <?php versioned_stylesheet($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/css/handheld.css", 'media="handheld"') ?>

  <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
  <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/js/modernizr-1.5.min.js") ?>
  
  <!-- Web fonts 
	/*
	 * MyFonts Webfont Build ID 305241, 2011-01-11T17:16:10-0500
	 * 
	 * The fonts listed in this notice are subject to the End User License
	 * Agreement(s) entered into by the website owner. All other parties are 
	 * explicitly restricted from using the Licensed Webfonts(s).
	 * 
	 * You may obtain a valid license at the urls below.
	 * 
	 * Webfont: Minister Book
	 * Url: http://new.myfonts.com/fonts/urw/minister/t-book/
	 * Foundry: URW++
	 * Copyright: (URW)++,Copyright 2006 by (URW)++ Design & Development
	 * License: http://www.myfonts.com/viewlicense?1056
	 * Licensed pageviews: 10,000/month
	 * CSS font-family: Minister-Boo
	 * CSS font-weight: normal
	 * 
	 * (c) 2011 Bitstream, Inc
	*/
	
	
	-->

  <!-- Wordpress Head Items -->
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

  <?php wp_head(); ?>

</head>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->

<!--[if lt IE 7 ]> <body <?php body_class('ie6'); ?>> <![endif]-->
<!--[if IE 7 ]>    <body <?php body_class('ie7'); ?>> <![endif]-->
<!--[if IE 8 ]>    <body <?php body_class('ie8'); ?>> <![endif]-->
<!--[if IE 9 ]>    <body <?php body_class('ie9'); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body <?php body_class('ie6'); ?>> <!--<![endif]-->

  <div id="container">
    <header id="mainHead" role="banner">
    	<div class="content">
	      	<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
	    
		    <nav id="mainNav">
		    	<ul>
					<li>
						<a href="<?php bloginfo('url') ?>/expertise" title="expertise" 
						class="<?php if ( is_page("expertise")) {?>selected<?php } ?>">expertise</a>
					</li>
					<li>
						<a href="<?php bloginfo('url') ?>/projects" title="projects" 
						class="projects <?php if ( is_page("projects")) {?>selected<?php } ?>">projects</a>
						<!--dev child is 9-->
					</li>
					<li>
						<a href="<?php echo get_settings('home'); ?>/blog" 
						 class="blog <?php if ( is_page("blog")) {?>selected<?php } ?>" title="blog">blog</a>
					</li>
		
					<li>
						<a href="<?php bloginfo('url') ?>/contact" title="contact" 
						class="<?php if ( is_page("contact")) {?>selected<?php } ?>">contact</a>
					</li>
					<li>
					 <a href="#">menu</a>
					</li>
				</ul>
			</nav>
		</div>
	</header>


