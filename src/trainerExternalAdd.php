<?php
include_once("lib/myOAuth.php");
//include_once("lib/myS3.php");
include_once("lib/chkLogin.php"); //If need login enable here
//restrict("istraining","trainerExternal"); //If need permission enable here
//include_once("lib/myIMGLib.php");

//Initial form value

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TIS : วิทยากรภายนอก</title>
    <?php include_once("basicHeader.php"); ?>
    <style>
        /*#loading {
		  display: none;
		  position: absolute;
		  left: 50%;
		  top: 45%;
		  z-index: 999;
		  width: 150px;
		  height: 150px;
		  margin: -75px 0 0 -75px;
		  border: 16px solid #f3f3f3;
		  border-radius: 50%;
		  border-top: 16px solid #3498db;
		  width: 120px;
		  height: 120px;
		  -webkit-animation: spin 2s linear infinite;
		  animation: spin 2s linear infinite;
	    }*/
        /*  li a {
   display:block;
}*/
        .nopaddingRight {
            padding-right: 0px !important;
            margin-right: 0px !important;
            padding-left: 5px !important;

        }

        .nopadding {
            padding-left: 0px !important;
            padding-right: 0px !important;
            margin-top: 2px !important;
            margin-bottom: 10px !important;
            margin-right: 15px !important;
            /* margin-left: 15px !important;*/

        }

        .quick-btn-custom {
            background: #FFFFFF;
            -webkit-box-shadow: 0 0 0 1px #F8F8F8 inset, 0 0 0 1px #CCCCCC;
            box-shadow: 0 0 0 1px #F8F8F8 inset, 0 0 0 1px #CCCCCC;
            color: #444444;
            display: inline-block;
            height: 60px;
            padding-top: 10px;
            text-align: center;
            text-decoration: none;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.6);
            width: 85px;
            border-radius: 6px;
            position: relative;
        }

        .modal-footer button {
            float: right;
            margin-left: 10px;
        }

        .quick-btn-custom li a {
            display: block;
        }

        .quick-btn-custom span {
            float: right;
            margin-right: 5px;
            padding-right: 10px;
            padding-top: 5px;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .form-horizontal .control-label {
            /* text-align:right; */
            text-align: left;
        }

        .kv-avatar {
            display: inline-block;
        }

        .kv-avatar .file-input {
            display: table-cell;
            width: 213px;
        }

        input:required {
            border-color: #f28d68;
        }

        .select2-container {
            height: 34px !important;
            ;
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            position: relative;
            vertical-align: middle;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {



            width: 100%;
            height: 34px !important;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143 !important;
            vertical-align: bottom !important;


        }

        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 34px !important;
            user-select: none;
            -webkit-user-select: none;


        }

        .control-label {
            text-align: right !important;
        }
    </style>
    <link href="lib/summernote-0.8.11/summernote.css" rel="stylesheet">
    <link rel="stylesheet" href="lib/select2-4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="lib/bootstrap-fileinput-v4.5.2-0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />

</head>

<body>
    <div id='loading'>
        <div class="loading-backdrop">
        </div>
        <div class="loading-img">
            <img src="assets/img/Loading-tis.gif" width="400px" />
        </div>
    </div>
    <div id="wrap">
        <?php $menu = "trainer" ?>
        <?php $submenu = "trainerExternal.php" ?>
        <?php include_once("top.php"); ?>
        <?php include_once("left.php"); ?>
        <div id="content">
            <div class="outer">
                <div class="inner">
                    <div class="box">
                        <header>

                            <ol class="breadcrumb">
                                <li>
                                    <a href="">
                                        <i class="fas fa-fw fa-chalkboard-teacher"></i> วิทยากร</a>
                                </li>
                                <li>
                                    <a href="trainerExternal.php">
                                        <i class="fas fa-fw fa-address-book"></i> วิทยากรภายนอก
                                    </a>
                                </li>
                                <li class="active">
                                    <i class="fas fa-user"></i> เพิ่มวิทยากรภายนอก
                                </li>
                            </ol>
                        </header>
                        <div class="body">
                            <div id='loading'></div>
                            <form id="formJPM" name="formJPM" class="form-horizontal " method="post">
                                <!--1st row-->
                                <div class="row">
                                    <input type="hidden" id="trainerID" name="trainerID">
                                    <input type="hidden" id="trainer_type" name="trainer_type" value="E">
                                    <div class="col-md-8">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <i class="fas fa-id-card-alt"></i>
                                                Basic Information
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-2">ชื่อ-นามสกุล</label>
                                                    <div class="col-sm-2">
                                                        <select id="th_initial" name="th_initial" class="form-control" required>
                                                            <option value="นาย">นาย</option>
                                                            <option value="นาง">นาง</option>
                                                            <option value="น.ส.">น.ส.</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="thai_fname" name="thai_fname" required>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="thai_lname" name="thai_lname" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-2">ชื่อ-นามสกุลภาษาอังกฤษ</label>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" id="name_en" name="name_en">
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" id="lastname_en" name="lastname_en">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-2">ตำแหน่ง</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="position" name="position">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">บริษัท</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="company" name="company">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-2">สถานที่ทำงาน</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="workplace" name="workplace">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-2">โทรศัพท์</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="telephone" name="telephone">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">email</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="email" name="email">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">ค่าแรงวิทยากร </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="expends" name="expends">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">หลักสูตรที่เชี่ยวชาญ</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="spe_courses" name="spe_courses">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">หลักสูตรที่อบรมให้พนักงาน</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="course_em" name="course_em">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">จำนวนรุ่น</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="gen" name="gen">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">ปีที่อบรม</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="year_train" name="year_train">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">ผู้ประสานงาน</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="contact_p" name="contact_p">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">เบอร์โทร ผู้ประสานงาน</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="contact_tel" name="contact_tel">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Email ผู้ประสานงาน</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="contact_email" name="contact_email">
                                                    </div>
                                                </div>
                                                <!-- <div class="form-group row" <?php if ($trainerID == "") {
                                                                                        echo " style='display:none'";
                                                                                    } ?>>
                          <label class="control-label col-sm-2">ปรับปรุงข้อมูลล่าสุด</label>
                          <div class="col-sm-10">
                            <span class="col-sm-8" style="padding-top:10px;color:green;"><?php echo date('d/m/Y H:i:s', strtotime($lastupdate)); ?></span>
                          </div>
                        </div> -->
                                            </div>
                                            <!--End panel body-->
                                        </div>
                                        <!--End panel Left-->
                                    </div>
                                    <!--End Left MD-->
                                    <div class="col-md-4 text-center" style="padding-top:10px;">
                                        <div class="kv-avatar">
                                            <div class="file-loading">
                                                <input name="avatar" id="avatar" type="file" accept="image/*">
                                            </div>
                                            <div id="errorBlock" class="help-block"></div>
                                            <span>ขนาดขั้นต่ำ 50x50 สูงสุด 600*400 [2MB]</span>
                                        </div>
                                    </div>
                                </div>
                                <!--End 1st row-->
                                <!--2nd row-->
                                <div class="row">

                                    <div class="col-md-8">
                                        <!--Begin Right MD-->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <i class="fas fa-user-plus"></i>
                                                Extra Information (TIS)
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class=" col-md-8">ประวัติการศึกษา</label>
                                                            <div class="col-md-12">
                                                                <textarea id="studyinfo" class="form-control" name="studyinfo" rows="5" cols="80"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-md-8">ประสบการณ์ทำงาน</label>
                                                            <div class="col-md-12">
                                                                <textarea id="workinfo" class="form-control" name="workinfo" rows="5" cols="80"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>




                                            </div>
                                            <!--End panel body-->
                                        </div>
                                        <!--End panel Right-->
                                    </div>
                                    <!--End Right MD-->

                                    <!--End 2nd row-->
                                    <!--3rd row-->
                                    <!--End Right MD-->
                                    <div class="col-md-8">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <i class="fas fa-marker"></i>
                                                หมายเหตุ
                                            </div>
                                            <div class="panel-body">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <textarea rows="5" class="form-control" id="trainerRemark" name="trainerRemark"></textarea>
                                                        <?php //if($trainerremark!=''){echo $trainerremark;}
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>
                        <!-- 4nd row-->
                        <div class="row">



                            <div class="form-group col-md-8" align="center">
                                <div class="form-group  col-md-2">
                                    <a href="trainerExternal.php" class="btn btn-default btn-sm">
                                        <i class="fas fa-arrow-circle-left"></i> กลับ
                                    </a>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm" id="btnSave">
                                    <i class="fas fa-save"></i> บันทึกข้อมูล
                                </button>
                                <button id="btnCancel" class="btn btn-danger btn-sm">
                                    <i class="fas fa-times-circle"></i> ยกเลิก
                                </button>
                            </div>
                        </div>

                    </div>
                    <!--End 3rd row-->
                    </form>
                    <div id='debug'></div>
                </div>
            </div>
        </div>

        <!-- end .inner -->
    </div>

    <!-- end .outer -->
    </div>

    <!-- end #content -->
    </div><!-- /#wrap -->
    <div id="footer">
        <?php include_once("footer.php"); ?>
    </div>
    <!-- Modal -->
    <div id="popupModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <br />
                </div>
                <div class="modal-body">
                    <div id="modalContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <?php include_once("notification.php"); ?>
    <script src="assets/lib/jquery.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables.js"></script>
    <script src="assets/lib/screenfull/screenfull.js"></script>
    <script src="assets/js/main.min.js"></script>
    <script src="lib/summernote-0.8.11/summernote.js"></script>
    <script src="lib/summernote-0.8.11/lang/summernote-th-TH.js"></script>
    <script src="lib/select2-4.0.5/js/select2.min.js"></script>
    <script src="lib/bootstrap-fileinput-v4.5.2-0/js/fileinput.min.js"></script>
    <script src="lib/bootstrap-fileinput-v4.5.2-0/js/locales/th.js"></script>
    <script src="lib/bootbox-5.1.3/bootbox.js"></script>
    <script src="assets/js/tisApp.js"></script>

    <script type="text/javascript">
        $("#btnCancel").click(function(event) {
            event.preventDefault();
            bootbox.confirm({
                title: "กรุณายืนยัน ยกเลิกการบันทึกข้อมูล ?",
                backdrop: true,
                closeButton: false,
                message: "คุณต้องการยกเลิกการบันทึกข้อมูล</br>และกลับสู่เมนูก่อนหน้า",
                size: 'small',
                animate: true,
                centerVertical: true,
                className: "confirmDelete bootbox-confirm",
                buttons: {
                    confirm: {
                        label: '<i class="fa fa-check "></i> ยืนยัน',
                        className: 'btn btn-success btn-sm'
                    },
                    cancel: {
                        label: '<i class="fa fa-times"></i> ยกเลิก',
                        className: 'btn btn-danger btn-sm'
                    }

                },
                callback: function(result) {
                    if (result) {
                        window.location.href = 'trainerExternal.php';
                    }

                }
            });
        });

        function deleteDocument(id, path, name) {
            var dialog;



            bootbox.confirm({
                size: 'small',
                title: "กรุณายืนยัน การลบไฟลข้อมูล ?",
                message: name,
                size: 'small',
                animate: true,
                centerVertical: true,
                className: "confirmDelete bootbox-confirm",
                buttons: {
                    confirm: {
                        label: 'ตกลง',
                        className: 'btn-success btn-sm'
                    },
                    cancel: {
                        label: 'ยกเลิก',
                        className: 'btn-danger btn-sm'

                    }
                },
                callback: function(result) {
                    if (result) {
                        var dataPostx = new FormData();
                        dataPostx.append('trainerDocID', id);
                        dataPostx.append('filePath', path);
                        console.log(path);
                        $.ajax({
                            type: "POST",
                            url: "db/deleteTrainerDocument.php",
                            data: dataPostx,
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $('#loading').show();
                                /*   dialog=bootbox.dialog({
                                         closeButton: false,
                                         size: "small",
                                         message: '<p class="text-primary text-center"><i class="fa fa-spin fa-spinner fa-lg"></i> กรุณารอสักครู่...</p>'
                                    });*/

                            },
                            success: function(result) {
                                var obj = JSON.parse(result);
                                //  console.log(obj);
                                if (obj.code == "200") {
                                    $('#loading').hide();
                                    // console.log(DocumentCount-1);
                                    DocumentCount = DocumentCount - 1;
                                    // dialog.modal('hide');
                                    $("#" + id).remove();
                                    if ($.trim($("#documentList").html()) == "") {
                                        $("#documentListLabel").remove();
                                    } else {
                                        $("#documentListCount").html('รายการเอกสารทีแนบไว้ ' + DocumentCount + ' ไฟล์');

                                    }
                                    tisAlertMessage('ผลการดำเนินการ', '<i class="fas fa-clipboard-check fa-2x"></i> ลบไฟล์ข้อมูลสิ้นเสร็จสิ้น', 'completed', 'small', '', false);


                                } else {
                                    $('#loading').hide();

                                    tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด', 'ไม่สามารถลบไฟล์ข้อมูล <br>' + obj.message + '<br>กรุณาติดต่อผู้ดูแลระบบ', 'error', 'small', '', false);

                                    /*

                                                        bootbox.alert({
                                                            size: "small",
                                                            message: obj.message,
                                                        })
                                                        dialog.modal('hide');*/
                                }
                            },
                            error: function() {
                                tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อ server ได้ <br>Cannot call save api<br>กรุณาติดต่อผู้ดูแลระบบ', 'error', 'small', '', false);
                                /* bootbox.alert({
                                     size: "small",
                                     message: "ไม่สามารถเชื่อมต่อ server ได",
                                 })
                                dialog.modal('hide');*/
                            }
                        });
                    }
                }


            });

        }

        function popup(msg) {
            $('#modalContent').html(msg);
            $('#popupModal').modal('show');
        }
        $(document).ready(function() {
            <?php
            $oldImage = "assets/img/guest.png";
            $previewAvatar = '<img src="assets/img/guest.png" alt="Your Avatar" width="200"><h6 class="text-primary">Click to upload</h6>';
            ?>

            maxImageWidth = '400';
            maxImageHeight = '600';
            $("#avatar").fileinput({
                language: "th",
                overwriteInitial: true,
                minImageWidth: 50,
                minImageHeight: 50,
                maxImageWidth: maxImageWidth,
                maxImageHeight: maxImageHeight,
                maxFileSize: 2048, //kb
                showClose: false,
                showCaption: false,
                showBrowse: false,
                browseOnZoneClick: true,
                removeLabel: 'ยกเลิก/ลบ',
                removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
                //defaultPreviewContent: '',
                layoutTemplates: {
                    main2: '{preview} {remove} {browse}'
                },
                allowedFileExtensions: ["jpg", "png"],
                elErrorContainer: "#errorBlock"
            }).on('fileuploaded', function(event, data) {
                //$('#kv-success-box').append(data.response.link);
                //$('#kv-success-modal').modal('show');
            });

            $('#avatar').on('fileerror', function(event, data, msg) {
                //Prevent submit
                $('#btnSave').prop('disabled', true);
            });

            $('#avatar').on('fileimageloaded', function(event, previewId) {
                //Validate size
                var img = $('#' + previewId).find('img')[0];
                if (img.naturalWidth > maxImageWidth & img.naturalHeight > maxImageHeight) {
                    //Prevent submit
                    $('#btnSave').prop('disabled', true);
                }
            });

            $('#avatar').on('fileclear', function(event) {
                //Enable submit
                $('#btnSave').prop('disabled', false);
            });


            //Save
            //$("#btnSave").click(function(){
            $("#formJPM").submit(function(e) {
                var dialogt;
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: "db/saveTrainerExternal.php",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#loading').show();
                        /*
                         dialogt=bootbox.dialog({
                              closeButton: false,
                              size: "small",
                              message: '<p class="text-primary text-center"><i class="fa fa-spin fa-spinner fa-lg"></i> กรุณารอสักครู่...</p>'
                         });*/
                    },
                    success: function(result) {
                        console.log(result)
                        var obj = JSON.parse(result);
                        $('#loading').hide();

                        <?php

                        echo "var IsRedirect=true;";

                        ?>
                        if (obj.code == "200") {
                            //  dialogt.modal('hide');
                            tisAlertMessage('ผลการดำเนินการ', '<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น', 'completed', 'small', 'trainerExternal.php', IsRedirect);
                            /*  bootbox.alert({
                                  size: "small",
                                  closeButton: false,
                                  message: '<p ><i class="fa fa-check-double fa-lg text-success"></i> บันทึกสำเร็จ  </p>',
                                  callback: function () {
                                        location.reload();
                                  }
                              });*/


                        } else {
                            tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้ <br>' + obj.message + '<br>กรุณาติดต่อผู้ดูแลระบบ', 'error', 'small', 'trainerExternalAdd.php', false);
                            /*  dialogt.modal('hide');
                              bootbox.alert({
                                  size: "small",
                                  closeButton: false,
                                  message: '<p ><i class="fa fa-times fa-lg text-danger"></i> '+obj.message+'</p>',
                                  callback: function () {
                                        location.reload();
                                  }
                              });*/
                            //popup(obj.message);
                        }
                    },
                    error: function() {
                        $('#loading').hide();
                        tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้ <br>Cannot call save api<br>กรุณาติดต่อผู้ดูแลระบบ', 'error', 'small', 'trainerExternalAdd.php', false);
                        //dialogt.modal('hide');
                        //popup("Cannot call save api");
                        /*bootbox.alert({
                            size: "small",
                            closeButton: false,
                            message: '<p ><i class="fa fa-times fa-lg text-danger"></i> Cannot call save api  </p>',
                            callback: function () {
                                  //location.reload();
                            }
                        })*/

                    }
                });
            });
            // $('#popupModal').on('hidden.bs.modal', function () {
            //     if ($('#modalContent').html()=="Completed save.") {
            //       location.reload();
            //     }
            // });
        });

        $(function() {
            $("input[required]").each(function() {
                if ($(this).val().length > 0) {
                    $(this).css('border-color', '#ccc');
                } else {
                    $(this).css('border-color', '#f28d68');
                }

                $(this).on('change', function() {
                    if ($(this).val().length > 0) {
                        $(this).css('border-color', '#ccc');
                    } else {
                        $(this).css('border-color', '#f28d68');
                    }
                });
            }) //end each
        }); //end function
    </script>
    <!-- <script>
    $("#file-document").fileinput({
      uploadUrl: "/file-upload-batch/1",
      uploadAsync: false,
      minFileCount: 1,
      maxFileCount: 5,
      showUpload: false,
      allowedFileExtensions: ["pdf"],
      language: "th",
      initialPreviewAsData: true, // defaults markup
      preferIconicPreview: true, // this will force thumbnails to display icons for following file extensions
      previewFileIconSettings: { // configure your icon file extensions
        'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
      },

    }).on('filesorted', function(e, params) {
      console.log('File sorted params', params);
    }).on('fileuploaded', function(e, params) {
      console.log('File uploaded params', params);
    });
  </script> -->
</body>

</html>