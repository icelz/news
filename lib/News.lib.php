<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Load news items from multiple sources.
 */
class News {

    static $items = [];

    /**
     * Load news items from each source.
     * @param array $sources
     */
    static function load(array $sources) {
        foreach ($sources as $s) {
            $class = "NewsSource_$s";
            $source = new $class();
            $source->loadItems();
            News::$items = array_merge(News::$items, $source->getItems());
        }
    }

    /**
     * Get all news items from all sources loaded.
     * @return array
     */
    static function getItems() {
        return News::$items;
    }

}