<?php
/*
Template Name: start page	
*/

get_header(); ?>
<div  class="content">
	<div id="main" role="main" class="highlight-content">
	 	<h2 class="big"><span class="tighten">We</span> design for passionate people.</h2>
	 	<h4>You&#8217;re passionate about your vision and could talk about it for hours. Yet getting that 
	 	vision across both creatively and professionally can be challenging.</h4>
	
		<h4>That&#8217;s where we come in:</strong> we know how to use words and design to maximize those 
		few seconds you&#8217;ll have to let the world know what you&#8217;re all about. </h4>
		<a class="button" href="<?php bloginfo('url') ?>/expertise">What we do</a>
	</div>
</div>
<div class="goat">






</div>

<div class="content home-content">
	<h3>Featured projects</h3>
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<article class="post" id="post-<?php the_ID(); ?>">
		<?php the_content(''); ?>
	</article>
	<?php endwhile; endif; ?>
	<br class="clear" />
	<a class="button" href="<?php bloginfo('url') ?>/projects">See all</a>
</div>
<?php get_footer(); ?>
