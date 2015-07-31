<?php

/**
 * A script to scan a folder structure extract the EXIF data from JPGs and use
 * the date to organise the files into a new folder structure.
 * 
 * At the moment the script quries an input list of the files location to process
 * E.g 
  ../inboun2/DSC_0001.JPG
  ../inboun2/DSC_0002.JPG
  ../inboun2/DSC_0003.JPG
  ../inboun2/DSC_0004.JPG
 * 
 * So a a file located at /pictures/mypetdog.jpg
 * and the exif retruns a date of 25/04/2001
 * the file will be moved to /2001/04/25/mypetdog.jpg
 * 
 * 
 */
include_once 'config.inc.php';
include_once 'functions.inc.php';

/*
 * Load an array with a list of files from and external file
 */

if (is_dir(IMPORT_FOLDER)) {
    foreach (new DirectoryIterator(IMPORT_FOLDER) as $fileInfo) {
        if ($fileInfo->isDot())
            continue;
        $list_of_files[] = $fileInfo->getPathname();
    }
}

if (isset($list_of_files) && is_array($list_of_files)) {
    foreach ($list_of_files as $each_file) {
        $each_file = chop($each_file);
        $path_info = pathinfo($each_file);
        log_message("Processing $each_file\n");
        if (file_exists($each_file)) {
            // if we can get a date from exif overwrite the date
            if (in_array(strtolower($path_info['extension']), $exif_image_types)) {
                $date = fetchExif($each_file);
                if (!$date) {
                    $date = date("Y:m:d 00:0000", filectime($each_file));
                    log_message("No date in EXIF data in file $each_file so using files timestamp - $date\n");
                }

                $current_working_directory = createFolder($date);
                log_message("Current Working directory $current_working_directory\n");

                if (ALLOW_OVERWRITE === FALSE && file_exists($current_working_directory . "/" . $path_info['basename'])) {
                    log_message("File exists. Skipping $current_working_directory/{$path_info['basename']}\n");
                    copy("$each_file", "$current_working_directory/{$path_info['basename']}");
                } elseif (ALLOW_OVERWRITE === TRUE && file_exists($current_working_directory . "/" . $path_info['basename'])) {

                    log_message("File Exists Overwriting. $current_working_directory/{$path_info['basename']}\n");
                    log_message("Command used 'copy(\"$each_file\", \"$current_working_directory/{$path_info['basename']}\");'\n");
                    copy("$each_file", "$current_working_directory/{$path_info['basename']}");
                } else {

                    log_message("Copying New file. $current_working_directory/{$path_info['basename']}\n");
                    log_message("Command used 'copy(\"$each_file\", \"$current_working_directory/{$path_info['basename']}\");'\n");
                    copy("$each_file", "$current_working_directory/{$path_info['basename']}");
                }
                
            } else {
                log_message("{$path_info['extension']} for $each_file is not in the valid extensions list\n");
                $date = date("Y:m:d 00:0000", filectime($each_file));
                $current_working_directory = createFolder($date);
                copy("$each_file", "$current_working_directory/{$path_info['basename']}");
            }
            if (ALLOW_DELETE === TRUE) {
                    log_message("Deleting orginal file - $each_file");
                    unlink("$each_file");
                }
        } else {
            echo "Failed: File does not exist\n";
        }
    }
} else {
    log_message("No files found in " . IMPORT_FOLDER . " or folder is missing");
}
log_message("Finished");
