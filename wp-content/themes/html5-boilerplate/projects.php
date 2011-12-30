<?php
/*
Template Name: projects	
*/

get_header(); ?>
<div class="content">
	<div role="main" class="content">
	  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	  <article class="post" id="post-<?php the_ID(); ?>">
	    <header>
	      <h2>We love to work.</h2>
	      <h4>View a small selection of Capra&#8217;s latest projects.</h4>
	    </header>
	    <!--<p class="categories">Sort by tags <a href="#">Education</a><a href="#">Web apps</a><a href="#">capra owned</a></p>-->
	  
	    <?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
	
	    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
	  
	  </article>
	  <?php endwhile; endif; ?>
	

	<br class="clear" />
	</div>
	
</div>
<?php get_footer(); ?>
