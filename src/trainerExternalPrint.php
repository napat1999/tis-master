<?php
include_once("lib/myOAuth.php");
//include_once("lib/myS3.php");
include_once("lib/chkLogin.php");
include_once("lib/ftp_upload.php"); //If need login enable here
//restrict("istraining","trainerExternal"); //If need permission enable here
//include_once("lib/myIMGLib.php");

//Initial form value
$trainerID = isset($_GET["trainerID"]) ? $_GET["trainerID"] : '';
if ($trainerID <> "") {
    $sql = "select * from trainer where trainerid=" . $trainerID;
    $result = json_decode(pgQuery($sql), true);
    $isFound = 0;
    for ($i = 0; $i < count($result) - 1; $i++) {
        $isFound = 1;
        $trainer_type = $result[$i]['trainer_type'];
        $th_initial = $result[$i]['th_initial'];
        $thai_name = $result[$i]["thai_name"];
        $name_en = $result[$i]["name_en"];
        $lastname_en = $result[$i]["lastname_en"];
        $position = $result[$i]["position"];
        $company = $result[$i]["company"];
        $workplace = $result[$i]["workplace"];
        $telephone = $result[$i]["telephone"];
        $email = $result[$i]["email"];
        $studyinfo = $result[$i]["studyinfo"];
        $workinfo = $result[$i]["workinfo"];
        $trainerRemark = $result[$i]["trainerRemark"];
        $lastupdate = $result[$i]["lastupdate"];
        $imagepath = $result[$i]["imagepath"];
        $expends = $result[$i]["expends"];
        $spe_courses = $result[$i]["spe_courses"];
        $course_em = $result[$i]["course_em"];
        $gen = $result[$i]["gen"];
        $year_train = $result[$i]["year_train"];
        $contact_p = $result[$i]["contact_p"];
        $contact_tel = $result[$i]["contact_tel"];
        $contact_email = $result[$i]["contact_email"];
    }
    if ($isFound == 0) { //Wrong access_token
        $url = "error.php?code=trainerexternal";
        header("Location: " . $url);
    }
}

?>
<!DOCTYPE html>
<html lang="en" onload="window.print()">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt "Tahoma";
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }


        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .subpage {
            padding: 1cm;
            border: 5px red solid;
            height: 257mm;
            outline: 2cm #FFEAEA solid;

        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;

        }

        @page {
            size: A4;
            margin: 0;

        }

        #h {
            text-align: center;
            top: 0;
            width: 99%;
            height: 40px;
        }

        #footer {
            text-align: center;
            bottom: 0;
            width: 99%;
            height: 40px;

        }

        label {
            display: inline;
            font-size: 16px;
            width: 105px;
        }

        @media print {
            #h {
                text-align: center;
                top: 0;
                width: 99%;
                height: 40px;
            }

            #footer {
                text-align: center;
                bottom: 0;
                width: 99%;
                height: 40px;

            }

            label {
                display: inline;
                width: 105px;
                line-height: auto;
                font-size: 16px;
                
            }

            html,
            body {
                width: 210mm;
                height: 297mm;
            }


            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>

</head>

<body onload="window.print();">
    <div class="container">
        <div class="book">
            <div class="page">
                <div id="h">
                    <label>ข้อมูลวิทยากรภายนอก</label>
                </div>
                <img src="<?php echo $imagepath; ?>" class="center">
                <br>
                <br>
                <label ><b>ชื่อ-นามสกุล : </b></label>
                <label >
                    <?php echo $th_initial = isset($th_initial) ? $th_initial : ''; ?>
                    <?php echo $thai_name = isset($thai_name) ? $thai_name : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>ชื่อ-นามสกุลภาษาอังกฤษ : </b></label>
                <label >
                    <?php echo $name_en = isset($name_en) ? $name_en : '-'; ?>
                    <?php echo $lastname_en = isset($lastname_en) ? $lastname_en : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>ตำแหน่ง : </b></label>
                <label>
                    <?php echo $position = isset($position) ? $position : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>บริษัท : </b></label>
                <label>
                    <?php echo $company = isset($company) ? $company : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>สถานที่ทำงาน : </b></label>
                <label>
                    <?php echo $workplace = isset($workplace) ? $workplace : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>Telephone : </b></label>
                <label>
                    <?php echo $telephone = isset($telephone) ? $telephone : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>email : </b></label>
                <label>
                    <?php echo $email = isset($email) ? $email : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>ค่าแรงวิทยากร : </b></label>
                <label>
                    <?php echo $expends = isset($expends) ? $expends : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>หลักสูตรที่เชี่ยวชาญ : </b></label>
                <label>
                    <?php echo $spe_courses = isset($spe_courses) ? $spe_courses : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>หลักสูตรที่อบรมให้พนักงาน : </b></label>
                <label>
                    <?php echo $course_em = isset($course_em) ? $course_em : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>จำนวนรุ่น : </b></label>
                <label abel>
                    <?php echo $gen = isset($gen) ? $gen : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>ปีที่อบรม : </b></label>
                <label>
                    <?php echo $year_train = isset($year_train) ? $year_train : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>ผู้ประสานงาน : </b></label>
                <label>
                    <?php echo $contact_p = isset($contact_p) ? $contact_p : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>เบอร์โทร ผู้ประสานงาน : </b></label>
                <label>
                    <?php echo $contact_tel = isset($contact_tel) ? $contact_tel : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>Email ผู้ประสานงาน : </b></label>
                <label>
                    <?php echo $contact_email = isset($contact_email) ? $contact_email : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>ประวัติการศึกษา : </b></label>
                <label>
                    <?php echo $studyinfo = isset($studyinfo) ? $studyinfo : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>ประสบการณ์ทำงาน : </b></label>
                <label>
                    <?php echo $workinfo = isset($workinfo) ? $workinfo : '-'; ?>
                </label>
                <br>
                <br>
                <label><b>หมายเหตุ : </b></label>
                <label>
                    <?php echo $trainerRemark = isset($trainerRemark) ? $trainerRemark : '-'; ?>
                </label>
                <div id="footer">
                    <?php include_once("footer.php"); ?>
                </div>
            </div>
        </div>
    </div>


</body>

</html>