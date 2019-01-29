<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

News::load($SETTINGS["sources"]["news"]);

$newsitems = News::getItems();
?>

<div class="btn-toolbar mb-4" role="toolbar">
    <div class="btn-group btn-group-toggle">
        <?php
        foreach (NewsCategory::CATEGORIES as $cat) {
            $category = NewsCategory::fromString($cat);
            ?>
            <label class="btn btn-secondary">
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

    <?php foreach ($newsitems as $item) { ?>
        <div class="col-12 col-sm-6 col-md-6 col-lg-4 px-1 m-0 grid__brick" data-groups='["<?php echo $item->getCategory()->toString(); ?>"]'>
            <div class="card mb-2">
                <?php if (!empty($item->getImage())) { ?>
                    <a href="<?php echo $item->getURL(); ?>" target="_BLANK">
                        <img src="<?php
                        if (strpos($item->getImage(), "preview.redd.it") !== false) {
                            echo $item->getImage();
                        } else {
                            echo Thumbnail::getThumbnailCacheURL($item->getImage(), 500);
                        }
                        ?>" class="card-img-top" alt="">
                    </a>
                <?php } ?>
                <div class="card-body">
                    <a class="text-dark" href="<?php echo $item->getURL(); ?>" target="_BLANK">
                        <h4 class="card-title">
                            <?php echo htmlentities($item->getHeadline()); ?>
                        </h4>
                    </a>
                    <p class="small"><?php echo $item->getSource(); ?></p>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="col-1 sizer-element"></div>

</div>
