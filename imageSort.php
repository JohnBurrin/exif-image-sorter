<?php
function fetchExif($fileName){
	//die((string)$fileName);
	if(DEBUG) echo "Attempting to fetch Exif information for $fileName \n";
	$fileName = chop($fileName);
	if (is_file($fileName)){
		if($exif = exif_read_data($fileName, "EXIF",TRUE)){
			foreach ($exif as $key => $section) {
	    			foreach ($section as $name => $val) {
	    				if (strstr($name, "DateTimeOriginal")){
//	    					print_r($exif);die($val);
	        				return chop($val);
	    				}
	    			}
			}
		}else{
			die("xif Failed:");
			return false;
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
function createFolder($dateTime, $yearSep = ":"){
	list($date,$time) = explode(" ", $dateTime);
	list($year,$month,$day) = explode($yearSep, $date);
	$d = "$year/$month/$day";
	if(!is_dir("$year/$month/$day")){
		if(DEBUG) echo "No $year/$month/$day Folder so attempting to Create\n";
		if (@mkdir("$year/$month/$day",0777,true)){
			if(DEBUG) echo "$year/$month/$day Created \n";
			$d = "$year/$month/$day";
		}else{
			if(DEBUG) echo "$year/$month/$day Exists\n";
			$d = "$year/$month/$day";
		}
		
	}
	return $d;
}
	        		
/**
 * 
 * Array to hold list of files
 * @var array
 */
 define("DEBUG",true);
$listOfFiles = array();
$startFolder = getcwd();
$overWrite = 0;
$path = "";
if ($listOfFiles = file("images.lst")){
	foreach ($listOfFiles as $aFile){
		$aFile = chop($aFile);
		$path_info = pathinfo($aFile);
		print_r($path_info);
		if (strtolower($path_info['extension']) == "jpg"){
			$date = fetchExif($aFile);
		}else{
			$date = date("Y:m:d 00:0000", filectime($aFile));
		}

		if($date){
			$cdw = createFolder($date);
			print "Current Working directory $cdw\n";
			print "copy(\"$aFile\", \"./$cdw/{$path_info['basename']}\");\n";
  			if($overWrite == 0 && file_exists($cdw. "/" . $path_info['basename'])){
				print "File exists. Skipping ./$cdw/{$path_info['basename']}\n";
				//copy("$aFile", "./$cdw/{$path_info['basename']}");
			}elseif($overWrite == 1 && file_exists($cdw. "/" . $path_info['basename'])){
				copy("$aFile", "./$cdw/{$path_info['basename']}");
				print "File Exists Overwriting. $cdw/{$path_info['basename']}\n";
			}else{
				copy("$aFile", "./$cdw/{$path_info['basename']}");
                                print "Copying New file. $cdw/{$path_info['basename']}\n";

			}
		}else{
			echo "Failed:";
		}
			// change to the folder where the image is located
	//	die();
	}
}else{
	die("File open Error");
}
echo "Complete";


?>
