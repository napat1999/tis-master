<style>
b{
  color:red;
}
</style>
<?php
include_once("lib/myS3.php");


//CopyObjectOnBucket('1','2','3');
echo "<br><br><b>List Buckets</b>-<br>";
$listBucket=ListBuckets();
foreach ( $listBucket['Buckets'] as $key =>  $bucket ) {
    $i=$key+1;
    echo $i.". Bucket Name=".$bucket['Name']."<br/>";
}


echo "<br><br><b>Check bucket 'obs-jas-nextcloud' exists</b><br>";
if (HeadBucket()=="200") {
  echo "Bucket exists<br/>";
} else {
  echo "Bucket error<br/>";
  exit;
}

echo "<br><br><b>Copy Object</b>";
echo '<br><b>Function: "CopyObjectOnBucket($copySource,$prefix,$filename)"</b>
<br> *Remark prfix Ex. TIS/SUBFOLDER/  </br></br>';

echo "<br><br><b>DELETE Object</b>";
echo '<br><b>Function: "DeleteObjectOnBucket($prefix,$filename)"</b>
<br> *Remark prfix Ex. TIS/SUBFOLDER/  </br></br>';

echo "<br><br><b>Download Object / SignURL</b>";
echo '<br><b>Function: "SignUrlObjectOnBucket($prefix,$filename,$isUrl)"</b>
<br> *Remark isUrl:1 return SignedUrl 0: object json </br></br>';

?>
<br><br>
<b>Upload File (default filename)</b>
<br><b>Function: "UploadObjectsToBucket($prefix,$filename,$file,$pathFile,$type)"</b>
<br> *Remark Ex. UploadObjectsToBucket("TIS/","filenam.txt",$_FILES['file'],$_FILES['tmp_name'],$_FILES['type']); </br></br>

<form action="<?= $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<input name="file" id="file" type="file" accept="image/*">
<input name="submit" value="Upload" type="submit" />
</form>


<?php
echo "<br><br><b>List Object' </b>";
echo '<br><b>Function: "ListObjects($prefix)"</b>
<br> *Remark Ex. ListObjects("TIS/")  </br></br>';
$resp=ListObjects("TIS/");
if ($resp['HttpStatusCode']=="200") {
  echo "List Objects....<br>";
  foreach ($resp['Contents']as $key => $content) {
    $filenameDel="'".$content['Key']."'";
    $urlImage=SignUrlObjectOnBucket("",$content['Key'],"1");
    echo $key.". ".$content['Key']
         .' <a href="'.$urlImage.'" target="_blank">view</a>'
         . ' <a onClick="deleteImage('.$filenameDel.')"  style="color:green"><U>delete</U></a>'
         . ' <a onClick="copyImage('.$filenameDel.')"  style="color:blue"><U>copy</U></a>'
         .'</br>';
  }

} else {
  echo "Error ".$resp."<br>";
  exit;
}


?>



<?php

if(isset($_FILES['file'])){
  echo "FILE UPLOAD";
  print_r($_FILES['file']);
  echo "<br><br><br>";
  $filename="".$_FILES['file']['name'];
  $prefix="TIS/SUBFOLDER/";
  echo UploadObjectsToBucket($prefix,$filename,$_FILES['file']['tmp_name'],$_FILES['file']['type']);
  //echo header( "location: ".$_SERVER['REQUEST_URI'] );
exit(0);

}else{
    echo "File not exists";
}

if(isset($_POST['deleteFile'])){
    DeleteObjectOnBucket("",$_POST['deleteFile']);
}else{
   //echo "File not exists";
}

if(isset($_POST['copyFile'])){
    $resp=CopyObjectOnBucket($_POST['copyFile'],$_POST['copyFile'],"copyFile");
    //print_r($resp);
}else{
   //echo "File not exists";
}
?>

<script src="assets/lib/jquery.min.js"></script>
<script>
  function deleteImage(image) {
    if(confirm("ยืยันการลบ")){
      $.ajax({
       url:"<?= $_SERVER['PHP_SELF']; ?>",
       type: "post", //request type,
       data: {deleteFile:image},
       success:function(data) {
         //handleData(data);
         console.log("delete");
         location.reload();
       }
      });
    }

  }
</script>

<script>
  function copyImage(path) {
    if(confirm("ยืนยัน Copy")){
      alert(path);
      $.ajax({
       url:"<?= $_SERVER['PHP_SELF']; ?>",
       type: "post", //request type,
       data: {copyFile:path},
       success:function(resp) {
         //handleData(data);
         console.log(resp);
         //location.reload();
       }
      });
    }

  }
</script>
