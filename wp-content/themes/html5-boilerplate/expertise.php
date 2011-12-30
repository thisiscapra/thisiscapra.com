<?php
/*
Template Name: expertise
*/

get_header(); ?>
<div class="content">
	<div id="main" role="main">
	  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	  <article class="post" id="post-<?php the_ID(); ?>">
	    <header>
	      <h2><?php the_title(); ?></h2>
	    </header>
	  
	    <?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
	
	    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
	  
	  </article>
	  <?php endwhile; endif; ?>

	</div>


	<br class="clear" />
	<hr />
	<h2>The team</h2>
	<ul class="team">
		<li>
			<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/team_kat.jpg" alt="Kat Neville" />
			<h4>Kat Neville</h4>
			<h6>Founder and Head Designer</h6>
			<p>Kat loves building beautiful, usable websites.  When she&#8217;s not in front of her computer (it's not often!), she&#8217;s 
			in the garden planting vegetables, obsessing over goats or coming up with ideas for new web apps.  Kat is the founder and star player in Capra's team.	</p>
		</li>
		<li>
			<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/team_ollie.jpg" alt="Ollie Kavanagh" />
			<h4>Ollie Kavanagh</h4>
			<h6>Designer</h6>
			<p>Ollie can&#8217;t decide if he&#8217;s a designer or a coder, but you can guarantee anything he&#8217;s done will be on a grid and baseline. 
			He also has an unhealthy obsession with trainers and condiments. Ollie works part-time for Capra.</p>
		</li>
		<li>
			<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/team_nat.png" alt="Nat Fletcher" />
			<h4>Nat Fletcher</h4>
			<h6>Writer and Researcher</h6>
			<p>Nat loves babysitting her ideas and force-feeding them chocolate chip cookies until they become full-fledged concepts. 
			She is scrupulous with words no matter what the style or message. She has been known to fondle dictionaries but lately
			 prefers cuddling with her puppy.  Natalie works part-time for Capra.</p>
		</li>
		<!--<li>
			<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/team_cindy.jpg" alt="Cindy Beischer" />
			<h4>Cindy Beischer</h4>
			<h6>Project Manager</h6>
			<p>Cindy's expert time management, organizational skills and attention to detail more make her the glue that keeps Capra 
			together. She&#8217;s studying adult education and learning methods while waiting to sign up for her next half marathon. </p>
		</li>-->
		<li>
			<img src="<?php bloginfo('url'); ?>/wp-content/themes/html5-boilerplate/images/team_kickcode.jpg" alt="KickCode" />
			<h4>KickCode</h4>
			<h6>Development</h6>
			<p>Capra works closely with KickCode, a small development agency that provides excellent, high quality coding services. 
			 Our partnership allows us to provide our clients with the best possible products. </p>
		</li>
	</ul>
	<br class="clear" />
</div>
<<?php get_footer(); ?>
