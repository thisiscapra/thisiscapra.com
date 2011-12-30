<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 */

get_header(); ?>
<div class="content">
<div id="main" role="main">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
    <header>
      <h2><?php the_title(); ?></a></h2>
     
      <h4><time datetime="<?php the_time('Y-m-d')?>"><?php the_time('l, F jS, Y') ?></time> by <?php the_author() ?></h4>
       <span class="categories"><?php the_category(' ') ?>
      <?php the_tags( ' '); ?></span>
     
    </header>
    <?php the_content('Read the rest of this entry &raquo;'); ?>
    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
    
    

      <?php if ( comments_open() && pings_open() ) {
        // Both Comments and Pings are open ?>
        

      <?php } elseif ( !comments_open() && pings_open() ) {
        // Only Pings are Open ?>
        Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

      <?php } elseif ( comments_open() && !pings_open() ) {
        // Comments are open, Pings are not ?>
        You can skip to the end and leave a response. Pinging is currently not allowed.

      <?php } elseif ( !comments_open() && !pings_open() ) {
        // Neither Comments, nor Pings are open ?>
        Both comments and pings are currently closed.

      <?php } edit_post_link('Edit this entry','','.'); ?>
      </p>
    </footer>

    <?php comments_template(); ?>

  </article>

<?php endwhile; else: ?>

  <p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

</div>
	<?php get_sidebar(); ?>

</div>
<?php get_footer(); ?>
