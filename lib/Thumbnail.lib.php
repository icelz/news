<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

use \SKleeschulte\Base32;

class Thumbnail {

    /**
     * Fetches an image URL, resizes it, and returns JPEG thumbnail data.
     * Based on https://stackoverflow.com/a/29024968
     * @param string $url
     * @param int $width
     * @param int,bool $height
     * @return type
     */
    static function getThumbnailFromUrl(string $url, int $width = 150, $height = true) {

        // download and create gd image
        $image = imagecreatefromstring(file_get_contents($url));

        // calculate resized ratio
        // Note: if $height is set to TRUE then we automatically calculate the height based on the ratio
        $height = $height === true ? (imagesy($image) * $width / imagesx($image)) : $height;

        // create image
        $output = imagecreatetruecolor($width, $height);
        imagecopyresampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));

        ob_start();
        imagejpeg($output, null, 75);
        $imagedata = ob_get_contents();
        ob_end_clean();

        // return resized image
        return $imagedata; // if you need to use it
    }

    /**
     * Return a thumbnail URL relative to the app root for the given image URL.
     * @param string $url
     * @param int $width
     * @param int,bool $height
     * @return string
     */
    static function getThumbnailCacheURL(string $url, int $width = 150, $height = true): string {
        global $database;
        $encodedfilename = Base64::encode($url);
        if (strlen("$encodedfilename.$width.jpg") > 250) {
            // We're too long for common filesystems
            $encodedfilename = "SHA1_" . sha1($url);
            if (!$database->has("imagecache", ["url" => $url])) {
                $database->insert("imagecache", ["url" => $url, "hash" => $encodedfilename, "created" => date("Y-m-d H:i:s")]);
            }
        }
        $path = "cache/thumb/$encodedfilename.$width.jpg";

        return $path;
    }

    /**
     * Generate a thumbnail and save it to the cache
     * @param string $url
     * @param int $width
     * @param type $height
     * @return type
     */
    static function addThumbnailToCache(string $url, int $width = 150, $height = true) {
        global $database;
        $encodedfilename = Base64::encode($url);
        if (strlen("$encodedfilename.$width.jpg") > 250) {
            // We're too long for common filesystems
            $encodedfilename = "SHA1_" . sha1($url);
            if (!$database->has("imagecache", ["url" => $url])) {
                $database->insert("imagecache", ["url" => $url, "hash" => $encodedfilename, "created" => date("Y-m-d H:i:s")]);
            }
        }
        $path = "cache/thumb/$encodedfilename.$width.jpg";
        $image = self::getThumbnailFromUrl($url, $width, $height);
        file_put_contents(__DIR__ . "/../$path", $image);
        return $image;
    }

}
