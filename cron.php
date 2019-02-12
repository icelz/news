<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

// Remove old thumbnails
echo "Pruning thumbnail cache...\n";
$path = __DIR__ . '/cache/thumb/';
if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
        if ((time() - filectime($path . $file)) > 60 * 60 * 24) {
            if (preg_match('/\.jpg$/i', $file)) {
                echo "Deleting $file\n";
                unlink($path . $file);
            }
        }
    }
}