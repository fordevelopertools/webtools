<?php

    /* 
        - CREATE BY FORDEVELOPERTOOLS WEB DEVELOPER TEAM
        - AUTHOR: NUR SHODIK ASSALAM
        - VERSION  1.2 BETA
        - RELEASE 5-20-2022
        - UPDATE 5-24-2022
    */

    // started First
    session_start();
    date_default_timezone_set('asia/jakarta');

    class WebTools
    {   
        public $userImage = 'https://raw.githubusercontent.com/fordevelopertools/webtools/89bb08d74b725a17704ffb5cafa3c6f4e8acd7a7/logo.png';
        public $userName = 'NUR SHODIK ASSALAM';
        public $authPass = 'root!';
        public $loadImage = 'https://raw.githubusercontent.com/fordevelopertools/webtools/main/loading.gif';
        public $malwareCheckPayload = 'https://raw.githubusercontent.com/fordevelopertools/webtools/main/listing_malware_payload.txt';

        public $dirLoc = __DIR__;
        public $fileLoc = __FILE__;
        public $baseLink = './webtools.php';

        public function __construct(){
            // something
        }

        public function domainName(){
            $getDomain = trim($_SERVER['SERVER_NAME']) !== '' ?  trim($_SERVER['SERVER_NAME']) :
                (trim($_SERVER['SERVER_NAME']) ? trim($_SERVER['SERVER_NAME']) : '');
            return $getDomain;
        }

        public function getOS(){
            $getOsName = PHP_OS ? PHP_OS : 
                (PHP_OS_FAMILY ? PHP_OS_FAMILY : FALSE);
            return $getOsName;
        }

        public function is_shell_exec_available() {
            if (in_array(strtolower(ini_get('safe_mode')), array('on', '1'), true) || (!function_exists('exec'))) {
                return false;
            }
            $disabled_functions = explode(',', ini_get('disable_functions'));
            $exec_enabled = !in_array('exec', $disabled_functions);
            return ($exec_enabled) ? true : false;
        }

        public function ramInfo($getMem = null, $returnType = null){

            // check exec avaiable
            if($this->is_shell_exec_available()){
                // win exec get ram info
                if (strpos(strtolower($this->getOS()), 'win') !== false) {

                    @exec("wmic ComputerSystem get TotalPhysicalMemory", $totalRam);
                    @exec("wmic OS get FreePhysicalMemory", $freeRam);

                    if($totalRam == false || $totalRam == '' || 
                    $freeRam == false || $freeRam == '' ||
                    !is_array($totalRam) || !is_array($freeRam) ||
                    !isset($totalRam[0]) || trim($totalRam[0]) !== 'TotalPhysicalMemory' ||
                    !isset($freeRam[0]) || trim($freeRam[0]) !== 'FreePhysicalMemory' ||
                    !isset($totalRam[1]) || !isset($freeRam[1])
                    ){
                        return $returnType == null ? null : '0';
                    }else{

                        $valTotalRam = trim($totalRam[1]);
                        $valFreeRam = trim($freeRam[1]);
                        $valTotalRamKb = $valTotalRam / 1024;
                        $valUsedRam = $valTotalRamKb - $valFreeRam;

                        if ($getMem == 'total') {
                            $getMemTotal = ($valTotalRam / 1024) / 1024;
                            return number_format($getMemTotal, 2);
                        } elseif ($getMem == 'free') {
                            if ($returnType == null) {
                                $getMemFree = $valFreeRam / 1024;
                                return number_format($getMemFree, 2);
                            } else {
                                $getPercent = ($valFreeRam / $valTotalRamKb) * 100;
                                return number_format($getPercent, 2);
                            }
                        } elseif ($getMem == 'used') {
                            if ($returnType == null) {
                                $getMemUsed = $valUsedRam / 1024;
                                return number_format($getMemUsed, 2);
                            } else {
                                $getPercent = ($valUsedRam / $valTotalRamKb) * 100;
                                return number_format($getPercent, 2);
                            }
                        } else {
                            $getMemTotal = ($valTotalRam / 1024) / 1024;
                            return number_format($getMemTotal, 2);  
                        }
                    }
                
                } 
                // linux exec get ram info
                elseif(strpos(strtolower($this->getOS()), 'linux') !== false) {

                    if (file_exists('/proc/meminfo')) {
                        
                        $data = explode("\n", file_get_contents('/proc/meminfo'));
                        $meminfo = array();
                        $str_rep = array('KB', 'kb', 'Kb', 'kB');
                        $str_rep2 = array('', '', '', '');
                        foreach ($data as $line) {
                            list($key, $val) = explode(":", $line);
                            $meminfo[trim($key)] = trim(str_replace($str_rep, $str_rep2, $val));
                        }

                        if (is_array($meminfo) && count($meminfo) > 0) {
                            
                            if(
                                !isset($meminfo['MemTotal']) || !isset($meminfo['MemFree'])
                            ){
                                return $returnType == null ? null : '0';
                            }else{

                                $valTotalRam = trim($meminfo['MemTotal']);
                                $valFreeRam = trim($meminfo['MemFree']);
                                $valTotalRamKb = $valTotalRam;
                                $valUsedRam = $valTotalRamKb - $valFreeRam;

                                if ($getMem == 'total') {
                                    $getMemTotal = $valTotalRam / 1024;
                                    return number_format($getMemTotal, 2);
                                } elseif ($getMem == 'free') {
                                    if ($returnType == null) {
                                        $getMemFree = $valFreeRam / 1024;
                                        return number_format($getMemFree, 2);
                                    } else {
                                        $getPercent = ($valFreeRam / $valTotalRamKb) * 100;
                                        return number_format($getPercent, 2);
                                    }
                                } elseif ($getMem == 'used') {
                                    if ($returnType == null) {
                                        $getMemUsed = $valUsedRam / 1024;
                                        return number_format($getMemUsed, 2);
                                    } else {
                                        $getPercent = ($valUsedRam / $valTotalRamKb) * 100;
                                        return number_format($getPercent, 2);
                                    }
                                } else {
                                    $getMemTotal = $valTotalRam / 1024;
                                    return number_format($getMemTotal, 2);  
                                }
                            }
                        } else {
                            return $returnType == null ? null : '0';
                        }
                    } else {
                        return $returnType == null ? null : '0';
                    }
                } else {
                    return $returnType == null ? null : '0';
                }
            }else{
                return $returnType == null ? null : '0';
            }
        }

        public function filePermInfo($pathLoc = null){
            $perms = fileperms(trim($pathLoc));

            switch ($perms & 0xF000) {
                case 0xC000: // socket
                    $info = 's';
                    break;
                case 0xA000: // symbolic link
                    $info = 'l';
                    break;
                case 0x8000: // regular
                    $info = 'r';
                    break;
                case 0x6000: // block special
                    $info = 'b';
                    break;
                case 0x4000: // directory
                    $info = 'd';
                    break;
                case 0x2000: // character special
                    $info = 'c';
                    break;
                case 0x1000: // FIFO pipe
                    $info = 'p';
                    break;
                default: // unknown
                    $info = 'u';
            }

            // Owner
            $info .= (($perms & 0x0100) ? 'r' : '-');
            $info .= (($perms & 0x0080) ? 'w' : '-');
            $info .= (($perms & 0x0040) ?
                        (($perms & 0x0800) ? 's' : 'x' ) :
                        (($perms & 0x0800) ? 'S' : '-'));

            // Group
            $info .= (($perms & 0x0020) ? 'r' : '-');
            $info .= (($perms & 0x0010) ? 'w' : '-');
            $info .= (($perms & 0x0008) ?
                        (($perms & 0x0400) ? 's' : 'x' ) :
                        (($perms & 0x0400) ? 'S' : '-'));

            // World
            $info .= (($perms & 0x0004) ? 'r' : '-');
            $info .= (($perms & 0x0002) ? 'w' : '-');
            $info .= (($perms & 0x0001) ?
                        (($perms & 0x0200) ? 't' : 'x' ) :
                        (($perms & 0x0200) ? 'T' : '-'));

            return $info;
        }

        public function varClean($vartoClean = null){
            $vartoClean = trim(strip_tags($vartoClean));
            return $vartoClean;
        }

        public function redirect($url = null){
            if (trim($url) !== '') {
                header('location: '.$url);
            } else {
                return false;
            }
        }

        public function checkLogin(){
            if (isset($_SESSION['login'])) {
                return true;
            } else {
                return false;
            }
        }

        public function login_auth($passInput = null){
            if (trim($passInput) !== null) {
                if (trim($passInput) == $this->authPass) {
                    $_SESSION['login']  =  $passInput;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public function logout($link = './'){
            session_destroy();
            self::redirect($link);
        }

        public function download($filename = null){
            if($filename !== null){
                $file = $filename; 
                if(is_file($file)){
                    header("Expires: 0");
                    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                    header("Cache-Control: no-store, no-cache, must-revalidate");
                    header("Cache-Control: post-check=0, pre-check=0", false);
                    header("Pragma: no-cache");
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    $basename = pathinfo($file, PATHINFO_BASENAME);
                    header("Content-type: application/".$ext);
                    header('Content-length: '.filesize($file));
                    header("Content-Disposition: attachment; filename=\"$basename\"");
                    ob_clean(); 
                    flush();
                    readfile($file);
                    exit;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public function chekRoot(){
            if (is_writable($_SERVER['DOCUMENT_ROOT'])) {
                return true;
            } else {
                return false;
            }
        }

        public function getDir(){
            return trim($_SERVER['DOCUMENT_ROOT']);
        }

        public function getDiskSize($dir = './'){
            $totalSpace = disk_total_space($dir);
            $totalSpace = ($totalSpace / 1024) / 1024;
            return number_format($totalSpace, 2);
        }

        public function getFreeDisk($dir = './', $returnType = null){
            
            $totalSpace = disk_total_space($dir);
            $freeSpace = disk_free_space($dir);

            if ($returnType == 'percent') {

                $getPercent = ($freeSpace / $totalSpace) * 100;
                return number_format($getPercent, 2);
                
            } else {
                $freeSpace = ($freeSpace / 1024) / 1024;
                return number_format($freeSpace, 2);
            }
        }

        public function diskUsedSpace($dir = './', $returnType = null){
            $totalSpace = disk_total_space($dir);
            $freeSpace = disk_free_space($dir);

            $usedSpace = $totalSpace - $freeSpace;

            if ($returnType == 'percent') {

                $getPercent = ($usedSpace / $totalSpace) * 100;
                return number_format($getPercent, 2);

            }else{
                $usedSpace = ($usedSpace / 1024) / 1024;
                return number_format($usedSpace, 2);
            }

        }
        
        public function cekdir(){
            if (isset($_GET['path'])) {
                $lokasi = trim($_GET['path']);
            } else {
                $lokasi = getcwd();
            }
            if (is_writable($lokasi)) {
                return true;
            } else {
                return false;
            }
        }

        public function loadMetaLink(){
            echo '
                <meta name="robots" content="noindex">
                <meta name="googlebot" content="noindex">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/fontawesome.min.css" integrity="sha512-xX2rYBFJSj86W54Fyv1de80DWBq7zYLn2z0I9bIhQG+rxIF6XVJUpdGnsNHWRa6AvP89vtFupEPDP8eZAtu9qA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/brands.min.css" integrity="sha512-OivR4OdSsE1onDm/i3J3Hpsm5GmOVvr9r49K3jJ0dnsxVzZgaOJ5MfxEAxCyGrzWozL9uJGKz6un3A7L+redIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            ';
        }

        public function goHome() {
            echo '
                <style>
                    .reload-page {
                        color: white;
                        background: var(--primary-color);
                        padding: 15px;
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        border-radius: 50%;
                        height: 40px;
                        width: 40px;
                        font-size: 14px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        text-decoration: none;
                        border: 2px solid white;
                    }
                    .reload-page:hover {
                        color: #6c757d;
                    }
                </style>
                <!-- reload page -->
                <a href="'. $this->baseLink .'" class="reload-page">
                    <i class="fa-solid fa-home"></i>
                </a>
            ';
        }

        public function addCssGlobal($addCss = null) {
            echo '
                <style>
                    :root {
                        --primary-color: #1B1E2B;
                        --secondary-color: #292D3E;
                        --body-text-header-font-size: 16px;
                        --body-text-content-font-size: 14px;
                    }
                    
                    body {
                        font-family: "Dosis", "cursive" !important;
                        background: var(--secondary-color) !important;
                        font-size: var(--body-text-content-font-size) !important;
                    }
                    
                    '. $addCss .'

                </style>
            ';
        }

        public function strSearch($haystack, $needles=array(), $offset=0) {
            $chr = array();
            foreach($needles as $needle) {
                    $res = strpos($haystack, $needle, $offset);
                    if ($res !== false) $chr[$needle] = $res;
            }
            if(empty($chr)) return false;
            return min($chr);
        }

        public function fileScan($folderScan = null, $searchFiles = array(), $searchBy = null/*, &$results = array()*/){
            if ($folderScan !== null) {

                $dir = $folderScan;
                
                if (is_dir($dir)) {
                    
                    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderScan));
                    $files = array(); 
                    foreach ($rii as $file) {
                        if ($file->isDir()){
                            if ($file != "." && $file != "..") {
                                if($searchBy == 'folder' || $searchBy == 'ff'){
                                    //check if search empty or not
                                    if (count($searchFiles) > 0) {
                                        if ($this->strSearch(trim($file->getPathname()), $searchFiles, 0)) {
                                            $files[] = [
                                                'item'  =>  $file->getPathname(),
                                                'type'  =>  'folder'
                                            ];
                                        }
                                    } else {
                                        $files[] = [
                                            'item'  =>  $file->getPathname(),
                                            'type'  =>  'folder'
                                        ];
                                    }
                                }
                            }
                            continue;
                        }

                        //check if search empty or not
                        if (count($searchFiles) > 0) {
                            if ($this->strSearch(trim($file->getPathname()), $searchFiles, 0)) {
                                if($searchBy == 'file' || $searchBy == 'ff'){
                                    $files[] = [
                                        'item'  =>  $file->getPathname(),
                                        'type'  =>  'file'
                                    ];
                                }
                            } 
                        }else {
                            if($searchBy == 'file' || $searchBy == 'ff'){
                                $files[] = [
                                    'item'  =>  $file->getPathname(),
                                    'type'  =>  'file'
                                ]; 
                            }
                        }
                        
                    }

                    return $files;

                } else {
                    return false;
                }
                
                // $files = scandir($dir);
            
                // foreach ($files as $key => $value) {
                //     $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                //     if (!is_dir($path)) {
                        
                //         // check if search empty or not
                //         if (count($searchFiles) > 0) {
                //             if ($this->strSearch(trim($path), $searchFiles, 0)) {
                //                 if($searchBy == 'file' || $searchBy == 'ff'){
                //                     $results[] = $path;
                //                 }
                //             }
                //         } else {
                //             if($searchBy == 'file' || $searchBy == 'ff'){
                //                 $results[] = $path;
                //             }
                //         }
                //     } else if ($value != "." && $value != "..") {
                //         self::fileScan($path, $searchFiles, $searchBy, $results[$path]);
                //         if($searchBy == 'folder' || $searchBy == 'ff'){
                //             $results[] = $path;
                //         }
                        
                //     }
                // }
                // print_r($results);
            }else{
                return false;
            }
        }

        function ff_delete($pathLoc = null){
            $pathLoc = trim($pathLoc);
            if (trim($pathLoc) !== '') {
                if(is_dir(trim($pathLoc))){

                    $dir = $pathLoc;
                
                    if (is_dir($dir)) {
                        
                        $rii = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                            RecursiveIteratorIterator::CHILD_FIRST);
                        $openDir = @scandir($dir);
                        $items = array(); 

                        if (count($openDir) > 2) {

                            foreach ($rii as $file) {
                                $pathItem = $file->getPathname();
                                if ($file->isDir()){

                                    if (substr($pathItem, -1) !== "." && substr($pathItem, -2) !== "..") {
                                    
                                        // remove directories
                                        $removeDir = @rmdir($pathItem);
                                        if($removeDir){ 
                                            $items[] = [
                                                'item_delete'   =>  $pathItem,
                                                'item_type'     =>  'directory',
                                                'status'        =>  'success'
                                            ];
                                        } else{
                                            $items[] = [
                                                'item_delete'   =>  $pathItem,
                                                'item_type'     =>  'directory',
                                                'status'        =>  'failed'
                                            ];
                                        }
                                    }

                                    continue;
    
                                }else{
    
                                    // remove files
                                    $deleteFile = @unlink($pathItem);

                                    if($deleteFile){ 
                                        $items[] = [
                                            'item_delete'   =>  $pathItem,
                                            'item_type'     =>  'file',
                                            'status'        =>  'success'
                                        ];

                                    }else{
                                        $items[] = [
                                            'item_delete'   =>  $pathItem,
                                            'item_type'     =>  'file',
                                            'status'        =>  'failed'
                                        ];
                                    }
                                }
                            }

                            // remove directories
                            $removeDir = @rmdir($dir);
                            if($removeDir){

                                $items[] = [
                                    'item_delete'   =>  $dir,
                                    'item_type'     =>  'directory',
                                    'status'        =>  'success'
                                ];

                            }else{

                                $items[] = [
                                    'item_delete'   =>  $dir,
                                    'item_type'     =>  'directory',
                                    'status'        =>  'failed'
                                ];
                            }


                            
    
                            return $items;
                            
                        } else {
                            // remove directories
                            $removeDir = @rmdir($dir);
                            if($removeDir){

                                $items[] = [
                                    'item_delete'   =>  $dir,
                                    'item_type'     =>  'directory',
                                    'status'        =>  'success'
                                ];

                                return $items;

                            }else{

                                return false;
                            }
                            
                        }

                    } else {
                        return false;
                    }
                    
                }else{

                    // remove file
                    if (file_exists($pathLoc)) {
                        $items = [];
                        $deleteFile = @unlink($pathLoc);
                        if ($deleteFile) {
                            $items[] = [
                                'item_delete'   =>  $pathLoc,
                                'item_type'     =>  'file',
                                'status'        =>  'success'
                            ];

                            return $items;
                        } else {
                            return false;
                        }
                        
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
            
        }

        public function zipManager($dirForZip = null, $fileNameZip = null, $saveTo = null, $zipAction = null){

            if ($zipAction !== null && $zipAction == 'zip' && 
                is_dir(trim($saveTo)) && trim($fileNameZip) !== '' && 
                is_dir(trim($dirForZip))
            ) {

                // set variable
                $folder_to_zip = trim($dirForZip);
                $save_to = trim($saveTo);
                $file_name_zip = trim($fileNameZip);
                $dataZip = [];
                $total_item = 0;

                if(is_dir($folder_to_zip)){

                    // Get real path for our folder
                    $rootPath = realpath($folder_to_zip);

                    // Initialize archive object
                    $zip = new ZipArchive();
                    $zip->open($save_to .''. $file_name_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

                    // Create recursive directory iterator
                    /** @var SplFileInfo[] $files */
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($rootPath),
                        RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    foreach ($files as $name => $file)
                    {
                        // Skip directories (they would be added automatically)
                        if (!$file->isDir())
                        {
                            // Get real and relative path for current file
                            $filePath = $file->getRealPath();
                            $relativePath = substr($filePath, strlen($rootPath) + 1);

                            // Add current file to archive
                            $zip->addFile($filePath, $relativePath);
                            $total_item++;
                        }
                    }

                    $zip->close();

                    $dataZip = [
                        'filename'      =>  trim($file_name_zip),
                        'file_path'     =>  trim($save_to) .''. trim($file_name_zip),
                        'save_path'     =>  trim($save_to),
                        'zipped_path'   =>  trim($folder_to_zip),
                        'total_item'    =>  $total_item,
                        'file_size'     =>  filesize(trim($save_to) .''. trim($file_name_zip))
                    ];

                    return $dataZip;

                } else {
                    return false;
                }
                
            } elseif($zipAction !== null && $zipAction == 'unzip' && 
                is_dir(trim($saveTo)) && trim($fileNameZip) !== '' && 
                is_dir(trim($dirForZip))
            ) {

                // statement
                return false;
                
            }else{
                return false;
            }
            
        }

        public function listDir($setDir = null){

            $setDir = $setDir;
            $listItemDir = [];
            if(is_dir(trim($setDir)) && trim($setDir) !== ''){


                $openDir = opendir($setDir);
                while ($getdirItem = readdir($openDir)) {
                    $itemPath = $setDir . DIRECTORY_SEPARATOR . $getdirItem;
                    $itemName = $getdirItem;
                    $listItemDir[] = [
                        'item_name'     =>  $itemName,
                        'item_type'     =>  is_dir($itemPath) ? 'directory': 'file',
                        'item_path'     =>  $itemPath,
                        'item_mime'     =>  @mime_content_type($itemPath),
                        'item_time'     =>  date ("F d Y H:i:s.", @filemtime($itemPath)),
                        'item_size'     =>  @filesize($itemPath) 
                    ];
                }

                return $listItemDir;

            }else{
                return false;
            }
        }

        public function mime_icon_set($mime_type){

            //echo $mime_type;
            if(strpos(trim($mime_type), 'directory') !== false) {
                return '<i class="fa-solid fa-folder icon-ff-list"></i>';
            }elseif(strpos(trim($mime_type), 'image') !== false){
                return '<i class="fa-solid fa-image icon-ff-list"></i>';
            }elseif(strpos(trim($mime_type), 'text/plain') !== false){
                return '<i class="fa-solid fa-file-lines icon-ff-list"></i>';
            }elseif(strpos(trim($mime_type), 'text/x-php') !== false){
                return '<i class="fa-brands fa-php icon-ff-list"></i>';
            }elseif(strpos(trim($mime_type), 'text/javascript') !== false){
                return '<i class="fa-brands fa-javascript icon-ff-list"></i>';
            }elseif(strpos(trim($mime_type), 'text/css') !== false){
                return '<i class="fa-brands fa-css icon-ff-list"></i>';
            }elseif(strpos(trim($mime_type), 'text/x-py') !== false){
                return '<i class="fa-brands fa-python icon-ff-list"></i>';
            }elseif(strpos(trim($mime_type), 'zip') !== false || strpos(trim($mime_type), 'rar') !== false){
                return '<i class="fa-solid fa-file-zipper icon-ff-list"></i>';
            }
            else{
                return '<i class="fa-solid fa-file-circle-question icon-ff-list"></i>';
            }
        }

        public function pageActive(){
            if (isset($_GET['page']) && trim($_GET['page']) !== '') {
                $page = trim($_GET['page']);
                return strtolower($page);
            } else {
                return 'dashboard';
            }
            
        }

        public function streamFile($file = null){
            if (trim($file) !== '') {
                if (file_exists($file)) {
                    $getContentFile = file_get_contents($file);
                    return $getContentFile;
                } else {
                    return false;
                }
            } else {
                return false;
            }   
        }

        public function saveFile($file = null, $mode = null, $fileContent = null){
            if (trim($file) !== '' && trim($mode) !== '') {
                if (file_exists($file)) {
                    $fileOpen = fopen($file, $mode);
                    fwrite($fileOpen, $fileContent);
                    fclose($fileOpen);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public function createFile($file = null, $mode = null, $fileContent = null){
            if (trim($file) !== '' && trim($mode) !== '') {
                if (!file_exists($file)) {
                    $fileOpen = fopen($file, $mode);
                    if ($fileOpen) {
                        fwrite($fileOpen, $fileContent);
                        fclose($fileOpen);
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
            
        }
    }

    // ins system
    $webTools = new webTools();
    
    function dashboardPage($webTools = false) {

        // START DASHBOARD

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB TOOLS</title>
    <?= $webTools->loadMetaLink(); ?>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/rainbow-code@2.1.7/themes/css/paraiso-dark.css" integrity="sha256-tIDos/4CvlyYUH34vy98sohTuDvmUTlu2ZsZMD4x9EU=" crossorigin="anonymous"> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/rainbow-code@2.1.7/themes/css/all-hallows-eve.css" integrity="sha256-dI4/9VdeYvon9cJ6+EyeVpJraFk1ucyDKI3pPx2CkOI=" crossorigin="anonymous"> -->
    
    <?php if($webTools->pageActive() == 'text-editor'){ ?>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/enlighterjs@3.4.0/dist/enlighterjs.dracula.min.css" integrity="sha256-x08qZTgWks4/JUCMKhc1k8HSSt6R+cmaHy8sGT0/g7c=" crossorigin="anonymous"> -->
    <?php } else {
        // something
    }
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dosis:wght@200;300;400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css?family=Bungee:wght@100;200;300;400;500;600;700;800');

        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #1B1E2B;
            --secondary-color: #292D3E;
            --body-text-header-font-size: 16px;
            --body-text-content-font-size: 14px;
        }
        
        body {
            font-family: "Dosis", cursive;
            background: var(--secondary-color);
            font-size: var(--body-text-content-font-size);
        }

        h1 { font-size: 36px; font-weight: 600; }
        h2 { font-size: 32px; font-weight: 600; }
        h3 { font-size: 24px; font-weight: 600; }
        h4 { font-size: 18px; font-weight: 600; }
        h5 { font-size: 16px; font-weight: 600; }
        h6 { font-size: 14px; font-weight: 600; }

        .bg-black {
            background-color: black;
        }

        .separator-prim {
            height: 1px;
            display: block;
            background: var(--primary-color);
            margin-top: 8px;
            margin-bottom: 8px;;
        }

        .separator-sec {
            height: 1px;
            display: block;
            background: var(--secondary-color);
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .bg-prim {
            background: var(--primary-color);
        }

        .bg-sec {
            background: var(--secondary-color) !important;
        }

        .text-second-color {
            color: var(--secondary-color);
        }

        .height-full {
            min-height: 100vh; 
        }

        .user-logo {
            border-radius: 50%;
            padding: 5px;
            height: 60px;
            width: 60px;
            border: 3px solid #292D3E;
            /* filter: contrast(200%); */
            margin-bottom: 20px;
        }

        .user-name {
            font-weight: 600;
            font-size: var(--body-text-header-font-size);
        }

        .sidebar-left {
            padding-top: 50px;
            padding-bottom: 50px;
            user-select: none;
        }

        .body-content {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .text-small {
            font-size: var(--body-text-header-font-size);
            font-weight: 400;
        }

        .overflow-active {
            overflow: auto;
            overflow-x: hidden;
        }

        .text-label {
            font-weight: 500;
            color: #fff;
            font-size: var(--body-text-header-font-size);
        }

        .text-green {
            color: #ff0f;
        }

        /* scrollbar */
        /* width */
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: var(--secondary-color); 
        }
        
        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: black; 
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555; 
        }

        .progress {
            font-size: 11px;
            height: 10px;
        }

        .progress-bar {
            transition: ease-out .8s;
            width: 0%;
        }

        .user-selected-active {
            user-select: text !important;
        }

        .elem-content {
            margin: 10px;
            background-color: var(--primary-color);
        }

        /* tools */
        .tools-item {
            margin: 10px;
            background-color: var(--primary-color);
        }

        .tools-item:hover {
            box-shadow: 0px 0px 10px var(--primary-color);
        }

        .tools-icon {
            font-size: 32px;
            padding: 10px;
            background-color: var(--secondary-color);
        }

        .tools-name {
            font-size: var(--body-text-header-font-size);
        }

        .tools-link {
            text-decoration: none !important;
        }

        .box-terminal {
            height: 400px;
        }
        .terminal-log {
            font-size: 18px !important;
        }

        .bg-transparent {
            background-color: rgba(0, 0, 0, .0) !important;
        }

        .form-control {
            background: rgba(0, 0, 0, .0) !important;
            border-color: #6c757d !important;
        }

        .scroll-active {
            overflow: auto;
        }

        .badge-custom-notice-term {
            border-radius: 0px;
            font-size: 13px;
        }

        .fixed-full-height {
            min-height: 100vh;
            max-height: 100vh;
        }

        .set-scan-file {
            cursor: pointer;
        }

        .text-danger {
            color: red;
        }
        .icon-ff-list {
            padding: 6px;
        }

        .badge{
            cursor: pointer;
            user-select: none !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .height-max {
            max-height: 300px;
        }

        .full-width {
            min-width: 100%;
        } 

        #textEditor {
            background: var(--primary-color);
            color: white;
            width: 100%;
            border: 2px solid var(--secondary-color);
            font-size: var(--body-text-header-font-size);
        }

        .min-max-height {
            max-height: 150px !important;
        }

    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row height-full">
            <!-- sidebar content -->
            <div class="col-md-3 bg-prim text-second-color sidebar-left height-full overflow-active">
                <!-- profile content login -->
                <div class="row mx-auto my-auto">
                    <div class="col-md-12 mx-auto my-auto">
                        <center>
                            <img src="<?= $webTools->userImage; ?>" alt="" class="user-logo">
                            <br>
                            <span>
                                <div class="text-small text-white">WELCOME!</div>
                                <small class="user-name text-white"><?= $webTools->userName ?></small>
                            </span>
                            <br><br>
                            <a href="<?= $webTools->baseLink ?>?logout=true" class="text-white btn">
                                Logout <i class="fa-solid fa-arrow-right-from-bracket"></i>                 
                            </a>
                        </center>
                    </div>
                </div>

                <div class="separator-sec"></div>
                <div class="row mx-auto my-auto">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-label">DISK INFO</div>
                                <div class="mt-1 text-white">Total Space: <?= $webTools->getDiskSize($webTools->getDir()); ?> MB</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" style="width: 100%;"  aria-valuemin="0" aria-valuemax="100">100%</div>
                                </div>
                                <div class="mt-1 text-white">Free Space: <?= $webTools->getFreeDisk($webTools->getDir()); ?> MB</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progress-bar-striped bg-success" role="progressbar" style="width: <?= $webTools->getFreeDisk($webTools->getDir(), 'percent'); ?>%;"  aria-valuemin="0" aria-valuemax="100"><?= $webTools->getFreeDisk($webTools->getDir(), 'percent'); ?>%</div>
                                </div>
                                <div class="mt-1 text-white">Used Space: <?= $webTools->diskUsedSpace($webTools->getDir()); ?> MB</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progress-bar-striped bg-danger" role="progressbar" style="width: <?= $webTools->diskUsedSpace($webTools->getDir(), 'percent'); ?>%;"  aria-valuemin="0" aria-valuemax="100"><?= $webTools->diskUsedSpace($webTools->getDir(), 'percent'); ?>%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator-sec"></div>
                <div class="row mx-auto my-auto">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-label">RAM INFO</div>
                                <div class="mt-1 text-white">Total Space: <?= $webTools->ramInfo(); ?> MB</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" style="width: 100%;"  aria-valuemin="0" aria-valuemax="100">100%</div>
                                </div>
                                <div class="mt-1 text-white">Free Space: <?= $webTools->ramInfo('free'); ?> MB</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progress-bar-striped bg-success" role="progressbar" style="width: <?= $webTools->ramInfo('free', 'percent'); ?>%;"  aria-valuemin="0" aria-valuemax="100"><?= $webTools->ramInfo('free', 'percent'); ?>%</div>
                                </div>
                                <div class="mt-1 text-white">Used Space: <?= $webTools->ramInfo('used'); ?> MB</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progress-bar-striped bg-danger" role="progressbar" style="width: <?= $webTools->ramInfo('used', 'percent'); ?>%;"  aria-valuemin="0" aria-valuemax="100"><?= $webTools->ramInfo('used', 'percent'); ?>%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator-sec"></div>
                <div class="row mx-auto my-auto">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-label">ROOT DIR</div>
                                <div>
                                <?php 
                                    if ($webTools->chekRoot()) {
                                        echo "<div class='text-green'>Writeable</div>";
                                    } else {
                                        echo "<div class='text-danger'>No Writeable</div>";
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-label">CURRENT DIR</div>
                                <div>
                                <?php 
                                    if ($webTools->cekdir()) {
                                        echo "<div class='text-green'>Writeable</div>";
                                    } else {
                                        echo "<div class='text-danger'>No Writeable</div>";
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator-sec"></div>
                <div class="row mx-auto my-auto user-selected-active">
                    <div class="col-md-12">
                        <div class="text-label">INFO</div>
                        <div class="mt-1 text-white"><b>Date:</b> <?= date('H:i, d-m-Y'); ?></div>
                        <div class="mt-1 text-white"><b>User:</b> <?= @get_current_user(); ?> [UID <?= @getmyuid(); ?>]</div>
                        <div class="mt-1 text-white"><b>Server:</b> <?= $_SERVER['SERVER_SOFTWARE']; ?></div>
                        <div class="mt-1 text-white"><b>System:</b> <?= @php_uname(); ?> | phpversion</div>
                        <div class="mt-1 text-white"><b>Current Dir:</b> <?= @getcwd(); ?></div>
                        <div class="mt-1 text-white"><b>Exec Info:</b> <?= $webTools->is_shell_exec_available() ? '<span class="badge badge-success bg-success">Enabled</span>' : '<span class="badge badge-success bg-success">Disabled</span>'; ?></div>
                        <div class="mt-1 text-white"><b>PHP Info:</b> 
                            <a href="<?= $webTools->baseLink; ?>?page=phpinfo">Open</a>
                        </div>
                        <div class="mt-1 text-white"><b>PHP Ini Path:</b> <?= php_ini_loaded_file(); ?> | <a href="<?= $webTools->baseLink; ?>?page=download&file=<?= php_ini_loaded_file(); ?>" target="_blank">Download</a>
                        </div>
                    </div>
                </div>

                <div class="separator-sec mt-5"></div>
                <div class="row mx-auto my-auto mt-5 pt-2">
                    <div class="col-md-12">
                        <div class="text-white">&copy;Copyright <?= date('Y') ?>. By <a href="https://github.com/fordevelopertools" target="_blank" class="text-white">ForDeveloperTools</a>.</div>
                    </div>
                </div>
            </div>
            <!-- end sidebar content -->

            <!-- body content -->
            <div class="col-md-9 body-content">
                <?php if($webTools->pageActive() == 'dashboard'){ ?>
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?=  $webTools->baseLink; ?>?page=terminal" class="tools-link">
                            <div class="card tools-item bg-prim">
                                <div class="card-body text-white">
                                    <div class="row">
                                        <div class="col-3 my-auto mx-auto">
                                            <i class="fa-solid fa-terminal text-white tools-icon"></i>
                                        </div>
                                        <div class="col-9 my-auto mx-auto">
                                            <div class="tools-name">Terminal</div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?=  $webTools->baseLink; ?>?page=filescan" class="tools-link">
                            <div class="card tools-item bg-prim">
                                <div class="card-body text-white">
                                    <div class="row">
                                        <div class="col-3 my-auto mx-auto">
                                            <i class="fa-solid fa-magnifying-glass text-white tools-icon"></i>
                                        </div>
                                        <div class="col-9 my-auto mx-auto">
                                            <div class="tools-name">File Scan</div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?=  $webTools->baseLink; ?>?page=malware-perm-scan" class="tools-link">
                            <div class="card tools-item bg-prim">
                                <div class="card-body text-white">
                                    <div class="row">
                                        <div class="col-3 my-auto mx-auto">
                                            <i class="fa-solid fa-shield-virus text-white tools-icon"></i>
                                        </div>
                                        <div class="col-9 my-auto mx-auto">
                                            <div class="tools-name">Scan Malware & Permission (Coming Soon)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <a href="<?=  $webTools->baseLink; ?>?page=file-manager" class="tools-link">
                            <div class="card tools-item bg-prim">
                                <div class="card-body text-white">
                                    <div class="row">
                                        <div class="col-3 my-auto mx-auto">
                                            <i class="fa-solid fa-folder-closed text-white tools-icon"></i>
                                        </div>
                                        <div class="col-9 my-auto mx-auto">
                                            <div class="tools-name">File Manager</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?=  $webTools->baseLink; ?>?page=zip-manager" class="tools-link">
                            <div class="card tools-item bg-prim">
                                <div class="card-body text-white">
                                    <div class="row">
                                        <div class="col-3 my-auto mx-auto">
                                            <i class="fa-solid fa-heading text-white tools-icon"></i>
                                        </div>
                                        <div class="col-9 my-auto mx-auto">
                                            <div class="tools-name">Find Text (Coming Soon)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?=  $webTools->baseLink; ?>?page=database-manager" class="tools-link">
                            <div class="card tools-item bg-prim">
                                <div class="card-body text-white">
                                    <div class="row">
                                        <div class="col-3 my-auto mx-auto">
                                            <i class="fa-solid fa-database text-white tools-icon"></i>
                                        </div>
                                        <div class="col-9 my-auto mx-auto">
                                            <div class="tools-name">Database Manager (Coming Soon)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <a href="<?=  $webTools->baseLink; ?>?page=text-editor" class="tools-link">
                            <div class="card tools-item bg-prim">
                                <div class="card-body text-white">
                                    <div class="row">
                                        <div class="col-3 my-auto mx-auto">
                                            <i class="fa-solid fa-file-pen text-white tools-icon"></i>
                                        </div>
                                        <div class="col-9 my-auto mx-auto">
                                            <div class="tools-name">Text Editor</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?=  $webTools->baseLink; ?>?page=phpinfo" class="tools-link">
                            <div class="card tools-item bg-prim">
                                <div class="card-body text-white">
                                    <div class="row">
                                        <div class="col-3 my-auto mx-auto">
                                            <i class="fa-brands fa-php text-white tools-icon"></i>
                                        </div>
                                        <div class="col-9 my-auto mx-auto">
                                            <div class="tools-name">PHP Info</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>


                <?php } elseif($webTools->pageActive() == 'phpinfo'){ ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-header">
                                <h4>PHP INFO</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-body">
                                
                                <?php 

                                    $webTools->addCssGlobal('
                                        h1, h2 {color: #fff !important;} 
                                        table, tr, td, th {background-color: var(--primary-color) !important; color: white !important;
                                        box-shadow: 0px 0px 0px 0px transparent !important; font-size: var(--body-text-content-font-size) !important; padding: 8px !important; 
                                        }
                                        th { background-color: black !important;}
                                    ');
                                    @phpinfo(); 
                                    
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } elseif($webTools->pageActive() == 'terminal'){ ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-header">
                                <h4>TERMINAL</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div>
                                            <strong><?= $webTools->is_shell_exec_available() ? 'ACTIVE': '<div class="text-danger">DISABLED BY SYSTEM</div>'; ?></strong>
                                            <div>
                                                <?= $_SERVER['COMSPEC']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" id="clearLogTerminal" onclick="clearLogTerminal('#terminal-log');" class="btn bg-primary text-white">
                                            Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body  box-terminal scroll-active">
                                <div id="terminal-log"></div>
                            </div>
                            <div class="card-footer">
                                <form id="terminal-input" action="" method="post">
                                    <div class="input-group mb-3 bg-transparent">

                                        <input type="text" hidden="hidden" class="form-control d-none" name="info_post" id="info_post" value="<?= @get_current_user(); ?>-<?= @getcwd(); ?>~" class="bg-transparent text-white" style="color: white !important;" required/>

                                        <input type="text" class="form-control" name="command" id="command" placeholder="<?= @get_current_user(); ?>-<?= @getcwd(); ?>~" class="bg-transparent text-white" style="color: white !important;" required/>
                                        <div class="input-group-append">
                                            <button id="submitCommand" class="btn btn-outline-secondary bg-transparent" type="submit">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } elseif($webTools->pageActive() == 'filescan'){ ?>

                    <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-header">
                                <h4>FILE SCAN</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-header">
                                
                                <div class="row">
                                    <div class="col-md-9">

                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" id="clearLogTerminal" onclick="clearLogFileScan('#file-scan-log');" class="btn bg-primary text-white">
                                            Clear
                                        </button>
                                    </div>
                                </div>

                                <div class="separator-sec"></div>

                                <form id="file-scan-input" action="" method="post">
                                    <div class="input-group mb-3 bg-transparent">
                                        <input type="text" class="form-control" name="scan_dir" id="scan_dir" placeholder="Directory Location..." value="<?= $webTools->dirLoc; ?>" class="bg-transparent text-white" style="color: white !important;" required/>
                                        <input type="text" class="form-control" name="scan_payload" id="scan_payload" placeholder="example.php (use ';' for multiple)" class="bg-transparent text-white" style="color: white !important;" />
                                        <select class="form-control" name="scan_for" id="scan_for" style="color: grey;" required/>
                                            <option selected disabled/>Scan For...</option>
                                            <option value="folder">Directory</option>
                                            <option value="file">File</option>
                                            <option value="ff">Both</option>
                                        </select>
                                            <div class="input-group-append">
                                            <button id="submitFileScan" class="btn btn-outline-secondary bg-transparent" type="submit">
                                                <i class="fa-solid fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body box-file-scan fixed-full-height scroll-active">
                                <div id="file-scan-log"></div>
                            </div>
                            <div class="card-footer">
                                
                            </div>
                        </div>
                    </div>
                </div>


                <?php } elseif($webTools->pageActive() == 'file-manager'){ ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-header">
                                <h4>FILE MANAGER</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-header">
                                
                                <div class="row">
                                    <div class="col-md-9">

                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" id="clearFileMgr" onclick="removeLogFileMgrAct('#file-mgr-log-act');" class="btn bg-primary text-white">
                                            Clear Log Action
                                        </button>
                                        <button type="button" id="clearFileMgr" onclick="setLoadFileMgr();" class="btn bg-primary text-white">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="separator-sec"></div>

                                <form id="file-mgr-input" action="" method="post">
                                    <div class="input-group mb-3 bg-transparent">
                                        <input type="text" class="form-control" name="scan_dir_mgr" id="scan_dir_mgr" placeholder="Directory Location..." value="<?= $webTools->dirLoc; ?>" class="bg-transparent text-white" style="color: white !important;" required/>
                                        <div class="input-group-append">
                                            <button id="submitFileMgr" class="btn btn-outline-secondary bg-transparent" type="submit">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body box-file-mgr-act min-max-max scroll-active">
                                <div id="file-mgr-log-act">No Action.</div>
                                <div class="separator-sec"></div>
                            </div>
                            <div class="card-footer fixed-full-height scroll-active box-file-mgr">
                                <div id="file-mgr-log"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } elseif($webTools->pageActive() == 'text-editor'){ ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-header">
                                <h4>TEXT EDITOR</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card elem-content bg-prim text-white">
                            <div class="card-header">
                                
                                <div class="row">
                                    <div class="col-md-3">

                                    </div>
                                    <div class="col-md-9 text-right">
                                        <button type="button" id="refreshTextEditor" onclick="autoStartTextEditor();" class="btn bg-primary text-white">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                        </button>
                                        <button type="button" id="clearTextEditor" onclick="removeLogTextEditor('#text-editor-log');" class="btn bg-primary text-white">
                                            Clear Log Action
                                        </button>
                                        <button type="button" id="saveTextEditor" onclick="saveTextEditor();" class="btn bg-primary text-white">
                                            <i class="fa-solid fa-floppy-disk"></i> Save
                                        </button>
                                    </div>
                                </div>

                                <div class="separator-sec"></div>

                                <form id="text-editor-input" action="" method="post">
                                    <div class="input-group mb-3 bg-transparent">
                                        <input type="text" class="form-control" name="file_loc" id="file_loc" placeholder="File Location..." value="<?= isset($_GET['file']) && trim($_GET['file']) !== '' ? trim($_GET['file']): ''; ?>" class="bg-transparent text-white" style="color: white !important;" required/>
                                        <div class="input-group-append">
                                            <button id="submitTextEditor" class="btn btn-outline-secondary bg-transparent" type="submit">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body box-text-editor min-max-height scroll-active">
                                <div id="text-editor-log">No Action.</div>
                                <div class="separator-sec"></div>
                            </div>
                            <div class="card-footer">
                                <textarea name="" id="textEditor"class="fixed-full-height scroll-active"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } else{ /*something*/ } ?>
            </div>
            <!-- end body content -->
        </div>
                                    
        <!-- reload -->
        <?= $webTools->goHome(); ?>
    </div>

<?php if($webTools->pageActive() == 'text-editor'){ ?>

    <!-- <script src="https://cdn.jsdelivr.net/npm/enlighterjs@3.4.0/dist/enlighterjs.min.js" integrity="sha256-uiBN8K9ataH6h7YZDL0Bx/x4UsS5I4n20nejkGvAY1k=" crossorigin="anonymous"></script> -->
    <script>
        // - highlight all pre + code tags (CSS3 selectors)
        // - use javascript as default language
        // - use theme "enlighter" as default theme
        // - replace tabs with 2 spaces
        // EnlighterJS.init('textarea#textEditor', 'code', {
        //         language : 'javascript',
        //         theme: 'dracula',
        //         indent : 2
        // });
    </script>

<?php } else {
    // something
} ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    function copy_text(containerid) {
        var range = document.createRange();
        range.selectNode(containerid); //changed here
        window.getSelection().removeAllRanges(); 
        window.getSelection().addRange(range); 
        document.execCommand("copy");
        window.getSelection().removeAllRanges();
        alert('copied.');
    }

    // function copy_text(containerid) {
    //     if (document.selection) {
    //         var range = document.body.createTextRange();
    //         range.moveToElementText(document.getElementById(containerid));
    //         range.select().createTextRange();
    //         document.execCommand("copy");
    //     } else if (window.getSelection) {
    //         var range = document.createRange();
    //         range.selectNode(document.getElementById(containerid));
    //         window.getSelection().addRange(range);
    //         document.execCommand("copy");
    //         alert("Text has been copied.");
    //     }
    // }

    function removeElem(elem = null){
        if (elem == null) {
            return null;
        } else {
            $(elem).remove();
        }
    }

    function removeVal(elemID =null){
        if (elemID == null) {
            return null;
        } else {
            $(elemID).val('');
        }
    }

    function clearLogTerminal(elemId = null){
        removeElem(elemId);
        $('.box-terminal').html('<div id="terminal-log"></div>');
    }

    $("#terminal-input").submit( function () {
            
        var command = $('#command').val(); 
        var infoPost = $('#info_post').val(); 

        
        $('#submitCommand').attr("disabled", true);

        $('#terminal-log').prepend('<div class="box-msg"><div class="loadingLogTerm"><img src="<?= $webTools->loadImage ?>" width="20px" height="20px" /> Loading...</div></div>');

        $.ajax({
            type: 'POST',
            url: "<?= $webTools->baseLink; ?>?page=terminal-execute",
            dataType: 'html',
            cache: false, 
            data: {
                command: command,
                info_post: infoPost
            },
            success: function(data){
                removeElem('.loadingLogTerm');
                removeVal('#command');
                $('#submitCommand').attr("disabled", false);
                $('#terminal-log').prepend('<div class="box-msg">'+ data +'</div>'); 

                            
            },
            error: function (e) {
                removeElem('.loadingLogTerm');
                $('#submitCommand').attr("disabled", false);
                $('#terminal-log').prepend('<div class="box-msg">Failed. Error Message: '+ e +'</div>'); 
            }
        });

        return false;   
    });

    function clearLogFileScan(elemId = null){
        removeElem(elemId);
        $('.box-file-scan').html('<div id="file-scan-log"></div>');
    }

    function setValueTo(elemID = null, val = null){
        $(elemID).val(atob(val));
    }

    $("#file-scan-input").submit( function () {
            
        var scanDir = $('#scan_dir').val(); 
        var scanFor = $('#scan_for').val(); 
        var scanPayload = $('#scan_payload').val(); 
        

        
        $('#submitFileScan').attr("disabled", true);

        $('#file-scan-log').prepend('<div class="box-msg d-block"><center><div class="loadingLogFileScan"><img src="<?= $webTools->loadImage ?>" width="20px" height="20px" /> Loading...</div></center></div>');

        $.ajax({
            type: 'POST',
            url: "<?= $webTools->baseLink; ?>?page=scanning-file",
            dataType: 'html',
            cache: false, 
            data: {
                scan_dir: scanDir,
                scan_for: scanFor,
                scan_payload: scanPayload
            },
            success: function(data){
                removeElem('.loadingLogFileScan');
                removeVal('#command');
                $('#submitFileScan').attr("disabled", false);
                $('#file-scan-log').prepend('<div class="box-msg">'+ data +'</div>'); 
          
            },
            error: function (e) {
                removeElem('.loadingLogTerm');
                $('#submitFileScan').attr("disabled", false);
                $('#file-scan-log').prepend('<div class="box-msg">Failed. Error Message: '+ e +'</div>'); 
            }
        });

        return false;   
    });

    // for action restricted access
    <?php if($webTools->pageActive() == 'file-manager'){ ?>

    function removeLogFileMgrAct(elemId = null){
        removeElem(elemId);
        $('.box-file-mgr-act').prepend('<div id="file-mgr-log-act">No Action.</div>');
    }

    function removeLogFileMgr(elemId = null){
        removeElem(elemId);
        $('.box-file-mgr').prepend('<div id="file-mgr-log"></div>');
    }

    function setLoadFileMgr(){
        var scanDirMgr = $('#scan_dir_mgr').val();
        
        $('#submitFileMgr').attr("disabled", true);

        $('#file-mgr-log').prepend('<div class="box-msg d-block"><center><div class="loadingLogFileMgr"><img src="<?= $webTools->loadImage ?>" width="20px" height="20px" /> Loading...</div></center></div>');

        $.ajax({
            type: 'POST',
            url: "<?= $webTools->baseLink; ?>?page=file-manager-load",
            dataType: 'html',
            cache: false, 
            data: {
                scan_dir_mgr: scanDirMgr
            },
            success: function(data){
                removeLogFileMgr('#file-mgr-log');
                removeElem('.loadingLogFileMgr');
                removeVal('#command');
                $('#submitFileMgr').attr("disabled", false);
                $('#file-mgr-log').prepend('<div class="box-msg">'+ data +'</div>'); 
            
            },
            error: function (e) {
                removeLogFileMgr('#file-mgr-log');
                removeElem('.loadingLogFileMgr');
                $('#submitFileMgr').attr("disabled", false);
                $('#file-mgr-log').prepend('<div class="box-msg">Failed. Error Message: '+ e +'</div>'); 
            }
        });
    }

    function loadMgrByItem(dataSet = null){
        setValueTo('#scan_dir_mgr', dataSet);
        setLoadFileMgr();
    }

    function zip_file_mgr(ListMgrDel = null, delItem = null){

        delItem = atob(delItem);
        $('#file-mgr-log-act').prepend('<div class="box-msg d-block"><center><div class="loadingLogFileMgrAct"><img src="<?= $webTools->loadImage ?>" width="20px" height="20px" /> Zipping ['+ delItem +'], Loading...</div></center></div>');

        $.ajax({
            type: 'POST',
            url: "<?= $webTools->baseLink; ?>?page=zipper",
            dataType: 'html',
            cache: false, 
            data: {
                delete_item: delItem
            },
            success: function(data){
                //removeLogFileMgr('#file-mgr-log-act');
                removeElem('.loadingLogFileMgrAct');
                $('#submitFileMgrAct').attr("disabled", false);
                $('#file-mgr-log-act').prepend('<div class="box-msg">'+ data +'</div>'); 
                setLoadFileMgr();
            
            },
            error: function (e) {
                //removeLogFileMgr('#file-mgr-log-act');
                removeElem('.loadingLogFileMgrAct');
                $('#submitFileMgrAct').attr("disabled", false);
                $('#file-mgr-log-act').prepend('<div class="box-msg">Failed. Error Message: '+ e +'</div>'); 
                setLoadFileMgr();
            }
        });

    }

    function delete_file_mgr(ListMgrDel = null, delItem = null){
        var confirmDel = confirm('are you sure you want to delete this item ['+atob(delItem)+'] ? If true, the item will be permanently deleted.');
        if(confirmDel){

            delItem = atob(delItem);
            $('#file-mgr-log-act').prepend('<div class="box-msg d-block"><center><div class="loadingLogFileMgrAct"><img src="<?= $webTools->loadImage ?>" width="20px" height="20px" /> Deleting ['+ delItem +'], Loading...</div></center></div>');

            $.ajax({
                type: 'POST',
                url: "<?= $webTools->baseLink; ?>?page=ff-delete",
                dataType: 'html',
                cache: false, 
                data: {
                    delete_item: delItem
                },
                success: function(data){
                    //removeLogFileMgr('#file-mgr-log-act');
                    removeElem('.loadingLogFileMgrAct');
                    $('#submitFileMgrAct').attr("disabled", false);
                    $('#file-mgr-log-act').prepend('<div class="box-msg">'+ data +'</div>'); 
                    setLoadFileMgr();
                
                },
                error: function (e) {
                    //removeLogFileMgr('#file-mgr-log-act');
                    removeElem('.loadingLogFileMgrAct');
                    $('#submitFileMgrAct').attr("disabled", false);
                    $('#file-mgr-log-act').prepend('<div class="box-msg">Failed. Error Message: '+ e +'</div>'); 
                    setLoadFileMgr();
                }
            });

        }else{
            // something statement
            return false;
        }

    }
    
    $( document ).ready(function() {

        function autoStartFileMgr(){

            var scan_dir_mgr = $('#scan_dir_mgr').val();

            $('#file-mgr-log').prepend('<div class="box-msg d-block"><center><div class="loadingLogFileMgr"><img src="<?= $webTools->loadImage ?>" width="20px" height="20px" /> Loading...</div></center></div>');

            $.ajax({
                type: 'POST',
                url: "<?= $webTools->baseLink; ?>?page=file-manager-load",
                dataType: 'html',
                cache: false, 
                data: {
                    scan_dir_mgr: scan_dir_mgr
                },
                success: function(data){
                    removeElem('.loadingLogFileMgr');
                    $('#file-mgr-log').prepend('<div class="box-msg">'+ data +'</div>'); 
            
                },
                error: function (e) {
                    removeElem('.loadingLogFileMgr');
                    $('#file-mgr-log').prepend('<div class="box-msg">Failed. Error Message: '+ e +'</div>'); 
                }
            });
        }

        autoStartFileMgr();

        $("#file-mgr-input").submit( function () {
            setLoadFileMgr();
            return false;   
        });

        

    });

    <?php } elseif($webTools->pageActive() == 'text-editor'){ ?>

    // setLoadTextEditor
    // textEditor
    function removeLogTextEditor(elemId = null){
        removeElem(elemId);
        $('.box-text-editor').prepend('<div id="text-editor-log">No Action.</div>');
    }

    function autoStartTextEditor(){

        var textEditorArea = $('#textEditor').val();
        var fileLoc = $('#file_loc').val();

        $('#submitTextEditor').attr("disabled", true);
        $('#refreshTextEditor').attr("disabled", true);
        $('#saveTextEditor').attr("disabled", true);

        $('#text-editor-log').prepend('<div class="box-msg d-block"><center><div class="loadingLogTextEditor"><img src="<?= $webTools->loadImage ?>" width="20px" height="20px" /> Loading...</div></center></div>');

        $.ajax({
            type: 'POST',
            url: "<?= $webTools->baseLink; ?>?page=text-editor-act&action=open",
            dataType: 'html',
            cache: false, 
            data: {
                file_loc: fileLoc,
                text_editor: textEditorArea
            },
            success: function(data){
                $('#submitTextEditor').attr("disabled", false);
                $('#refreshTextEditor').attr("disabled", false);
                $('#saveTextEditor').attr("disabled", false);
                var dataSet = JSON.parse(data);
                removeElem('.loadingLogTextEditor');
                $('#textEditor').val(""+dataSet[0].content+"");
                $('#text-editor-log').prepend('<div class="box-msg">'+ dataSet[0].action +'</div>'); 
        
            },
            error: function (e) {
                $('#submitTextEditor').attr("disabled", false);
                $('#refreshTextEditor').attr("disabled", false);
                $('#saveTextEditor').attr("disabled", false);
                removeElem('.loadingLogTextEditor');
                $('#text-editor-log').prepend('<div class="box-msg">Failed. Error Message: '+ e +'</div>'); 
            }
        });
    }

    // text-editor-input
    // submitTextEditor

    function saveTextEditor(){
        $('#submitTextEditor').attr("disabled", true);
        $('#refreshTextEditor').attr("disabled", true);
        $('#saveTextEditor').attr("disabled", true);

        var textEditorArea = $('#textEditor').val();
        var fileLoc = $('#file_loc').val();

        $('#text-editor-log').prepend('<div class="box-msg d-block"><center><div class="loadingLogTextEditor"><img src="<?= $webTools->loadImage ?>" width="20px" height="20px" /> Loading...</div></center></div>');

        $.ajax({
            type: 'POST',
            url: "<?= $webTools->baseLink; ?>?page=text-editor-act&action=save",
            dataType: 'html',
            cache: false, 
            data: {
                file_loc: fileLoc,
                text_editor: textEditorArea
            },
            success: function(data){
                $('#submitTextEditor').attr("disabled", false);
                $('#refreshTextEditor').attr("disabled", false);
                $('#saveTextEditor').attr("disabled", false);
                var dataSet = JSON.parse(data);
                removeElem('.loadingLogTextEditor');
                //$('#textEditor').val(""+dataSet[0].content+"");
                $('#text-editor-log').prepend('<div class="box-msg">'+ dataSet[0].action +'</div>'); 
            },
            error: function (e) {
                $('#submitTextEditor').attr("disabled", false);
                $('#refreshTextEditor').attr("disabled", false);
                $('#saveTextEditor').attr("disabled", false);
                removeElem('.loadingLogTextEditor');
                $('#text-editor-log').prepend('<div class="box-msg">Failed. Error Message: '+ e +'</div>'); 
            }
        });

        return false;   
    }

    $("#text-editor-input").submit( function () {
        
        $('#submitTextEditor').attr("disabled", true);
        $('#refreshTextEditor').attr("disabled", true);
        $('#saveTextEditor').attr("disabled", true);

        var textEditorArea = $('#textEditor').val();
        var fileLoc = $('#file_loc').val();

        $('#text-editor-log').prepend('<div class="box-msg d-block"><center><div class="loadingLogTextEditor"><img src="<?= $webTools->loadImage ?>" width="20px" height="20px" /> Loading...</div></center></div>');

        $.ajax({
            type: 'POST',
            url: "<?= $webTools->baseLink; ?>?page=text-editor-act&action=open",
            dataType: 'html',
            cache: false, 
            data: {
                file_loc: fileLoc,
                text_editor: textEditorArea
            },
            success: function(data){
                $('#submitTextEditor').attr("disabled", false);
                $('#refreshTextEditor').attr("disabled", false);
                $('#saveTextEditor').attr("disabled", false);
                var dataSet = JSON.parse(data);
                removeElem('.loadingLogTextEditor');
                $('#textEditor').val(""+dataSet[0].content+"");
                $('#text-editor-log').prepend('<div class="box-msg">'+ dataSet[0].action +'</div>'); 
            },
            error: function (e) {
                $('#submitTextEditor').attr("disabled", false);
                $('#refreshTextEditor').attr("disabled", false);
                $('#saveTextEditor').attr("disabled", false);
                removeElem('.loadingLogTextEditor');
                $('#text-editor-log').prepend('<div class="box-msg">Failed. Error Message: '+ e +'</div>'); 
            }
        });

        return false;   
    });

    $( document ).ready(function() {

        autoStartTextEditor();

        $("#text-editor-submit").submit( function () {
            //setLoadFileMgr();
            return false;   
        });
    });

    <?php } else {
        // statement
    } ?>

</script>
</body>
</html>
<?php 
    // END OF DASHOBARD
    }

    // START LOGIN PAGE
    function loginPage($webTools = false){
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB TOOLS</title>
    <?= $webTools->loadMetaLink(); ?>
    <title>LOGIN PAGE</title>

    <?= $webTools->addCssGlobal(); ?>

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Dosis:wght@200;300;400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css?family=Bungee:wght@100;200;300;400;500;600;700;800');

        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        body {
            font-family: "Dosis", cursive;
            background: #292D3E;
        }

        .set-full-height {
            min-height: 100vh;
        }

        .user-logo {
            border-radius: 50%;
            height: 80px;
            width: 80px;
            border: 3px solid #292D3E;
            /* filter: contrast(200%); */
            margin-bottom: 20px;
            padding: 5px;
        }

        .user-name {
            font-weight: 600;
        }

        .footer-text {
            font-size: 12px;
            font-weight: 600;
            display: block;
            margin-top: 20px;
            color: #6c757d;
        }

        .text-primary {
            color: #6c757d !important;
        }

        .bg-transparent {
            background-color: rgba(0, 0, 0, .0) !important;
        }

        .form-control {
            background: rgba(0, 0, 0, .0) !important;
            border-color: #6c757d !important;
        }

        /* scrollbar */
        /* width */
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #000000; 
        }
        
        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #000000; 
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555; 
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="row mx-auto my-auto set-full-height">
            <div class="col-md-4"></div>
            <div class="col-md-4 my-auto mx-auto">
                <form action="<?= $webTools->baseLink; ?>" method="post">
                    <!-- profile content login -->
                    <div class="row mx-auto my-auto">
                        <div class="col-md-12 mx-auto my-auto">
                            <center>
                                <img src="<?= $webTools->userImage; ?>" alt="" class="user-logo">
                                <br>
                                <span class="text-white"><small class="user-name">-- WELCOME <?= $webTools->userName ?> --</small></span>
                                <br><br>
                            </center>
                        </div>
                    </div>
                    <!-- body form login -->
                    <div class="input-group mb-3 bg-transparent">
                        <input type="text" class="form-control" name="password_login" placeholder="Password" class="bg-transparent" required/>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary bg-transparent" type="submit">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    <!-- footer login -->
                    <div class="row mx-auto my-auto">
                        <div class="col-md-12 mx-auto my-auto">
                            <center>
                                <span class="footer-text">&copy;Copyright <?= date('Y') ?>. By <a href="https://github.com/fordevelopertools" target="_blank" class="text-primary">ForDeveloperTools</a></span>
                            </center>
                        </div>
                    </div>

                    <!-- reload -->
                    <?= $webTools->goHome(); ?>
                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
<?php
    }

    // logout
    if($webTools->checkLogin() && isset($_GET['logout'])){
        $webTools->logout($webTools->baseLink);
    }

    // check input login
    $pass_formlogin = isset($_POST['password_login']) ? $_POST['password_login'] : null;
    if ($webTools->login_auth($pass_formlogin)) {
        
        $webTools->redirect($webTools->baseLink);

    } else {

        // check session for access features & pages restricted.
        if($webTools->checkLogin()){

            // php info feature
            $pageShow = $webTools->pageActive();
            if ($pageShow == 'download'){
                if(isset($_GET['file']) && trim($_GET['file']) !== ''){
                    $downloadFile = $webTools->download(trim($_GET['file']));
                    if ($downloadFile) {
                        // do something
                    } else {
                        echo "Download failed. File not found.";
                    }
                }else{
                    echo "Please add file to download.";
                }
            } elseif($pageShow == 'terminal-execute'){
                
                $infoPost = $_POST['info_post'];
                $command = trim($_POST['command']);
                echo "
                <div class='badge badge-warning badge-custom-notice-term'>[--$infoPost--] ". date('H:i d-m-Y') ."</div>
                <div>
                    <div class='badge badge-primary text-white badge-custom-notice-term'>===>></div>&nbsp;&nbsp;&nbsp; $command 
                </div>
                ";
                echo "<div style='border-left: 2px dotted grey; padding-left: 10px;'>";
                if ($webTools->is_shell_exec_available()) {
                    @exec($command, $output, $var);
                    foreach ($output as $key => $value) {
                        echo '<div>'. htmlentities($value) . '</div>';
                    }
                } else {
                    echo "<div><div class='badge badge-danger'>Exec not available.</div></div>";
                }
                echo "</div>";   
            }

            elseif($pageShow == 'scanning-file'){
                
                $scanFor = $_POST['scan_for'];
                $scanDir = trim($_POST['scan_dir']);
                $scanPayload = trim($_POST['scan_payload']);

                

                if ($scanPayload !== '') {
                    $scanPayloadArr = explode(';', $scanPayload);
                } else {
                    $scanPayloadArr = [];
                }

                $getScan = $webTools->fileScan($scanDir, $scanPayloadArr, $scanFor);

                if (is_array($getScan) && count($getScan) > 0) {

                    $totalItem = count($getScan);

                    echo "
                    <div class='badge badge-warning badge-custom-notice-term'>[$scanDir <=> $scanFor] ". date('H:i d-m-Y') ."</div>
                    <div>
                        <div class='badge badge-primary text-white badge-custom-notice-term'>search for>></div>&nbsp;&nbsp;&nbsp; $scanPayload 
                    </div>
                    ";

                    echo "
                        <div style='border-left: 2px dotted grey; padding-left: 10px;'>
                        <div class='row'>
                            <div class='col-md-12 p-2 m-2 text-bold'><strong>Total Item: $totalItem</strong></div>
                        </div>
                    ";
                    $nox = 1;
                    foreach ($getScan as $key => $value) {
                        echo '<div style="border-bottom: 2px dotted grey; padding-left: 10px; padding-bottom: 8px; padding-top: 8px;">'. $nox .')&nbsp;&nbsp;&nbsp;'. $value['item'];

                        if ($value['type'] == 'folder') {
                            echo '
                            <div class="row">
                                <div class="col-md-12 mt-1 mb-1"><strong>Type: '. $value['type'] .'</strong> | <span onclick=\'setValueTo("#scan_dir", "'. base64_encode($value['item']) .'");\' class="badge badge-primary text-white set-scan-file">Set Search</span></div>
                            </div>
                            ';
                        }else{
                            echo '
                            <div class="row">
                                <div class="col-md-12 mt-1 mb-1"><strong>Type: '. $value['type'] .'</strong> | <a href="'. $webTools->baseLink .'?page=download&file='. $value['item'] .'"><span class="badge badge-success text-white set-scan-file">Download</span></a></div>
                            </div>
                            ';
                        }
                        
                        echo '</div>';
                        $nox++;
                    }
                    
                    echo "</div>";
                    
                } else {
                    echo "<div><div class='badge badge-danger'>Results 0 or Failed.</div></div>";
                }
                

                // echo "
                // <div class='badge badge-warning badge-custom-notice-term'>[--$infoPost--] ". date('H:i d-m-Y') ."</div>
                // <div>
                //     <div class='badge badge-primary text-white badge-custom-notice-term'>===>></div>&nbsp;&nbsp;&nbsp; $command 
                // </div>
                // ";
                // echo "<div style='border-left: 2px dotted white; padding-left: 10px;'>";
                // if ($webTools->is_shell_exec_available()) {
                //     @exec($command, $output, $var);
                //     foreach ($output as $key => $value) {
                //         echo '<div>'. $value. '</div>';
                //     }
                // } else {
                //     echo "<div><div class='badge badge-danger'>Exec not available.</div></div>";
                // }
                // echo "</div>";
                
            }

            elseif($pageShow == 'file-manager-load'){

                $dirScanMgr = trim($_POST['scan_dir_mgr']);
                
                //listDir
                echo "<div id='". time() ."' class='set-list-file-manager'>";
                echo "
                    <div class='badge badge-warning badge-custom-notice-term'>[". date('H:i d-m-Y') ."]</div>
                ";
                $getDirScan = $webTools->listDir($dirScanMgr);
                if ($getDirScan) {
                    $xCounter = 1;
                    foreach ($getDirScan as $keyItem => $valueItem) {

                        echo '<span id="item_name_'. $xCounter .'" style="width: 0px !important; height: 0px !important; font-size: 0px;">'. $valueItem['item_path'] .'</span>';
                        
                        if ($valueItem['item_type'] == 'directory') {

                            echo '<div class="list-item-file-mgr list-item-dir" style="border-bottom: 2px dotted grey; padding-left: 10px; padding-bottom: 8px; padding-top: 8px;" id="list-item-mgr_'. $xCounter .'">
                            <div class="row">
                            <div class="col-md-6">
                            <div class="cursor-pointer" onclick="loadMgrByItem(\''. base64_encode($valueItem['item_path']) .'\');">
                            '. $webTools->mime_icon_set($valueItem['item_mime']) .'
                            '. $valueItem['item_name'];
                            
                            echo '
                            </div>
                            </div>
                            <div class="col-md-2">Directory</div>
                            <div class="col-md-2">
                            '. $webTools->filePermInfo(trim($valueItem['item_path'])) .'
                            </div>
                            <div class="col-md-2">
                            '. number_format(($valueItem['item_size'] / 1024) / 1024, 2) .'MB
                            </div>
                            </div>
                                <div class="list-item-file-mgr-act">
                            ';
                            
                            // skip dots
                            if (substr($valueItem['item_path'], -1) !== "." && substr($valueItem['item_path'], -2) !== "..") {

                                echo'<span class="badge badge-success" onclick="zip_file_mgr(\'#list-item-mgr_'. $xCounter .'\', \''. base64_encode($valueItem['item_path']) .'\');">Zip Arcive</span>
                                 | 
                                <span class="badge badge-danger" onclick="delete_file_mgr(\'#list-item-mgr_'. $xCounter .'\', \''. base64_encode($valueItem['item_path']) .'\');">Delete</span> | 
                                ';
                            }

                            echo'<span class="badge badge-info" onclick="copy_text(item_name_'. $xCounter .');">Copy Path</span>
                            </div>
                            ';

                            echo '
                            </div>
                            </div>';
                        } else {
                            echo '<div class="list-item-file-mgr list-item-file" style="border-bottom: 2px dotted grey; padding-left: 10px; padding-bottom: 8px; padding-top: 8px;" id="list-item-mgr_'. $xCounter .'">
                            <div class="row">
                            <div class="col-md-6">
                            <div class="cursor-pointer">
                            '. $webTools->mime_icon_set($valueItem['item_mime']) .'
                            '. $valueItem['item_name'];
                            
                            echo '
                            </div>
                            </div>
                            <div class="col-md-2">File</div>
                            <div class="col-md-2">
                            '. $webTools->filePermInfo(trim($valueItem['item_path'])) .'
                            </div>
                            <div class="col-md-2">
                            '. number_format(($valueItem['item_size'] / 1024) / 1024, 2) .'MB
                            </div>
                            </div>
                                <div class="list-item-file-mgr-act">
                                    <a href="'. $webTools->baseLink .'?page=download&file='. $valueItem['item_path'] .'" target="_blank">
                                        <span class="badge badge-success">Download</span>
                                    </a>
                                     | 
                                     <a href="'. $webTools->baseLink .'?page=text-editor&file='. $valueItem['item_path'] .'" target="_blank">
                                        <span class="badge badge-primary">Open</span>
                                     </a>
                                     | 
                                    <span class="badge badge-danger" onclick="delete_file_mgr(\'#list-item-mgr_'. $xCounter .'\', \''. base64_encode($valueItem['item_path']) .'\');">Delete</span>
                                    
                                    | 
                                    <a href="./'. $valueItem['item_path'] .'" target="_blank">
                                        <span class="badge badge-warning">Show</span>
                                    </a>
                                    | 
                                    <span class="badge badge-info" onclick="copy_text(item_name_'. $xCounter .');">Copy Path</span>
                                </div>
                            ';

                            echo '
                            </div>
                            </div>';
                        }

                        $xCounter++;
                    }
                }else{
                    echo "<div><div class='badge badge-danger'>Results 0 or Failed.</div></div>";
                }
                echo "</div>";
            }

            elseif($pageShow == 'ff-delete'){
                $deleteFF = trim($_POST['delete_item']);
                $deletingItem = $webTools->ff_delete($deleteFF);
                echo "<div id='". time() ."' class='set-list-file-manager'>";
                echo "
                    <div class='badge badge-warning badge-custom-notice-term'>[Delete] ". date('H:i d-m-Y') ."</div>
                ";
                if ($deletingItem) {

                    echo "<div style='border-left: 2px dotted grey; padding-left: 10px;'>Delete [$deleteFF] Success.</div>";
                    
                } else {
                    echo "<div style='color: red; border-left: 2px dotted grey; padding-left: 10px;'>Delete  [$deleteFF] Failed.</div>";
                }

                echo "</div>";
                
            }

            elseif($pageShow == 'zipper'){
            
                $zipDir = trim($_POST['delete_item']);
                $fileName = basename($zipDir);
                $zippingItem = $webTools->zipManager($zipDir, $fileName .'.zip', $zipDir, 'zip');
                
                echo "<div id='". time() ."' class='set-list-file-manager'>";
                echo "
                    <div class='badge badge-warning badge-custom-notice-term'>[Zip] ". date('H:i d-m-Y') ."</div>
                ";
                if ($zippingItem) {

                    echo "<div style='border-left: 2px dotted grey; padding-left: 10px;'>Zipping [$zipDir] Success.</div>";
                    
                } else {
                    echo "<div style='color: red; border-left: 2px dotted grey; padding-left: 10px;'>Zipping  [$zipDir] Failed.</div>";
                }

                echo "</div>";


            } elseif($pageShow == 'text-editor-act'){

                $action = trim($_GET['action']);
                $file_loc = trim($_POST['file_loc']);

                if ($action == 'open') {
                    $openFile = $webTools->streamFile($file_loc);
                    $actionSetcontent = '';
                    if ($openFile) {
                        $fileSize = number_format(filesize($file_loc));

                        $actionSetcontent .= "<div class='badge badge-warning badge-custom-notice-term'>[Open ". $file_loc ."] ". date('H:i d-m-Y') ."</div>";
                        $actionSetcontent .= "<div style='border-left: 2px dotted grey; padding-left: 10px;'>Size: [$fileSize Bytes]</div>";


                        $setJsonContent[] = [
                            "action"    =>  $actionSetcontent,
                            "content"   =>  $openFile 
                        ];

                        $setjson = json_encode($setJsonContent);
                        echo $setjson;
                    } else {

                        $fileSize = 'Unknown';
                        $actionSetcontent .= "<div class='badge badge-danger badge-custom-notice-term'>[Open Failed ". $file_loc ."] ". date('H:i d-m-Y') ."</div>";
                        $actionSetcontent .= "<div style='border-left: 2px dotted grey; padding-left: 10px;'>Size: [$fileSize Bytes]</div>";

                        $setJsonContent[] = [
                            "action"    =>  $actionSetcontent,
                            "content"   =>  $openFile 
                        ];

                        $setjson = json_encode($setJsonContent);
                        echo $setjson;
                    }
                } elseif($action == 'save') {
                    $content = trim($_POST['text_editor']);
                    $saveFile = $webTools->saveFile($file_loc, 'w+', $content);
                    $actionSetcontent = '';
                    if ($saveFile) {

                        $fileSize = number_format(filesize($file_loc));
                        $actionSetcontent .= "<div class='badge badge-success badge-custom-notice-term'>[Saved ". $file_loc ."] ". date('H:i d-m-Y') ."</div>";
                        $actionSetcontent .= "<div style='border-left: 2px dotted grey; padding-left: 10px;'>Size: [$fileSize Bytes]</div>";

                        $setJsonContent[] = [
                            "action"    =>  $actionSetcontent
                        ];

                        $setjson = json_encode($setJsonContent);
                        echo $setjson;
                    }else{



                        $fileSize = 'Unknown';
                
                        $actionSetcontent .= "<div class='badge badge-danger badge-custom-notice-term'>[Save Failed ". $file_loc ."] ". date('H:i d-m-Y') ."</div>";
                        $actionSetcontent .= "<div style='border-left: 2px dotted grey; padding-left: 10px;'>Size: [$fileSize Bytes]</div>";

                        if ($webTools->createFile($file_loc, 'w+', $content)) {
                            $fileSize = 'New File 0';
                            $actionSetcontent = "<div class='badge badge-primary badge-custom-notice-term'>[File Created ". $file_loc ."] ". date('H:i d-m-Y') ."</div> $actionSetcontent";
                            $actionSetcontent = "$actionSetcontent <div style='border-left: 2px dotted grey; padding-left: 10px;'>Size: [$fileSize Bytes]</div>";
                        }

                        $setJsonContent[] = [
                            "action"    =>  $actionSetcontent
                        ];

                        $setjson = json_encode($setJsonContent);
                        echo $setjson;
                    }
                } else{
                    echo "no action set.";
                }
            }

            else {
                dashboardPage($webTools);
            }

        }else{
            loginPage($webTools);
        }
    }

?>