<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 */
?>
<aside id="sidebar">
  <?php   /* Widgetized sidebar, if you have the plugin installed. */
      if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

  <!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.
  <section>
    <h2>Author</h2>
    <p>A little something about you, the author. Nothing lengthy, just an overview.</p>
  </section>
  -->

  <?php if ( is_404() || is_category() || is_day() || is_month() || is_year() || is_search() || is_paged() ) { ?> 
  <section>
    
    <?php /* If this is a 404 page */ if (is_404()) { ?>
    <?php /* If this is a category archive */ } elseif (is_category()) { ?>
    <p>You are currently browsing the archives for the <?php single_cat_title(''); ?> category.</p>

    <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
    <p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives
    for the day <?php the_time('l, F jS, Y'); ?>.</p>

    <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
    <p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives
    for <?php the_time('F, Y'); ?>.</p>

    <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
    <p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives
    for the year <?php the_time('Y'); ?>.</p>

    <?php /* If this is a search result */ } elseif (is_search()) { ?>
    <p>You have searched the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives
    for <strong>'<?php the_search_query(); ?>'</strong>. If you are unable to find anything in these search results, you can try one of these links.</p>

    <?php /* If this set is paginated */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
    <p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives.</p>

    <?php } ?>

  </section>
  <?php }?>
  
  <nav role="navigation">
	
    <?php wp_list_categories('title_li=<h2>Topics</h2>'); ?>
    <a class="rss" href="http://thisiscapra.com/feed/">Subscribe to our RSS feed</a>
  </nav>

  <nav>
    <ul>
      <?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
              <?php } ?>

      <?php endif; ?>
    </ul>
  </nav>
</aside>

