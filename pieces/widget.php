<aside class="widget">

	<?php if ( isset( $config[ 'title' ] ) ) : ?>
		<?php echo $before_title . $config[ 'title' ] . $after_title; ?>
	<?php endif; ?>

    <div style="font-family: Verdana, Arial, sans-serif;">
		<img src='https://img4.wikia.nocookie.net/__cb20130124112826/logopedia/images/8/8e/IMDB.png'
			 style="max-height: 20px;"/>
		<a href="<?= $info->profileUrl ?>" target="_blank" title="View profile" style="display: block;">
			<img src="<?= $info->avatar ?>" style="border-radius: 3px;"/>

			<h3><?= $info->nick ?></h3>
		</a>

		<div style="font-size: 80%;">
			<?= $info->memberSince ?>
		</div>
		<div style="font-size: 80%; color: darkgrey">
			<?= $info->bio ?>
		</div>

		<?php
		for ( $i = 0; $i < count( $info->ratings ); $i++ ) {
			$rating = $info->ratings[ $i ];
			?>
            <a target="_blank" href="<?= $info->baseUrl . $rating->href ?>"><?= $rating->title ?></a>
			<?php
		}
		?>

		   <a href="<?= $info->ratingsUrlRss ?>" target="_blank" title="Ratings RSS">
			<img src='https://www.intrepidmuseum.org/App_Themes/Intrepid/images/rss_logo.gif'/>
		</a>
	</div>

</aside>
