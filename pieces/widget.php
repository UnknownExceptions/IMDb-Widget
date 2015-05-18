<aside class="widget" style="font-family: Verdana; color: black;">

    <?php if (isset($config['title'])) : ?>
        <?php echo $before_title . $config['title'] . $after_title; ?>
    <?php endif; ?>

    <div>
        <a href="<?= $info->url ?>" target="_blank" title="View profile"
           style="display: inline-block; width: 50%; vertical-align: top; position: relative;border-radius: 3px;">
            <img src="<?= $info->avatar ?>" style="height: 80px;"/>
            <img src='https://img4.wikia.nocookie.net/__cb20130124112826/logopedia/images/8/8e/IMDB.png'
                 style="max-height: 20px; position: absolute; left: 0; bottom: 0; opacity: 0.8;"/>
        </a>

        <div style="display: inline-block; width: 50%; vertical-align: top;">
			<span style="display: block;font-size: 115%;font-weight: bold;color: black;">
				<?= $info->nick ?>
			</span>

            <div style="font-size: 70%; color: black;">
                <?= $info->memberSince ?>
            </div>
            <div style="font-size: 80%; color: darkgrey">
                <?= $info->bio ?>
            </div>
        </div>
    </div>

    <div>
        <div style="font-weight: bold; margin-top: 8px; font-size: 80%;">Latest ratings</div>
        <?php
        for ($i = 0;
        $i < count($info->ratings);
        $i++) {
        $rating = $info->ratings[$i];
        ?>

        <a target="_blank" href="<?= $info->baseUrl . $rating->href ?>"
           title="<?= $rating->title ?> (<?= $rating->rating ?>)" style="display: inline-block;">
            <img src="<?= $rating->logo ?>" style="height: 60px;; margin: 2px; border-radius: 2px;"/>
            <a/>
            <?php } ?>

            <a href="<?= $info->ratingsUrl ?>" target="_blank"
               title="See all user ratings" style="font-size: 70%;float: right;">
                <?= $info->ratingsCount; ?>
            </a>
    </div>


</aside>
