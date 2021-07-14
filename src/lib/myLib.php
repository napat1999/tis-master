<?php
function minToText($min) {
  $hours=floor($min / 60);
  if($hours>0) {
    $hoursText=$hours." ชม.";
  }
  $minutes=$min%60;
  if($minutes>0) {
    $minutesText=$minutes." นาที";
  }
  return $hoursText." ".$minutesText;
}

function statusText($statusID) {
  switch ($statusID) {
    case '0':
      return "เตรียมข้อมูล";
      break;
    case '1':
      return "รอตรวจสอบหลักสูตร";
      break;
    case '2':
      return "ตรวจสอบแล้ว (งบพื้นที่) รอผลอนุมัติ";
      break;
    case '3':
      return "ตรวจสอบแล้ว (งบส่วนกลาง) รอตรวจสอบขั้นสอง";
      break;
    case '21':
      return "<span style='color:green'>อนุมัติ (งบพื้นที่) รอเปิดรับสมัคร</span>";
      break;
    case '22':
      return "<span style='color:red'>ไม่อนุมัติ (งบพื้นที่)</span>";
      break;
    case '31':
        return "ตรวจสอบขั้นสองแล้ว (งบส่วนกลาง) รออนุมัติ";
        break;
    case '32':
      return "<span style='color:red'>ไม่ผ่านตรวจสอบขั้นสอง (งบส่วนกลาง)</span>";
      break;
    case '33':
      return "<span style='color:green'>อนุมัติ (งบส่วนกลาง) รอเปิดรับสมัคร</span>";
      break;
    case '34':
      return "<span style='color:red'>ไม่อนุมัติ (งบส่วนกลาง)</span>";
      break;
    case '40':
      return "เปิดรับสมัคร";
      break;
    default:
      // code...
      break;
  }
}

function statusStudentText($statusID) {
  switch ($statusID) {
    case '0':
      return "รอตอบรับ";
      break;
    case '1':
      return "รับทราบ";
      break;
    case '2':
      return "ขอไม่เข้าร่วม";
      break;
    case '3':
      return "ยืนยันไม่เข้าร่วม";
      break;
    case '4':
      return "ระงับการอบรม";
      break;
    default:
      // code...
      break;
  }
}
?>
