<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

News::load($SETTINGS["sources"]["news"]);

$newsitems = News::getItems();
?>

<div class="btn-toolbar mb-4" role="toolbar">
    <div class="btn-group btn-group-toggle">
        <?php foreach (NewsCategory::CATEGORIES as $cat) { ?>
            <label class="btn btn-secondary">
                <input type="radio" name="newscategory" id="category-btn-<?php echo strtolower($cat); ?>" value="<?php echo strtolower($cat); ?>" autocomplete="off"> <?php $Strings->get($cat); ?>
            </label>
        <?php } ?>
    </div>
</div>

<div class="row" id="news-grid">

    <?php foreach ($newsitems as $item) { ?>
        <div class="col-12 col-sm-6 col-md-6 col-lg-4 px-1 m-0 grid__brick" data-groups='["<?php echo $item->getCategory()->toString(); ?>"]'>
            <div class="card mb-2">
                <?php if (!empty($item->getImage())) { ?>
                    <img src="<?php echo $item->getImage(); ?>" class="card-img-top" alt="">
                <?php } ?>
                <a class="card-body text-dark" href="<?php echo $item->getURL(); ?>" target="_BLANK">
                    <h4 class="card-title">
                        <?php echo htmlentities($item->getHeadline()); ?>
                    </h4>
                    <p class="small"><?php echo htmlentities($item->getSource()); ?></p>
                </a>
            </div>
        </div>
    <?php } ?>

    <div class="col-1 sizer-element"></div>

</div>
