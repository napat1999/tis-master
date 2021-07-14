<?php
// Include the main TCPDF library (search for installation path).
require_once('lib/tcpdf/tcpdf_include.php');
require_once('lib/headerFormName1.php');

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
$courseDate="20-ธ.ค.-61";
$idNo="1234567890123";
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
        $courseDate="";
        $idNo="";
        break;
}
$studentNumber=count($student);
$stdPerPage=0;
$numPage=0;
$over20=$studentNumber % 20;
$over25=$studentNumber % 25;
if($over20<=5) {
    $stdPerPage=25;
    $rowheight="25px";
} else {
    $stdPerPage=20;
    $rowheight="28px";
}
$numPage=intdiv($studentNumber,$stdPerPage)+1;

$rowdata=array();
for($i=1;$i<=$numPage;$i++) {
    //Index = Blank row before fill
    $startIndex=(($i-1)*$stdPerPage)+1;
    $stopIndex=($i*$stdPerPage);
    for($j=$startIndex;$j<=$stopIndex;$j++) {
        $rowdata[$i-1].='<tr>';
        $rowdata[$i-1].='<td class="detail">'.$j.'</td>';
        $rowdata[$i-1].='<td class="detail">'.$student[$j-1].'</td>';
        $rowdata[$i-1].='<td class="detail">'.$idNo.'</td>';
        $rowdata[$i-1].='<td class="detail">&nbsp;</td>';
        $rowdata[$i-1].='<td class="detail">&nbsp;</td>';
        $rowdata[$i-1].='<td class="detail">&nbsp;</td>';
        $rowdata[$i-1].='<td class="detail">&nbsp;</td>';
        $rowdata[$i-1].='</tr>';
    }
}

$cssStyle=<<<EOD
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
        font-size: 17px;
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
EOD;
$headerForm=<<<EOD
    <tr>
        <td colspan="7" class="captionRight">แบบ ฝยฝป. 3</td>
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
        <td rowspan="3" class="header" style="width:1cm;">&nbsp;<br/>ลำดับ</td>
        <td rowspan="3" class="header" style="width:2cm;">&nbsp;<br/>รหัสพนักงาน</td>
        <td rowspan="3" class="header" style="width:2.5cm;">เลขที่<br/>บัตรประชาชน</td>
        <td rowspan="3" class="header" style="width:5cm;">&nbsp;<br/>ชื่อ - สกุล</td>
        <td rowspan="3" class="header" style="width:4cm;">&nbsp;<br/>ตำแหน่ง</td>
        <td colspan="2" class="header" style="width:4cm;">ลายเซ็นต์</td>
    </tr>
    <tr>
        <td colspan="2" class="header">$courseDate</td>
    </tr>
    <tr>
        <td class="header">เช้า</td>
        <td class="header">บ่าย</td>
    </tr>
EOD;

$footerForm=<<<EOD
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
EOD;


// Print text using writeHTML()
//data,newline,background,reset height,add padding,align
for($i=0;$i<$numPage;$i++) {
$html=<<<EOD
    <html>
    <head>
    $cssStyle
    </head>
    <body>
    <table style="width:100%;border-collapse: collapse;">
    $headerForm
    $rowdata[$i]
    $footerForm
    </table>
    </body>
    </html>
EOD;
    $pdf->AddPage();
    $pdf->writeHTML($html, true, false, true, false, '');
}

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.

$pdf->Output("formName1PDF.pdf", 'I');
?>
