<?php
error_reporting(-1);
ini_set('display_errors', 'On');
$versionPath           = "./variables/";
$sourceAdsPath         = "./source/";
$generatedVersionsPath = "./generated/";
if (!isCLI()){
$url = $_SERVER['REQUEST_URI']; //returns the current URL
$parts = explode('/',$url);
$dir = $_SERVER['SERVER_NAME'];
for ($i = 0; $i < count($parts) - 1; $i++) {
 $dir .= $parts[$i] . "/";
}
$url = "http://".$dir;
} else {
    $url = '';
}

@mkdir($generatedVersionsPath);
@mkdir($sourceAdsPath);
@mkdir($versionPath);

//Read All Versions into arrays
@$versionFiles = array_splice(scandir($versionPath), 2);
@$sourceFiles = array_splice(scandir($sourceAdsPath), 2);

//Run Through All Version Files and Create Array For Referance
foreach ($versionFiles as $currentVersionFile) {
    $VersionAdName             = str_replace(".json", "", $currentVersionFile);
    $currentVersionFileContent = file_get_contents($versionPath . $currentVersionFile);
    $currentVersionArray       = (array) json_decode($currentVersionFileContent, true);
    foreach ($currentVersionArray as $AdVersion) {
        $currentLang                           = $AdVersion['version'];
        $version[$VersionAdName][$currentLang] = array();
        $version[$VersionAdName][$currentLang] = $AdVersion;
    }
}

foreach ($sourceFiles as $sourceAdName) {
    foreach ($version[$sourceAdName] as $currentVersionName => $currentVersionVars) {
        echo "Ad: ".$sourceAdName .  " | Version:" . $currentVersionName . " - Started\n<br>";
        //Create Directory for Current Version
        @mkdir($generatedVersionsPath . $sourceAdName);
        @$sourceAdFiles = array_splice(scandir($sourceAdsPath . "/" . $sourceAdName), 2);
        //Remove Gross Files that we dont want
        if (($key = array_search("banner.jpg", $sourceAdFiles)) !== false) {
            unset($sourceAdFiles[$key]);
        }
        if (($key = array_search("banner.png", $sourceAdFiles)) !== false) {
            unset($sourceAdFiles[$key]);
        }
        if (($key = array_search(".DS_Store", $sourceAdFiles)) !== false) {
            unset($sourceAdFiles[$key]);
        }
        
        foreach ($sourceAdFiles as $sourceAdSize) {
            //Generate and Create Path to current ad/version version
            $currentAdCreationPath = $generatedVersionsPath . $sourceAdName . "/" . $currentVersionName;
            @mkdir($currentAdCreationPath);
            $currentAdCreationPath = $generatedVersionsPath . $sourceAdName . "/" . $currentVersionName . "/" . $sourceAdSize . "/";
            @mkdir($currentAdCreationPath);
            //Copy Source ad Files to New Lanugage Directory
            recurse_copy($sourceAdsPath . $sourceAdName . "/" . $sourceAdSize . "/", $currentAdCreationPath);
            $varFile = '';
            foreach ($currentVersionVars as $varName => $varValue) {
                
                if ($varName == "clickTag") {
                    //Lets do something else for clickTag
                    $currentIndexFileSrc = file_get_contents($currentAdCreationPath . "index.html");
                    $currentIndexFileSrc = str_replace("[clickTag]", $varValue, $currentIndexFileSrc);
                    file_put_contents($currentAdCreationPath . "index.html", $currentIndexFileSrc);
                } else {
                    $varFile .= "var " . $varName . " = '" . str_replace("'", "\'", $varValue) . "';\n";
                }
            }
            //Put vars file in root ad directory
            file_put_contents($currentAdCreationPath . "/vars.js", $varFile);
            @mkdir($generatedVersionsPath . $sourceAdName . "/zips");
            zip_directory($sourceAdName . "-" . $currentVersionName . "-" . $sourceAdSize, $currentAdCreationPath, $generatedVersionsPath . $sourceAdName . "/zips");
            if (!isCLI()){
            echo $sourceAdSize." Link: <a target='_blank' href='".$url.str_replace("./","",$currentAdCreationPath)."'>".$url.str_replace("./","",$currentAdCreationPath)."</a><br>";
            } 
        }
        echo "Finished.<br><br>\n\n";
        //Let screen know what was finished.
        
        
    }
}

echo "Finished.\n";

/* UTILITIE FUNCTIONS !!!!DO NOT CHANGE!!!! */
function recurse_copy($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function csv_to_array($filename = '', $delimiter = ',')
{
    if (!file_exists($filename) || !is_readable($filename))
        return FALSE;
    
    $header = NULL;
    $data   = array();
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
            if (!$header) {
                $header = $row;
            } else {
                $data[] = array_combine($header, $row);
            }
        }
        fclose($handle);
    }
    return $data;
}

function isCLI()
{
    return (php_sapi_name() === 'cli');
}

function zip_directory($zipName, $path, $dest)
{
    $rootPath = $path;
    
    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($dest . "/" . $zipName . ".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
    
    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
    
    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir()) {
            // Get real and relative path for current file
            $filePath     = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            
            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }
    
    // Zip archive will be created only after closing object
    $zip->close();
    
}
