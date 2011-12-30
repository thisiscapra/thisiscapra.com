<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 */

get_header(); ?>
<div class="content">
	<div class="highlight-content">
		<h2>We'd like to share with you.</h2>
		<h4>We believe that educating and nurturing others is the best way to make everyone's life a little better.  So, this is the place you'll find information about things we've found insightful, practical or interesting. We also might talk about our company sometimes too.</h4>
	</div>
	<div id="main" role="main">
	
	  <?php if (have_posts()) : ?>
	    <?php while (have_posts()) : the_post(); ?>
	
	      <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
	        <header>
	          <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
	          <h6><time datetime="<?php the_time('Y-m-d')?>"><?php the_time('F jS, Y') ?></time>
	          <span class="author">by <?php the_author() ?></span></h6>
	        </header>
	       	<p><?php the_excerpt('<p>Read the rest of this page &raquo;</p>'); ?></p>
	        <footer>
	          <?php the_tags(' '); ?> 
	          <?php the_category(' ') ?>
	        </footer>
	      </article>
	
	    <?php endwhile; ?>
	
	    <nav>
	      <div><?php next_posts_link('&laquo; Older Entries') ?></div>
	      <div><?php previous_posts_link('Newer Entries &raquo;') ?></div>
	    </nav>
	
	  <?php else : ?>
	
	    <h2>Not Found</h2>
	    <p>Sorry, but you are looking for something that isn't here.</p>
	    <?php get_search_form(); ?>
	
	  <?php endif; ?>
	</div>
	
	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>


