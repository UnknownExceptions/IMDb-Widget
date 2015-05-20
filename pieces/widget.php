<aside class="widget">

	<?php if ( isset( $config['title'] ) ) : ?>
		<?php echo $before_title . $config['title'] . $after_title; ?>
	<?php endif; ?>

	<div class="imdb-widget">            
            <a href="<?= $info->url ?>" target="_blank" title="View profile" class="imdb-avatar-link">
                    <img src="<?= $info->avatar ?>" class="imdb-avatar"/>
                    <img src='<?= IMDB_PLUGIN_URL . 'css/imdb-logo.png'; ?>' class="imdb-logo"/>
            </a>

            <div class="imdb-info-box">
                    <span class="imdb-nick">
                            <?= $info->nick ?>
                    </span>
                <a class="imdb-widget-icon imdb-ratings-charts-message-link" href="<?= $info->boardssendpmUrl ?>" target="_blank"></a>
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
                            <span><?= $badge->title ?></span>
                            <span><?= $badge->value ?></span>
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

            <!-- OVERLAY RATINGS -->
            <script>
                    jQuery(document).ready(function ($) {
                            $('.imdb-ratings-charts-link').click(function(ev) {
                                    var el = $(ev.currentTarget).parents('.imdb-widget').find('.imdb-widget-charts');
                                    el.toggle(! el.is(":visible"));
                                    return false;
                            });
                            $('.imdb-widget-charts-close').click(function(ev) {
                                    $(ev.currentTarget).parents('.imdb-widget-charts').hide();
                            });
                    });
            </script>
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
