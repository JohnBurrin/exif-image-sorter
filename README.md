# exif-image-sorter
PHP script to scan a folder of jpeg images, extract the exif date and move the files to a new location based on YEAR/MONTH/DAY.

I needed a script to reorganise the images folder on my NAS, so I created this script.

When set up it is possible to put all your new images into an "import" folder and run this script.

The script will check each file and if it is a jpg file it will inspect the exif data for the date it was taken and then move it.

The destination will be year folders, containing month folders, containing day folders that contain the files.

YEAR/
    MONTH/
            DAY/
                file1.jpg
                ...
                ...

If the file is not a jpg or does not contain exif information the files destination is based on the files last changed time.

Options in the config allow for overwriting the destination and deleting the source file.

Currently the exif php library I'm using only inspects jpg files.
