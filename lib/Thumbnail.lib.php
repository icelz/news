<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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

        ob_end_flush();
        ob_start();
        imagejpeg($output, null, 75);
        $imagedata = ob_get_contents();
        ob_end_clean();

        // return resized image
        return $imagedata; // if you need to use it
    }

    /**
     * Encodes some data to base64.
     * @param type $img
     * @return string
     */
    static function imgToBase64($img): string {
        return base64_encode($img);
    }

    /**
     * Get the base64 data: URI for a jpeg image
     * @param type $img
     * @return string
     */
    static function jpegToBase64URI($img): string {
        return "data:image/jpeg;base64," . self::imgToBase64($img);
    }

}
