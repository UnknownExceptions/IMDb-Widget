<?php
/*
 * IMDb Widget for WordPress
 *
 *     Copyright (C) 2015 Henrique Dias <hacdias@gmail.com>
 *     Copyright (C) 2015 Luís Soares <lsoares@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<aside class="widget">

	<?php if ( isset( $config['title'] ) ) : ?>
		<?php echo $before_title . $config['title'] . $after_title; ?>
	<?php endif; ?>

	<div class="imdb-widget"
	     id='<?php echo $this->id; ?>'>

		<?php if ( ! isset( $config['hideBuiltInHeader'] ) || ! $config['hideBuiltInHeader'] == 'on' ) : ?>
			<header class="imdb-widget-header">
				<img class="imdb-widget-company-logo"
				     src="<?php echo plugins_url( 'css/imdb-logo.png', dirname( __FILE__ ) ); ?>"/>

				<div class="imdb-widget-header-text">
					<a class='imdb-widget-header-link' target='_blank'
					   href="<?php echo $info->url ?>" target="_blank" title="View profile">
						<?php echo $info->nick ?>
					</a>
					<span class="separator"> |</span>
					<span>IMDb</span>
				</div>
			</header>
		<?php endif; ?>

		<div class="imdb-widget-content">

			<div class="imdb-widget-profile-info">

				<?php if ( $this->isChecked( $config, 'picture' ) ) { ?>
					<div class="imdb-widget-pi-left" style="position:relative; display: inline-block">
					<a href='<?php echo $info->url ?>' target='_blank' title='Check profile' >
						<img src='<?php echo $this->serveImage( $info->avatar ); ?>' class='imdb-widget-avatar'/>
						<a class="imdb-widget-icon imdb-ratings-charts-message-link"
						   href="<?php echo $info->boardssendpmUrl ?>"
						   target="_blank" title="Send private message"></a>
					</a>
					</div>
				<?php } ?>

				<div class='imdb-widget-pi-right imdb-shadowed'>
					<div class="hrUserRealName"><strong><?php echo $info->nick ?></strong></div>
					<?php if ( $this->isChecked( $config, 'member since' ) ) { ?>
						<div class="imdb-member-since">
							<?php echo $info->memberSince ?>
						</div>
					<?php } ?>
					<!-- BIO -->
					<?php if ( $this->isChecked( $config, 'bio' ) ) { ?>
						<div class="imdb-bio">
							<?php echo $info->bio ?>
						</div>
					<?php } ?>
				</div>
			</div>

			<div class="imdb-info-box">
				<!-- BADGES -->
				<?php if ( $this->isChecked( $config, 'watchlist' ) ) {
					for ( $i = 0; $i < count( $info->badges ); $i ++ ) {
						$badge = $info->badges[ $i ]; ?>
						<div class="imdb-badge-info">
							<a href="http://www.imdb.com/badge/" target="_blank">
								<span title="<?php echo $badge->title ?> badge"><?php echo $badge->value ?></span>
								<span><?php echo $badge->title ?></span>
							</a>
						</div>
					<?php }
				} ?>

			</div>


			<!-- LATEST RATINGS -->
			<?php if ( count( $info->ratings ) && $this->isChecked( $config, 'ratings' ) ) { ?>
				<div class="imdb-block imdb-ratings">
					<div class="imdb-block-title"><h2 style="display: inline-block">Latest ratings</h2>
						<a href="javascript:void(0);" class="imdb-ratings-charts-link imdb-widget-icon" style="margin-top:1em"
						   title="Ratings charts"></a>
					</div>

					<?php for ( $i = 0; $i < count( $info->ratings ); $i ++ ) : ?>
						<?php $rating = $info->ratings[ $i ]; ?>
						<a target="_blank" href="<?php echo $info->baseUrl . $rating->link ?>"
						   title="<?php echo $rating->title ?> (<?php echo $rating->rating ?> ★)"
						   class="imdb-block-item-title">
							<img src="<?php echo $this->serveImage( $rating->logo ) ?>" class="imdb-block-item-logo"/>
						<span class="imdb-ratings-icon-star">
							<?php echo $rating->rating ?>
						</span>
						</a>
					<?php endfor; ?>

					<a href="<?php echo $info->ratingsUrl ?>" target="_blank"
					   title="See all user ratings" class="imdb-widget-small-link">
						<?php echo $info->ratingsCount; ?> »
					</a>
				</div>
			<?php } ?>

			<!-- WATCHLIST -->
			<?php if ( count( $info->watchlist ) && $this->isChecked( $config, 'watchlist' ) ) { ?>
				<div class="imdb-block imdb-watchlist">
					<div class="imdb-block-title"><h2>Watchlist</h2></div>
					<?php for ( $i = 0; $i < count( $info->watchlist ); $i ++ ) : ?>
						<?php $watch = $info->watchlist[ $i ]; ?>
						<a target="_blank" href="<?php echo $info->baseUrl . $watch->link ?>"
						   title="<?php echo $watch->title ?>" class="imdb-block-item-title">
							<img src="<?php echo $this->serveImage( $watch->logo ) ?>" class="imdb-block-item-logo"/>
						</a>
					<?php endfor; ?>

					<a href="<?php echo $info->watchlistUrl ?>" target="_blank"
					   title="See more" class="imdb-widget-small-link">
						See all »
					</a>
				</div>
			<?php } ?>

			<!-- LISTS -->
			<?php if ( count( $info->userLists ) && $this->isChecked( $config, 'lists' ) ) { ?>
				<div class="imdb-user-lists">
					<div class="imdb-block-title"><h2>Lists</h2></div>
					<?php for ( $i = 0; $i < count( $info->userLists ); $i ++ ) : ?>
						<?php $list = $info->userLists[ $i ]; ?>
						<a target="_blank" href="<?php echo $info->baseUrl . $list->link ?>"
						   title="List: <?php echo $list->title ?>" class="imdb-block-item-title">
							<img
								src="<?php echo $this->serveImage( ( substr( $list->logo, 0, 1 ) == '/' ? $info->baseUrl : '' ) . $list->logo ) ?>"
								class="imdb-block-item-small-logo"/>
							<?php echo $list->title ?>
						</a>

					<?php endfor; ?>

					<a href="<?php echo $info->listsUrl ?>" target="_blank"
					   title="See more" class="imdb-widget-small-link">
						See all »
					</a>
				</div>
			<?php } ?>

			<!-- REVIEWS -->
			<?php if ( count( $info->reviews ) && $this->isChecked( $config, 'reviews' ) ) { ?>
				<div class="imdb-user-reviews">
					<div class="imdb-block-title"><h2>Reviews</h2></div>
					<?php for ( $i = 0; $i < count( $info->reviews ); $i ++ ) : ?>
						<?php $review = $info->reviews[ $i ]; ?>
						<div class="imdb-block-item-title">
							<a title="<?php echo $review->movieTitle ?>"
							   href="<?php echo $info->baseUrl . $review->movieLink ?>"
							   target="_blank">
							<span
								title="<?php echo $review->movieTitle ?> <?php echo $review->movieYear ?>"><?php echo $review->movieTitle ?></span>
							</a><?php echo $review->movieYear ?>
						</div>
						<div class="imdb-user-review-title"><?php echo $review->title ?></div>
						<div class="imdb-user-review">
							<div class="imdb-user-reviews-left">
								<a title="<?php echo $review->movieTitle ?>"
								   href="<?php echo $info->baseUrl . $review->movieLink ?>"
								   class="imdb-block-item-title" target="_blank">
									<img src="<?php echo $this->serveImage( $review->movieLogo ) ?>" style=""/>
								</a>
							</div>
							<div class="imdb-user-reviews-right">
								<span><?php echo $review->text ?></span>
								<span><?php echo $review->meta ?></span>
							</div>
						</div>
					<?php endfor; ?>
					<a href="<?php echo $info->commentsindexUrl ?>" target="_blank"
					   title="See all reviews" class="imdb-widget-small-link">
						See all »
					</a>
				</div>
			<?php } ?>

			<!-- BOARDS -->
			<?php if ( count( $info->boards ) && $this->isChecked( $config, 'boards' ) ) { ?>
				<div class="imdb-user-board-messages">
					<div class="imdb-block-title"><h2>Board posts</h2></div>
					<?php for ( $i = 0; $i < count( $info->boards ); $i ++ ) : ?>
						<?php $board = $info->boards[ $i ];
						if ( ! empty( $board->commentTitle ) ) : ?>
							<div class="imdb-user-board-message">
								<a href='<?php echo $info->baseUrl . $board->boardLink ?>' target="_blank"
								   class="imdb-block-item-title">
									<?php echo $board->boardTitle ?>
								</a>
								<a href='<?php echo $info->baseUrl . $board->commentLink ?>' target="_blank"
								   title="<?php echo $board->when ?>" class="imdb-user-board-message-comment">
									<?php echo $board->commentTitle ?>
								</a>
							</div>
						<?php endif; ?>
					<?php endfor; ?>
					<a href="<?php echo $info->boardsUrl ?>/" target="_blank"
					   title="See all board messages" class="imdb-widget-small-link">
						See all messages »
					</a>
				</div>
			<?php } ?>


			<!-- OVERLAY RATINGS -->
			<div class="imdb-widget-charts">
				<span class="imdb-widget-charts-close" title="Close">x</span>

				<div class="imdb-block-title"><h2>Ratings charts for <?php echo $info->nick ?></h2></div>

				<div class="imdb-widget-chart">
					<h4>Ratings distribution</h4>

					<div class="imdb-histogram-horizontal">
						<?php echo $info->ratingsDistribution ?>
					</div>
				</div>

				<div class="imdb-widget-chart">
					<h4>By year</h4>

					<div class="imdb-histogram-horizontal">
						<?php echo $info->ratingsByYear ?>
					</div>
					<div class="imdb-histogram-by-year-legend">
						<?php echo $info->ratingsByYearLegend ?>
					</div>
				</div>

				<div class="imdb-widget-chart">
					<h4>Top-Rated Genres</h4>

					<div class="imdb-histogram-by-genre imdb-histogram-vertical">
						<?php echo $info->ratingsTopRatedGenres ?>
					</div>
				</div>

				<div class="imdb-widget-chart">
					<h4>Top-Rated Years</h4>

					<div class="imdb-histogram-vertical">
						<?php echo $info->ratingsTopRatedYears ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</aside>
