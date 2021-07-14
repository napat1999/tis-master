<?php

function uploadimg($prefix, $filename, $temp, $type)
{
    // $ftp_server = "10.11.30.91";
    // $ftp_user_name = "training_admin";
    // $ftp_user_pass = "train@hr01";
    //  //echo $file_data ;
    // //$avatar = $_REQUEST['avatar'];
    // //$name = $_POST['name'];
    // //$ext = pathinfo(basename($file_data['name']), PATHINFO_EXTENSION);
    // $conn_id = ftp_connect($ftp_server);
    // ftp_chdir($conn_id, "htdocs/upload/store_file");
    // $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
     $new_img_name = $filename . "." . $type;
    $img_path = $prefix;
    $upload_path = $img_path . $new_img_name;
     $upload_path1 = "assets/img/trainer/" . $new_img_name;
    $success = move_uploaded_file($temp, $upload_path);
    //echo $success ;
    if ($success != 1) {
        echo "";
        exit();
    }
//     $new_img_name = $filename . "." . $type;
//     $upload_path1 = "upload/trainer" . $new_img_name;
//     $ftp_server = "10.11.7.169";
//     $ftp_user_name = "backend_tis";
//     $ftp_user_pass = "P@ssw0rd";

//     $destination_file = $_FILES['avatar']['name'];
//     $source_file = $_FILES['avatar']['tmp_name'];
//     $size_file = $_FILES['avatar']['size'];
//     $conn_id = ftp_connect($ftp_server, 21);
//     // login with username and password
//     $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
//     ftp_chdir($conn_id,$prefix);
//     // check connection  
//     if ((!$conn_id) || (!$login_result)) {
//         echo "FTP connection has failed!";
//         echo "Attempted to connect to $ftp_server for user $ftp_user_name";
//         exit;
//     } else {
//         echo "Connected to $ftp_server, for user $ftp_user_name<br/>";
//     }
//     // upload the file  
//     $upload = ftp_put($conn_id, $new_img_name, $temp, FTP_BINARY);

//     // check upload status  
//     if (!$upload) {
//         echo "FTP upload has failed!";
//     }

//     // close the FTP stream  
//     ftp_close($conn_id);
//    return $upload_path1;
    return $upload_path1;
}

// function ftp_getfile()
// {
//     $ftp_server = "10.11.30.91";
//     $ftp_user_name = "training_admin";
//     $ftp_user_pass = "train@hr01";
//     $conn_id = ftp_connect($ftp_server, 21);
//     // login with username and password
//     $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// }
