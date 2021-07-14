<?php
// แบบ ฝยฝป.3
// Include the main TCPDF library (search for installation path).
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
require_once('lib/tcpdf/tcpdf_include.php');
require_once('lib/myPDFDefault.php');

$courseID = isset($_POST["courseID"])?strtolower($_POST["courseID"]):'';
$fillUser = isset($_POST["fillUser"])?strtolower($_POST["fillUser"]):'';

$monthTH = [null,'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
$monthTH_brev = [null,'ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];

if($courseID<>"") {
  $sql="select * from course where courseid=".$courseID;
  $result=json_decode(pgQuery($sql),true);
  $isFound=0;
  for($i=0;$i<count($result)-1;$i++) {
    $isFound=1;
    $coursemasterid=$result[$i]['coursemasterid'];
    $courseGen=$result[$i]['coursegen'];
    $nameOfficial=$result[$i]['nameofficial'];
    $nameMarketing=$result[$i]['namemarketing'];
    $approxstudent = $result[$i]["approxstudent"];
  }
  if($isFound==0) { //Wrong access
    $url="error.php?code=courseStudent";
    header("Location: ".$url);
  }
} else {
  //For test, real case read from DB
  $nameOfficial=str_repeat("_",20);
  $courseDate="";
}

$prepareStudent=$approxstudent+10;

$filterStatus="";
switch ($fillUser) {
  case '0':
    $filterStatus=" and status=999";
    break;
  case '1':
    $filterStatus=" and status=1";
    break;
  case '1':
    $filterStatus=" and status in ('0','1')";
    break;
  default:
    // code...
    break;
}
// $sql="select count(*) cnt from coursestudent where courseid=".$courseID.$filterStatus;
// $resultStudent=json_decode(pgQuery($sql),true);
// $studentNumber=$resultStudent[0]['cnt'];

$student=array();
// if($studentNumber>0) {
  $sql="select * from coursestudent where courseid=".$courseID.$filterStatus;
  $sql.=" order by thai_name";
  $resultStudent=json_decode(pgQuery($sql),true);
  if($resultStudent['code']=="200") {
    for($i=0;$i<count($resultStudent)-1;$i++) {
      $student[$i]['employeeno']=$resultStudent[$i]['employeeno'];
      $student[$i]['personal_id']=$resultStudent[$i]['personal_id'];
      $student[$i]['th_initial']=$resultStudent[$i]['th_initial'];
      $student[$i]['thai_name']=$resultStudent[$i]['thai_name'];
      $student[$i]['position']=$resultStudent[$i]['position'];
    }
  }
// }

$stdPerPage=0;
$numPage=0;
$over20=$prepareStudent % 20;
$over25=$prepareStudent % 25;
if($over20<=5) {
    $stdPerPage=25;
    $rowheight="24px";
} else {
    $stdPerPage=20;
    $rowheight="27px";
}
$numPage=intdiv($prepareStudent,$stdPerPage)+1;

$rowdata=array();
$stdCount=0;
$menCount=0;
$womenCount=0;
for($i=1;$i<=$numPage;$i++) {
    //Index = Blank row before fill
    $startIndex=(($i-1)*$stdPerPage)+1;
    $stopIndex=($i*$stdPerPage);
    for($j=$startIndex;$j<=$stopIndex;$j++) {
        if($student[$j-1]['employeeno']!="") {
          $th_initial=str_replace("นางสาว","น.ส.",$student[$j-1]['th_initial']);
          if($th_initial=="นาย") {
            $menCount++;
          } else {
            $womenCount++;
          }
          $stdCount++;
        } else {
          $th_initial="";
        }

        $rowdata[$i-1].='<tr>';
        $rowdata[$i-1].='<td class="detail">'.$j.'</td>';
        $rowdata[$i-1].='<td class="detail">'.$student[$j-1]['employeeno'].'</td>';
        $rowdata[$i-1].='<td class="detail">'.$student[$j-1]['personal_id'].'</td>';
        $rowdata[$i-1].='<td class="detailleft">'.$th_initial.' '.$student[$j-1]['thai_name'].'</td>';
        $rowdata[$i-1].='<td class="detail">'.$student[$j-1]['position'].'</td>';
        $rowdata[$i-1].='<td class="detail">&nbsp;</td>';
        $rowdata[$i-1].='<td class="detail">&nbsp;</td>';
        $rowdata[$i-1].='</tr>';
    }
}

$daySchedule=array();
$dayScheduleCount=0;
$sql="select * from courseschedule where courseId=".$courseID;
$result=json_decode(pgQuery($sql),true);
if($result['code']=="200") {
  for($i=0;$i<count($result)-1;$i++) {
    $datebegin=new DateTime($result[$i]['datebegin']);
    $dateend=new DateTime($result[$i]['dateend']);
    $interval=new DateInterval('P1D');;
    $period = new DatePeriod($datebegin, $interval, $dateend);
    foreach ($period as $dt) {
      $dtd=$dt->format("d");
      $dtm=$monthTH_brev[$dt->format("n")];
      $dty=$dt->format("Y")+543;
      $daySchedule[$dayScheduleCount]="$dtd $dtm $dty";
      // echo $dayScheduleCount.".";
      // echo $daySchedule[$dayScheduleCount]."<br>";
      $dayScheduleCount++;
    }
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
        font-size: 16px;
        border: 1px solid black;
    }
    td.detailleft {
        text-align:left;
        font-size: 16px;
        border: 1px solid black;
    }
    td.footer {
        text-align:center;
        font-size: 16px;
    }
</style>
EOD;

$footerForm=<<<EOD
    <tr>
        <td colspan="7">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
        <td colspan="4" class="footer">จำนวนผู้เข้ารับการฝึกอบรม รวม&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คน
          ชาย&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คน
          หญิง&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คน
        </td>
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

for($d=0;$d<$dayScheduleCount;$d++) {
$headerForm=<<<EOD
    <tr>
        <td colspan="7" class="captionRight">แบบ ฝยฝป. 3</td>
    </tr>
    <tr>
        <td colspan="7" class="caption">รายชื่อผู้รับการฝึกอบรม (กรณีดำเนินการฝึกเอง)</td>
    </tr>
    <tr>
        <td colspan="7" class="caption">ชื่อหลักสูตร $nameOfficial</td>
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
        <td rowspan="3" class="header" style="width:1.8cm;">รหัส<br/>พนักงาน</td>
        <td rowspan="3" class="header" style="width:2.5cm;">เลขที่<br/>บัตรประชาชน</td>
        <td rowspan="3" class="header" style="width:4.8cm;">&nbsp;<br/>ชื่อ - สกุล</td>
        <td rowspan="3" class="header" style="width:4.4cm;">&nbsp;<br/>ตำแหน่ง</td>
        <td colspan="2" class="header" style="width:4cm;">ลายเซ็น</td>
    </tr>
    <tr>
        <td colspan="2" class="header">$daySchedule[$d]</td>
    </tr>
    <tr>
        <td class="header">เช้า</td>
        <td class="header">บ่าย</td>
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

}

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.

$pdf->Output("form1"-$courseID.".pdf", 'I');
?>
