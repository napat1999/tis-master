<?php
// Include the main TCPDF library (search for installation path).
require_once('lib/tcpdf_include.php');

function genArray($size) {
    $tempArray=array($size);
    for($i=0;$i<$size;$i++) {
        $tempArray[$i]="RO".str_pad($i+1, 4, '0', STR_PAD_LEFT);
    }
    return $tempArray;
}

//$courseID = isset($_GET["courseID"])?strtolower($_GET["courseID"]):'';
$courseID = isset($_POST["courseID"])?strtolower($_POST["courseID"]):'';
//For test, real case read from DB
$student=array();
switch($courseID) {
    case "1" :
        $courseName="อบรมพนักงานขาย รุ่นที่ ".$courseID;
        $student=genArray(15);
        break;
    case "2" :
    $courseName="อบรมพนักงานขาย รุ่นที่ ".$courseID;
        $student=genArray(20);
        break;
    case "3" :
        $courseName="อบรมพนักงานขาย รุ่นที่ ".$courseID;
        $student=genArray(23);
        break;
    case "4" :
        $courseName="อบรมพนักงานขาย รุ่นที่ ".$courseID;
        $student=genArray(30);
        break;
    default : //Empty
        $courseName=str_repeat("_",20);
        break;
}
$studentNumber=count($student);
$stdPerPage=0;
if ($studentNumber>20 && $studentNumber <=25 ) {
    $stdPerPage=25;
    $rowheight="25px";
    require_once('lib/headerFormName1.php');
} else {
    $stdPerPage=20;
    $rowheight="28px";
    require_once('lib/headerFormName1.php');
}
$rowdata="";
for($i=0;$i<$stdPerPage;$i++) {
    $rowdata.='<tr>';
    $rowdata.='<td class="detail">'.($i+1).'</td>';
    $rowdata.='<td class="detail">'.$student[$i].'</td>';
    $rowdata.='<td class="detail">&nbsp;</td>';
    $rowdata.='<td class="detail">&nbsp;</td>';
    $rowdata.='<td class="detail">&nbsp;</td>';
    $rowdata.='<td class="detail">&nbsp;</td>';
    $rowdata.='<td class="detail">&nbsp;</td>';
    $rowdata.='</tr>';
}

$html=<<<EOD
<html>
<head>
    <title>formName1</title>
    <style>
        tr {
            line-height: $rowheight;
        }
        td.captionRight {
            text-align:right;
            font-weight: bold;
            font-size: 18px;
        }
        td.caption {
            text-align:center;
            font-weight: bold;
            font-size: 18px;
        }
        td.captionSmall {
            text-align:center;
            font-weight: bold;
            font-size: 14px;
        }
        td.header {
            text-align:center;
            font-weight: bold;
            font-size: 18px;
            vertical-align:middle;
            border: 1px solid black;
        }
        td.detail {
            text-align:center;
            font-size: 14px;
            border: 1px solid black;
        }
        td.footer {
            text-align:center;
            font-size: 14px;
        }    

    </style>
</head>
<body>
<table style="width:100%;border-collapse: collapse;">
    <tr>
        <td colspan="7" class="captionRight" style="">แบบ ฝยฝป. 3</td>
    </tr>
    <tr>
        <td colspan="7" class="caption">รายชื่อผู้รับการฝึกอบรม (กรณีดำเนินการฝึกเอง)</td>
    </tr>
    <tr>
        <td colspan="7" class="caption">ชื่อหลักสูตร $courseName</td>
    </tr>
    <tr>
        <td colspan="7" class="caption">สำหรับบริษัท ทริปเปิลที บรอดแบนด์ จำกัด (มหาชน)</td>
    </tr>
    <tr>
        <td colspan="7" class="captionSmall">(ผู้รับการฝึกต้องเข้ารับการฝึกอบรมไม่น้อยกว่าร้อยละแปดสิบของระยะเวลาการฝึกอบรมทั้งหลักสูตร)</td>
    </tr>
    <tr><td colspan="7">&nbsp;</td></tr>
    <tr>
        <td rowspan="3" class="header" style="width:2cm;">ลำดับ</td>
        <td rowspan="3" class="header">รหัสพนักงาน</td>
        <td rowspan="3" class="header">เลขที่บัตรประชาชน</td>
        <td rowspan="3" class="header">ชื่อ - สกุล</td>
        <td rowspan="3" class="header">ตำแหน่ง</td>
        <td colspan="2" class="header">ลำดับ</td>
    </tr>
    <tr>
        <td colspan="2" class="header" style="color:red">ระบุ วันที่/เดือน/ปี</td>
    </tr>
    <tr>
        <td class="header">เช้า</td>
        <td class="header">บ่าย</td>
    </tr>
    $rowdata
    <tr>
        <td colspan="7">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
        <td colspan="4" class="footer">จำนวนผู้เข้ารับการฝึกอบรม รวม...............คน ชาย..........คน  หญิง..........คน</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
        <td colspan="4" class="footer">ขอรับรองว่าเป็นความจริง</td>
    </tr>
    <tr>
        <td colspan="7">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="7s">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
        <td colspan="4" class="footer">( น.ส.พรพิมล นาคพินิจ )</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
        <td colspan="4" class="footer">ตำแหน่ง เจ้าหน้าที่อาวุโส ฝ่ายฝึกอบรมและพัฒนาบุคลากร</td>
    </tr>
</table>
EOD;

// Print text using writeHTML()
//data,newline,background,reset height,add padding,align
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->AddPage();

$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.

$pdf->Output("formName1PDF.pdf", 'I');
?>