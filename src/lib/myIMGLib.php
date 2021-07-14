<?php
function checkLink($link){
    flush();
    $fp = @fopen($link, "r");
    @fclose($fp);
    if (!$fp) {
        return false;
    } else {
        return true;
    }
}

function checkURL($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    $result = curl_exec($ch);
    $ret = false;
    if ($result !== false) {
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($statusCode == 200) {
            $ret = true;
        }
    }
    curl_close($ch);
    return $ret;
}

function intranetIMGCheck($empID) {
    if(strpos($empID,'/') == true){
        $empID = str_replace('/','',$empID);
    }elseif(strpos($empID,'-') == true){
        $empID = str_replace('JTS','',$empID);
    }else{
        $empID = str_replace('BB','',$empID);
    }
    $intranetURL="https://intranet.jasmine.com/hr/office/Data/";
    $photoURL=$intranetURL.$empID.".jpg";
    if(!checkURL($photoURL)) {
        $photoURL="assets/img/guest.png"; //Default image if not found
    }
    return $photoURL;
}

function intranetIMGNoCheck($empID) { //Ignore check to improve speed
    if(strpos($empID,'/') == true){
        $empID = str_replace('/','',$empID);
    }elseif(strpos($empID,'-') == true){
        $empID = str_replace('JTS','',$empID);
    }else{
        $empID = str_replace('BB','',$empID);
    }
    $empID=str_replace('BB','',$empID); //Fix xxxxBB link broken
    $intranetURL="https://intranet.jasmine.com/hr/office/Data/";
    $photoURL=$intranetURL.$empID.".jpg";
    return $photoURL;
}

function trainerExIMGCheck($trainerID) {
    $trainerIMGFolder="assets/img/trainer/";
    $photoURL=$trainerIMGFolder."trainer".$trainerID.".jpg";
    if(!checkLink($photoURL)) {
        $photoURL="assets/img/guest.png"; //Default image if not found
    }
    return $photoURL;
}

function trainerExIMGNoCheck($trainerID) { //Ignore check to improve speed
  $trainerIMGURL="assets/img/trainer/";
  $photoURL=$trainerIMGURL."trainer".$trainerID.".jpg";
  return $photoURL;
}

function png2jpg($filePath) {
  $imageDir = strtolower(pathinfo($filePath,PATHINFO_DIRNAME));
  $imageName = strtolower(pathinfo($filePath,PATHINFO_FILENAME));
  //$imageExt = strtolower(pathinfo($filePath,PATHINFO_EXTENSION));
  $image = imagecreatefrompng($filePath);
  $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
  imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
  imagealphablending($bg, TRUE);
  imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
  imagedestroy($image);
  $quality = 50; // 0 = worst / smaller file, 100 = better / bigger file
  imagejpeg($bg, $imageDir."/".$imageName . ".jpg", $quality);
  imagedestroy($bg);
}
?>
