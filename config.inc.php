<?php
# Display debug messages
define("DEBUG",TRUE);

# Allow destination files to be overwritten
define("ALLOW_OVERWRITE",FALSE);

# Delete source files - becareful it does it as it goes along
define("ALLOW_DELETE",FALSE);

# Location of source files
define("IMPORT_FOLDER","/pictures/_Import-images");

# Location of destination gallery folder
define("GALLERY_FOLDER","/pictures");

# Allowed exif files, these are the files that contain exif information to use as
# the destination location
$exif_image_types = array("jpg","jpeg");
