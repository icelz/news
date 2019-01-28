<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

class ApiFetcher {

    /**
     * Gets a URL.  Caches and returns the result, but if the cache has a recent copy
     * return that instead of making the request.
     *
     * Basically, the goal is to stay within the free tier of paid APIs :)
     *
     * @global Medoo $database
     * @param string $url
     * @param array $params key=>value array of URL parameters
     * @param array $extraParams key=>value array of URL parameters that will be ignored when checking the cache
     * @param string $expires When the fetched resource should expire from the cache.  A string parsable by strtotime()
     * @return string The content of the requested URL.
     */
    static function get(string $url, array $params = [], array $extraParams = [], string $expires = "+15 minutes"): string {
        global $database;

        // Delete stale records
        $database->delete("requestcache", ["expires[<=]" => date("Y-m-d H:i:s")]);

        // Make sure the params are in the same order every time
        ksort($params);

        $urlparams = [];
        foreach ($params as $k => $v) {
            $urlparams[] = urlencode($k) . "=" . urlencode($v);
        }

        // Make the URL that will be cached
        $cacheurl = $url . "?" . implode("&", $urlparams);

        foreach ($extraParams as $k => $v) {
            $urlparams[] = urlencode($k) . "=" . urlencode($v);
        }

        if ($database->has("requestcache", ["AND" => ["url" => $cacheurl, "expires[>]" => date("Y-m-d H:i:s")]])) {
            return $database->get("requestcache", "content", ["AND" => ["url" => $cacheurl, "expires[>]" => date("Y-m-d H:i:s")]]);
        }

        // Make the actual URL that will be requested
        $requesturl = $url . "?" . implode("&", $urlparams);

        $content = file_get_contents($requesturl);

        // Only insert into db if it didn't fail horribly
        if ($content !== FALSE) {
            $database->insert("requestcache", ["url" => $cacheurl, "expires" => date("Y-m-d H:i:s", strtotime($expires)), "content" => $content]);
        }

        return $content;
    }

    /**
     * Clear the given URL from the cache.
     *
     * @global Medoo $database
     * @param string $url
     * @param array $params key=>value array of URL parameters
     */
    static function removeFromCache(string $url = "", array $params = []) {
        global $database;

        // Make sure the params are in the same order every time
        ksort($params);

        $urlparams = [];
        foreach ($params as $k => $v) {
            $urlparams[] = urlencode($k) . "=" . urlencode($v);
        }

        $cacheurl = $url . "?" . implode("&", $urlparams);

        $database->delete("requestcache", ["url" => $cacheurl]);
    }

}
