<?php
error_reporting(-1);
ini_set('display_errors', 'On');
echo "<pre>";
$languagePath          = "./languageCsvs/";
$sourceAdsPath         = "./sourceAds/";
$generatedVersionsPath = "./generatedVersions/";

//Read All Languages into arrays
@$languageFiles = array_splice(scandir($languagePath), 2);
@$sourceFiles = array_splice(scandir($sourceAdsPath), 2);

//Run Through All Language Files and Create Array For Referance
foreach ($languageFiles as $currentLanguageFile) {
    $LanguageAdName       = str_replace(".csv", "", $currentLanguageFile);
    $currentLanguageArray = csv_to_array($languagePath . $currentLanguageFile);
    print_r($currentLanguageArray);
    foreach ($currentLanguageArray as $AdLanguage) {
        $currentLang                             = $AdLanguage['Language'];
        //unset($AdLanguage['Language']);
        $language[$LanguageAdName][$currentLang] = array();
        $language[$LanguageAdName][$currentLang] = $AdLanguage;
    }
}

foreach ($sourceFiles as $sourceAdName) {
    foreach ($language[$sourceAdName] as $currentLanagueVersion => $currentLanguageVars) {
        //Create Directory for Current Language
        @mkdir($generatedVersionsPath . $currentLanagueVersion);
        
        @$sourceAdFiles = array_splice(scandir($sourceAdsPath . "/" . $sourceAdName), 2);
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
            //Generate and Create Path to current ad/language version
            $currentAdCreationPath = $generatedVersionsPath . $currentLanagueVersion . "/" . $sourceAdName;
            @mkdir($currentAdCreationPath);
            $currentAdCreationPath = $generatedVersionsPath . $currentLanagueVersion . "/" . $sourceAdName . "/" . $sourceAdSize . "/";
            @mkdir($currentAdCreationPath);
            //Copy Source ad Files to New Lanugage Directory
            recurse_copy($sourceAdsPath . $sourceAdName . "/" . $sourceAdSize . "/", $currentAdCreationPath);
            $varFile = '';
            foreach ($currentLanguageVars as $varName => $varValue) {
                
                if ($varName == "clickTag") {
                    //Lets do something else for clickTag
                    $currentIndexFileSrc = file_get_contents($currentAdCreationPath . "index.html");
                    $currentIndexFileSrc = str_replace("[clickTag]", $varValue, $currentIndexFileSrc);
                    file_put_contents($currentAdCreationPath . "index.html", $currentIndexFileSrc);
                } else {
                    $varFile .= "var " . $varName . " = '" . $varValue . "';\n";
                }
            }
            //Put vars file in root ad directory
            file_put_contents($currentAdCreationPath . "/vars.js", $varFile);
            zip_directory($sourceAdName . "-" . $sourceAdSize, $currentAdCreationPath, $generatedVersionsPath . $currentLanagueVersion);
        }
    }
}

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
