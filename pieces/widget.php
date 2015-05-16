<aside class="widget">

	<?php if ( isset( $config['title'] ) ) : ?>
		<?php echo $before_title . $config['title'] . $after_title; ?>
	<?php endif; ?>

	<img src='http://img4.wikia.nocookie.net/__cb20130124112826/logopedia/images/8/8e/IMDB.png'
	     style="max-height: 20px;"/>
	<a href="<?= $info->profileUrl ?>" target="_blank" title="View profile" style="display: block;">
		<img src="<?= $info->avatar ?>"/>

		<h3><?= $info->nick ?></h3>
	</a>

	<div style="font-size: 80%;">
		<?= $info->memberSince ?>
	</div>
	<a href="<?= $info->ratingsUrlRss ?>" target="_blank" title="Ratings RSS">
		<img src='http://www.intrepidmuseum.org/App_Themes/Intrepid/images/rss_logo.gif'/>
	</a>

</aside>
