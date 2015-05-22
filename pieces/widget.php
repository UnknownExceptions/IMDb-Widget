<aside class="widget">

	<?php if ( isset( $config['title'] ) ) : ?>
		<?php echo $before_title . $config['title'] . $after_title; ?>
	<?php endif; ?>

	<div class="imdb-widget">
		<a href="<?= $info->url ?>" target="_blank" title="View profile" class="imdb-avatar-link">
			<img src="<?= $info->avatar ?>" class="imdb-avatar"/>
			<img src='<?= plugins_url( 'css/imdb-logo.png', dirname( __FILE__ ) ); ?>' class="imdb-logo"/>
		</a>

		<div class="imdb-info-box">
                    <span class="imdb-nick">
                            <?= $info->nick ?>
                    </span>
			<a class="imdb-widget-icon imdb-ratings-charts-message-link" href="<?= $info->boardssendpmUrl ?>"
			   target="_blank" title="Send private message"></a>

			<div class="imdb-member-since">
				<?= $info->memberSince ?>
			</div>
			<div class="imdb-bio">
				<?= $info->bio ?>
			</div>
			<!-- BADGES -->
			<?php
			for ( $i = 0; $i < count( $info->badges ); $i ++ ) {
				$badge = $info->badges[ $i ]; ?>
				<div class="imdb-badge-info">
					<a href="http://www.imdb.com/badge/" title="More info »" target="_blank">
						<?= $badge->title ?>
					</a>
					<span title="<?= $badge->title ?> badge"><?= $badge->value ?></span>
				</div>
			<?php } ?>
		</div>

		<!-- LATEST RATINGS -->
		<div class="imdb-block imdb-ratings">
			<div class="imdb-block-title">Latest ratings</div>
			<a href="javascript:void(0);" class="imdb-ratings-charts-link imdb-widget-icon" title="Ratings charts">
			</a>
			<?php for ( $i = 0; $i < count( $info->ratings ); $i ++ ) : ?>
				<?php $rating = $info->ratings[ $i ]; ?>
				<a target="_blank" href="<?= $info->baseUrl . $rating->link ?>"
				   title="<?= $rating->title ?> (<?= $rating->rating ?>)" class="imdb-block-item-title">
					<img src="<?= $rating->logo ?>" class="imdb-block-item-logo"/>
                                    <span class="imdb-ratings-icon-star" title="Rating by <?= $info->nick ?>">
                                        <?= $rating->rating ?>
                                    </span>
				</a>
			<?php endfor; ?>

			<a href="<?= $info->ratingsUrl ?>" target="_blank"
			   title="See all user ratings" class="imdb-widget-small-link">
				<?= $info->ratingsCount; ?> »
			</a>
		</div>

		<!-- WATCHLIST -->
		<div class="imdb-block imdb-watchlist">
			<div class="imdb-block-title">Watchlist</div>
			<?php for ( $i = 0; $i < count( $info->watchlist ); $i ++ ) : ?>
				<?php $watch = $info->watchlist[ $i ]; ?>
				<a target="_blank" href="<?= $info->baseUrl . $watch->link ?>"
				   title="<?= $watch->title ?>" class="imdb-block-item-title">
					<img src="<?= $watch->logo ?>" class="imdb-block-item-logo"/>
				</a>
			<?php endfor; ?>

			<a href="<?= $info->watchlistUrl ?>" target="_blank"
			   title="See more" class="imdb-widget-small-link">
				See all »
			</a>
		</div>
		
		<!-- USER LISTS -->
		<div class="imdb-user-lists">
			<div class="imdb-block-title">Lists</div>
			<?php for ( $i = 0; $i < count( $info->userLists ); $i ++ ) : ?>
				<?php $list = $info->userLists[ $i ]; ?>
				<a target="_blank" href="<?= $info->baseUrl . $list->link ?>"
				   title="<?= $list->title ?>" class="imdb-block-item-title">
					<img src="<?= $list->logo ?>" class="imdb-block-item-small-logo"/>
					<?= $list->title ?>
				</a>
				
			<?php endfor; ?>

			<a href="<?= $info->listsUrl ?>" target="_blank"
			   title="See more" class="imdb-widget-small-link">
				See all »
			</a>
		</div>
		

		<!-- OVERLAY RATINGS -->
		<div class="imdb-widget-charts">
			<span class="imdb-widget-charts-close" title="Close">x</span>

			<div class="imdb-block-title">Ratings charts for <?= $info->nick ?></div>
			<div class="imdb-block-title-2">Ratings distribution</div>
			<div class="imdb-histogram-horizontal">
				<?= $info->ratingsDistribution ?>
			</div>

			<div class="imdb-block-title-2">By year</div>
			<div class="imdb-histogram-horizontal">
				<?= $info->ratingsByYear ?>
			</div>
			<div class="imdb-histogram-by-year-legend">
				<?= $info->ratingsByYearLegend ?>
			</div>

			<div class="imdb-block-title-2">Top-Rated Genres</div>
			<div class="imdb-histogram-by-genre imdb-histogram-vertical">
				<?= $info->ratingsTopRatedGenres ?>
			</div>
		</div>
	</div>

</aside>
