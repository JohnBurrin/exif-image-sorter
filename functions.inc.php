<?php

/**
 * 
 * @param type $filepath
 * @return boolean
 */
function fetchExif($filepath) {
    //die((string)$fileName);
    if (DEBUG === TRUE) {
        echo "Attempting to fetch Exif information for $filepath \n";
    }
    $fileName = chop($filepath);
    if (is_file($fileName)) {
        $exif = xif_read_data($fileName, "EXIF", TRUE);
        if ($exif) {
            return process_exif($exif,"DateTimeOriginal");
        }
        if (DEBUG === TRUE) {
            print "This image has no EXIF data\n";
        }
        return FALSE;
    }
}

/**
 * Return a chopped value from exif array
 * @param type $exif
 * @param type $value
 * @return type
 */
function process_exif($exif,$value) {
    foreach ($exif as $key => $section) {
        foreach ($section as $name => $val) {
            if (strstr($name, $value)) {
                log_message("Extracting DateTimeOriginal data\n");
                return chop($val);
            }
        }
    }
}

/**
 * 
 * Creates a folder structure from a date ignoring time
 * e.g 2006:09:11 00:0000 
 * @param String $dateTime
 * @return String
 */
function createFolder($dateTime, $yearSep = ":") {
    list($date, $time) = explode(" ", $dateTime);
    list($year, $month, $day) = explode($yearSep, $date);
    $d = GALLERY_FOLDER."/$year/$month/$day";
    if (!is_dir(GALLERY_FOLDER."/$year/$month/$day")) {
        log_message("No $year/$month/$day Folder so attempting to Create\n");
        if (@mkdir(GALLERY_FOLDER."/$year/$month/$day", 0777, true)) {
            log_message(GALLERY_FOLDER."/$year/$month/$day Created \n");
            $d = GALLERY_FOLDER."/$year/$month/$day";
        } else {
            log_message(GALLERY_FOLDER."/$year/$month/$day Exists\n");
            $d = GALLERY_FOLDER."/$year/$month/$day";
        }
    }
    return $d;
}

function log_message($message) {
    if (DEBUG === TRUE) {
        print $message . "\n";
    }
}
