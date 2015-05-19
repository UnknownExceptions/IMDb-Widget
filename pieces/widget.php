<aside class="widget imdbWidget" style="font-family: Verdana; color: black;">

    <?php if (isset($config['title'])) : ?>
        <?php echo $before_title . $config['title'] . $after_title; ?>
    <?php endif; ?>

    <div>
        <a href="<?= $info->url ?>" target="_blank" title="View profile"
           style="display: inline-block; width: 45%; vertical-align: top; position: relative;border-radius: 3px;">
            <img src="<?= $info->avatar ?>" style="width: 100%"/>
            <img src='https://img4.wikia.nocookie.net/__cb20130124112826/logopedia/images/8/8e/IMDB.png'
                 style="max-height: 20px; position: absolute; left: 0; bottom: 0; opacity: 0.8;"/>
        </a>

        <div style="display: inline-block; width: 50%; vertical-align: top;">
			<span style="display: block;font-size: 115%;font-weight: bold;color: black; margin-bottom: 0px; line-height: 100%;">
				<?= $info->nick ?>
			</span>
            <div style="font-size: 9px; color: black; margin-top: 0">
                <?= $info->memberSince ?>
            </div>
            <div style="font-size: 70%; color: darkgrey">
                <?= $info->bio ?>
            </div>
			<!-- BADGES -->
			<?php
				for ($i = 0; $i < count($info->badges); $i++) {
					$badge = $info->badges[$i]; ?>
			<?= $badge->logo ?>
			<?php } ?>
        </div>
    </div>

	<!-- LATEST RATINGS -->
    <div style="margin-bottom: 8px;">
        <div style="font-weight: bold; margin-top: 8px; font-size: 80%;color:#A58500;">Latest ratings</div>
        <?php
        for ($i = 0; $i < count($info->ratings); $i++) {
			$rating = $info->ratings[$i]; ?>

        <a target="_blank" href="<?= $info->baseUrl . $rating->href ?>"
           title="<?= $rating->title ?> (<?= $rating->rating ?>)" style="display: inline-block;">
            <img src="<?= $rating->logo ?>" style="height: 60px; margin: 1px; border-radius: 2px;"/>
            <a/>
        <?php } ?>

            <a href="<?= $info->ratingsUrl ?>" target="_blank"
               title="See all user ratings" style="font-size: 70%;display: block;">
                <?= $info->ratingsCount; ?> »
            </a>
    </div>
	
	<!-- WATCHLIST -->
	<div style="margin-bottom: 8px;">
        <div style="font-weight: bold; margin-top: 8px; font-size: 80%;color:#A58500;">Watchlist</div>
        <?php
        for ($i = 0; $i < count($info->watchlist); $i++) {
			$watch = $info->watchlist[$i];
        ?>

        <a target="_blank" href="<?= $info->baseUrl . $watch->href ?>"
           title="<?= $watch->title ?>" style="display: inline-block;">
            <img src="<?= $watch->logo ?>" style="height: 60px; margin: 2px; border-radius: 2px;"/>
            <a/>
            <?php } ?>

            <a href="<?= $info->watchlistUrl ?>" target="_blank"
               title="See more" style="font-size: 70%;display: block;">
                See all »
            </a>
    </div>


</aside>
