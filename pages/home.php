<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

News::load($SETTINGS["sources"]["news"]);

$newsitems = News::getItems();

// Sort by category
$itemsbycategory = [];
foreach ($newsitems as $item) {
    $itemsbycategory[$item->getCategory()->toString()][] = $item;
}
?>

<p class="lead">
    <i class="fas fa-newspaper fa-fw"></i> <?php $Strings->get("Headlines"); ?>
</p>
<?php

?>
<div class="list-group mb-4">
    <?php
    $count = 0;
    foreach ($itemsbycategory["general"] as $item) {
        if ($count >= 6) {
            break;
        }
        $count++;
        ?>
        <div class="list-group-item d-flex justify-content-between">
            <a class="text-dark" href="<?php echo $item->getURL(); ?>" target="_BLANK">
                <h4>
                    <?php echo htmlentities($item->getHeadline()); ?>
                </h4>
                <p class="text-muted"><?php echo htmlentities($item->getSource()); ?></p>
            </a>

            <?php if (!empty($item->getImage())) { ?>
                <img src="<?php echo Thumbnail::getThumbnailCacheURL($item->getImage(), 100); ?>" alt="" class="img-fluid">
            <?php } ?>
        </div>
        <?php
    }
    ?>
</div>
<?php
?>