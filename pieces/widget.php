<aside class="widget">

    <?php if ( isset( $config['title'] ) ) : ?>
	<?php echo $before_title . $config['title'] . $after_title; ?>
    <?php endif; ?>

    <div class="imdb-widget">
		<div class="imdb-logo"></div>
		
        <div class="imdb-nick">
            <?= $info->nick ?>
        </div>
		<div class="imdb-member-since">
			<?= $info->memberSince ?>
		</div>

		<div class="imdb-avatar-wrapper">
			<a href="<?= $info->url ?>" target="_blank" title="View profile" class="imdb-avatar-link">
				<img src="<?= $info->avatar ?>" class="imdb-avatar" />
				<a class="imdb-widget-icon imdb-ratings-charts-message-link" href="<?= $info->boardssendpmUrl ?>"
				   target="_blank" title="Send private message"></a>
			</a>
		</div>

        <div class="imdb-info-box">
			<!-- BADGES -->
            <?php
            for ( $i = 0; $i < count( $info->badges ); $i ++ ) {
                    $badge = $info->badges[ $i ]; ?>
                    <div class="imdb-badge-info">
                            <a href="http://www.imdb.com/badge/" target="_blank">
                                    <span title="<?= $badge->title ?> badge"><?= $badge->value ?></span>
                                    <span><?= $badge->title ?></span>
                            </a>
                    </div>
            <?php } ?>
			<div class="imdb-bio">
				<?= $info->bio ?>
			</div>
        </div>
		
		
        <!-- LATEST RATINGS -->
		<?php if (count( $info->ratings )) { ?>
        <div class="imdb-block imdb-ratings">
			<div class="imdb-block-title">Latest ratings
				<a href="javascript:void(0);" class="imdb-ratings-charts-link imdb-widget-icon"
				   title="Ratings charts"></a>
			</div>
			
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
		<?php } ?>

        <!-- WATCHLIST -->
		<?php if (count( $info->watchlist )) { ?>
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
		<?php } ?>

        <!-- LISTS -->
		<?php if (count( $info->userLists )) { ?>
        <div class="imdb-user-lists">
			<div class="imdb-block-title">Lists</div>
			<?php for ( $i = 0; $i < count( $info->userLists ); $i ++ ) : ?>
					<?php $list = $info->userLists[ $i ]; ?>
					<a target="_blank" href="<?= $info->baseUrl . $list->link ?>"
					   title="List: <?= $list->title ?>" class="imdb-block-item-title">
							<img src="<?= (substr($list->logo,0,1) == '/' ? $info->baseUrl : '') . $list->logo ?>" 
								 class="imdb-block-item-small-logo" />
							<?= $list->title ?>
					</a>

			<?php endfor; ?>

			<a href="<?= $info->listsUrl ?>" target="_blank"
			   title="See more" class="imdb-widget-small-link">
					See all »
			</a>
        </div>
		<?php } ?>

        <!-- REVIEWS -->
        <?php if (count( $info->reviews )) { ?>
        <div class="imdb-user-reviews">
                <div class="imdb-block-title">Reviews</div>
                <?php for ( $i = 0; $i < count( $info->reviews ); $i ++ ) : ?>
                    <?php $review = $info->reviews[ $i ]; ?>
                     <div class="imdb-block-item-title">
						<a title="<?= $review->movieTitle ?>" href="<?= $info->baseUrl . $review->movieLink ?>" target="_blank">
							 <span title="<?= $review->movieTitle ?> <?= $review->movieYear ?>"><?= $review->movieTitle ?></span>
						</a><?= $review->movieYear ?>
                    </div>
                    <div class="imdb-user-review-title"><?= $review->title ?></div>       
                    <div class="imdb-user-review">                                                  
                        <div class="imdb-user-reviews-left">
                           <a title="<?= $review->movieTitle ?>" href="<?= $info->baseUrl . $review->movieLink ?>"
                              class="imdb-block-item-title" target="_blank">
                                <img src="<?= $review->movieLogo ?>" style="" />
                           </a>
                        </div>
                        <div class="imdb-user-reviews-right">
                            <span><?= $review->text ?></span>                                
                            <span><?= $review->meta ?></span>
                        </div>
                    </div>
                <?php endfor; ?>
                <a href="<?= $info->commentsindexUrl ?>" target="_blank"
                   title="See all reviews" class="imdb-widget-small-link">
                        See all »
                </a>
        </div>
        <?php } ?>

        <!-- OVERLAY RATINGS -->
        <div class="imdb-widget-charts">
			<span class="imdb-widget-charts-close" title="Close">x</span>

			<div class="imdb-block-title">Ratings charts for <?= $info->nick ?></div>
			
			<div class="imdb-widget-chart">
				<div class="imdb-histogram-horizontal">
					<?= $info->ratingsDistribution ?>
				</div>
				<h4>Ratings distribution</h4>
			</div>
			
			<div class="imdb-widget-chart">
				<div class="imdb-histogram-horizontal">
					<?= $info->ratingsByYear ?>
				</div>
				<div class="imdb-histogram-by-year-legend">
					<?= $info->ratingsByYearLegend ?>
				</div>
				<h4>By year</h4>
			</div>
			
			<div class="imdb-widget-chart">
				<div class="imdb-histogram-by-genre imdb-histogram-vertical">
					<?= $info->ratingsTopRatedGenres ?>
				</div>
				<h4>Top-Rated Genres</h4>
			</div>
		</div>
    </div>

</aside>
