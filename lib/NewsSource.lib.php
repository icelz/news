<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Base class for news sources.
 */
class NewsSource {

    private $items = [];

    function getItems(): array {
        return $this->items;
    }

    /**
     * Fetch news items from this source.
     */
    function loadItems() {
        // Do nothing, because this is a dummy source
    }

}