<?php
/*
Template Name: projects-single
*/

get_header(); ?>

<nav class="projectNav">
	<ul>
		<li>
			<a class="<?php if ( is_page("totomerch")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/totomerch">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/toto_thumb_s.jpg" alt="totomerch" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("webcandy")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/webcandy">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/webcandy_thumb_s.jpg" alt="webcandy" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("watchy")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/watchy">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/watchy_thumb_s.jpg" alt="watchy" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("playnicely")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/playnicely">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/playnicely_thumb_s.jpg" alt="playnicely" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("kickcode")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/kickcode">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/kickcode_thumb_s.jpg" alt="kickcode" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("power-predictor")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/power-predictor">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/powerpredictor_thumb_s.jpg" alt="powerpredictor" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("quarkfilms")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/quarkfilms">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/quark_thumb_s.jpg" alt="quarkfilms" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("buddi")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/buddi">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/buddi_thumb_s.jpg" alt="buddi" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("braineos")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/braineos">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/braineos_thumb_s.jpg" alt="braineos" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("pioneers-running")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/pioneers-running">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/pioneers_thumb_s.jpg" alt="Pioneers Running" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("thepickuptruck")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/thepickuptruck">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/pickuptruck_thumb_s.jpg" alt="The pickuptruck" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("graffed")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/graffed">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/graffed_thumb_s.jpg" alt="graffed" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("rocketsports")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/rocketsports">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/rocket_thumb_s.jpg" alt="rocketsports" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("twiends")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/twiends">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/twiends_thumb_s.jpg" alt="twiends" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("paymenthub")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/paymenthub">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/paymenthub_thumb_s.jpg" alt="paymenthub" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("josyflo")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/josyflo">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/josy_thumb_s.jpg" alt="josyflo" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("gekko")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/gekko">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/gekko_thumb_s.jpg" alt="gekko" />
			</a>
		</li>
		<li>
			<a class="<?php if ( is_page("kingsroad")) {?>selected<?php } ?>" href="<?php bloginfo('url') ?>/projects/kingsroad">
				<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/kingsroad_thumb_s.jpg" alt="kingsroad" />
			</a>
		</li>
	</ul>
</nav>

<div id="projectSingle" role="main" class="content">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <article class="post" id="post-<?php the_ID(); ?>">
  
    <?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

    <div class="projectsBox">
      	<img id="x_portfolio" class="size-full" src="<?php echo get_post_meta($post->ID, "images", true); ?>" width="940" height="520" />
	    <a id="x_next" class="next" href="#">Next</a>
	    <a id="x_prev"  class="previous" href="#">Previous</a>
	    <img id="x_spinner" class="hide spinner" src="/wp-content/themes/html5-boilerplate/images/ajax-loader.gif" />
	</div>
    <div id="x_images" class="hide">
      <?php
        foreach ( get_post_meta($post->ID, "images", false) as $img ) {
          if ( $img == get_post_meta($post->ID, "images", true) ) {
            echo "<div class=\"x_current_image x_image\">" . $img . "</div>";
          } else {
            echo "<div class=\"x_image\">" . $img . "</div>";
          }
        }
      ?>
    </div>

    <ul class="columns">
	    <li>
	    	<h4>The challenge</h4>
			<?php echo get_post_meta($post->ID, "the_problem", true); ?>
		</li>
		<li>
	    	<h4>The solution</h4>
			<?php echo get_post_meta($post->ID, "the_solution", true); ?>
		</li>
		<li class="last">
	    	<h4>What we did</h4>
			<?php echo get_post_meta($post->ID, "what_we_did", true); ?>
		</li>
	</ul>
        
  
  </article>
  <?php endwhile; endif; ?>

	

</div>
<hr />
<?php get_footer(); ?>
