<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

header("Link: <static/img/news-placeholder.svg>; rel=preload; as=image", false);

News::load($SETTINGS["sources"]["news"]);

$newsitems = News::getItems();
?>

<div class="btn-toolbar mb-4 justify-content-center" role="toolbar">
    <div class="btn-group btn-group-toggle flex-wrap justify-content-center" data-toggle="buttons">
        <?php
        foreach (NewsCategory::CATEGORIES as $cat) {
            $category = NewsCategory::fromString($cat);
            ?>
            <label class="btn btn-secondary category-btn<?php echo $category->get() == NewsCategory::GENERAL ? " active" : ""; ?>">
                <input
                    type="radio"
                    name="newscategory"
                    id="category-btn-<?php echo $category->toString(); ?>"
                    value="<?php echo $category->toString(); ?>"
                    autocomplete="off" />
                <i class="<?php echo $category->getIcon(); ?>"></i>
                <?php $Strings->get($category->toString()); ?>
            </label>
        <?php } ?>
    </div>
</div>

<div class="row" id="news-grid">

    <?php
    foreach ($newsitems as $item) {
        echo $item->generateGridCard(true);
    }
    ?>

    <div class="col-1 sizer-element"></div>

</div>


<script nonce="<?php echo $SECURE_NONCE; ?>">
    var preload_images = <?php
    $srcs = [];
    foreach ($newsitems as $item) {
        if (strpos($item->getImage(), "preview.redd.it") !== false) {
            $imgurl = $item->getImage();
        } else {
            $imgurl = Thumbnail::getThumbnailCacheURL($item->getImage(), 500);
        }
        $srcs[$item->getCategory()->toString()][] = $imgurl;
    }
    echo json_encode($srcs);
    ?>;
</script>