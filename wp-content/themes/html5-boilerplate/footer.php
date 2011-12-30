<?php
/**
 * @package WordPress
 * @subpackage HTML5_Boilerplate
 */
?>
  <footer id="main-footer">
  	<div class="content">
  	
  	
  		<ul>
	  		<li>
	  			<h2>latest post</h2>
	  			<!--exclude portfolio category-->
				<?php query_posts('cat=-14'); ?>
				
				<?php
				    $myLatestPost = new WP_Query();
				    $myLatestPost->query('showposts=1');
				?>
				
				
				<?php while ($myLatestPost->have_posts()) : $myLatestPost->the_post(); ?>
				    	<h4><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h4>
				    	<p class="small"><?php the_time('l, F jS, Y') ?></p>

				<?php endwhile; ?>
				

	  		</li>
	  		<li>
		      	<h2>see us socially</h2>
		      	<a class="linked-in-link" href="http://www.linkedin.com/companies/capra-limited">linkedIn</a>
		      	<a class="facebook-link" href="http://en-gb.facebook.com/pages/Capra-Limited/141585689219947">Facebook</a>
		      	<a class="twit-link" href="http://twitter.com/thisiscapra">twitter</a>
		      	<br class="clear" />
		      	<?php twitter_messages('thisiscapra', 1, true, true, 'by @kassy4', false, false, false); ?>
	      	</li>
	      	<li>
	      		<h2>goat fact</h2>
	      		<p>The pupil in a goat's eye is rectangular in shape instead of being round like those of other animals.   </p>
	      		<!--<a class="small" href="#">Why we love goats</a>-->
	      	</li>
	      	<li class="last">
	      		<h2>we're loved</h2>
	      		<p>I love working with Capra.  They do great work!</p>
				<a class="small" href="http://kickcode.com">Elliott Draper, KickCode</a>
      		</li>
      	</ul>
      <p class="registration">
      	

        Capra Limited is registered in the UK    Registration Number:  7336818
      </p>
  </footer>
</div> <!--! end of #container -->

  <!-- Javascript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script>!window.jQuery && document.write('<script src="<?php echo $GLOBALS["TEMPLATE_RELATIVE_URL"] ?>html5-boilerplate/js/jquery-1.4.2.min.js"><\/script>')</script>


  <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/js/plugins.js") ?>
  <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/js/script.js") ?>


  <!--[if lt IE 7 ]>
    <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/js/dd_belatedpng.js") ?>
  <![endif]-->


  <!-- yui profiler and profileviewer - remove for production -->
  <!-- <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/js/profiling/yahoo-profiling.min.js") ?>
    <?php versioned_javascript($GLOBALS["TEMPLATE_RELATIVE_URL"]."html5-boilerplate/js/profiling/config.js") ?> -->
  <!-- end profiling code -->


  <meta name="google-site-verification" content="4R-d4fXlUTYNLND5oqJa1meo17aeJ4dAM5XWGfxDcFc" />
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-15656719-1']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		
		</script>


  <?php wp_footer(); ?>
	</div>
</body>
</html>
