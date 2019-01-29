<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

class NewsSource_NewsAPI extends NewsSource {

    private $items = [];

    function getItems(): array {
        return $this->items;
    }

    function loadItems() {
        $this->loadHeadlines((new NewsCategory(NewsCategory::BUSINESS)));
        $this->loadHeadlines((new NewsCategory(NewsCategory::ENTERTAINMENT)));
        $this->loadHeadlines((new NewsCategory(NewsCategory::GENERAL)));
        $this->loadHeadlines((new NewsCategory(NewsCategory::HEALTH)));
        $this->loadHeadlines((new NewsCategory(NewsCategory::SCIENCE)));
        $this->loadHeadlines((new NewsCategory(NewsCategory::SPORTS)));
        $this->loadHeadlines((new NewsCategory(NewsCategory::TECHNOLOGY)));
    }

    private function loadHeadlines($category = null, string $country = "us", $apikey = null) {
        global $SETTINGS;
        if (is_null($category)) {
            $category = new NewsCategory(NewsCategory::GENERAL);
        }
        if (is_null($apikey)) {
            $apikey = $SETTINGS['apikeys']['newsapi.org'];
        }
        $url = "https://newsapi.org/v2/top-headlines";
        $params = [];
        if (!empty($country)) {
            $params['country'] = $country;
        }
        if (!empty($category->toString())) {
            $params['category'] = $category->toString();
        }
        $items = [];
        $json = ApiFetcher::get($url, $params, ["apiKey" => $apikey], "+1 hour");
        $data = json_decode($json, TRUE);
        if ($data['status'] != "ok") {
            return [];
        }
        $articles = $data['articles'];
        foreach ($articles as $n) {
            $title = $n['title'];
            $image = $n['urlToImage'];
            if (is_null($image)) {
                continue;
            }
            $url = $n['url'];
            $source = $n['source']['name'];
            $timestamp = strtotime($n['publishedAt']);

            $items[] = new NewsItem($title, $image, $url, $source, "NewsAPI.org", $timestamp, $category);
        }

        $this->items = array_merge($this->items, $items);
    }

}
