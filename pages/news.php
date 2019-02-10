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

    <?php
    foreach ($newsitems as $item) {
        echo $item->generateGridCard(true);
    }
    ?>

    <div class="col-1 sizer-element"></div>

</div>
