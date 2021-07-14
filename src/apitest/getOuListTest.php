<?php
include_once("../lib/myOAuth.php");
include_once("../lib/chkLogin.php"); //If need login enable here
$apiURL="https://app.jasmine.com/jpmapi/ou/details/all";
$token=$_SESSION["access_token"];
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../lib/DataTables/datatables.min.css">
</head>
<body>
<?php
$response=$json;
$NewOu=array();
$keyOu=0;

if ($token=="") { //No Login no show
    $error_arr=array('code'=>$httpcode,'message'=>'Please login single sign-on');
    $error_json = json_encode($error_arr);
    echo $error_json;
    exit;
}
$headers = array('token: '.$token.'',
            'Content-Type: application/json; charset=UTF-8');
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $apiURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if($httpcode==200) {
      $responseArray=json_decode($response,true);
      $ouListArray=array();
      foreach ($responseArray as $key=>$value) {
          $NewOu[$keyOu]['code']  =$value['code'];
          $NewOu[$keyOu]['name'] = $value['name'];
          $NewOu[$keyOu]['head']  =$value['head_of_onit'];
          if($value['org_unit_type']['name']!='Section'){
            $NewOu[$keyOu]['Section'] = "";
            if($value['org_unit_type']['name']!='Division'){
                $NewOu[$keyOu]['Division'] = "";
            }
          }
          $NewOu[$keyOu][$value['org_unit_type']['name']] = $value['name'];
          recursive($value);
          $keyOu++;
      }
      echo "Total OU:".count($NewOu)."<br/>";
      //echo json_encode($NewOu);
      //exit;
      ?>
      <table id='ouList' class='cell-border display compact'>
      <thead>
        <tr>
          <th>Code</th>
          <th>Name</th>
          <th>Head</th>
          <th>Section</th>
          <th>Division</th>
          <th>Department</th>
          <th>Company</th>
        </tr>
      </thead>
      <tbody>
      <?php
      for($i=0;$i<count($NewOu);$i++) {
        echo "<tr>\n";
        echo "<td>".$NewOu[$i]['code']."</td>";
        echo "<td>".$NewOu[$i]['name']."</td>";
        echo "<td>".$NewOu[$i]['head']."</td>";
        echo "<td>".$NewOu[$i]['Section']."</td>";
        echo "<td>".$NewOu[$i]['Division']."</td>";
        echo "<td>".$NewOu[$i]['Department']."</td>";
        echo "<td>".$NewOu[$i]['Company']."</td>";
        echo "</tr>\n";
      }
      echo "</tbody>\n";
      echo "</table>\n";


    } else {
      $error_arr=array('code'=>$httpcode,'message'=>'Cannot connected');
      $error_json = json_encode($error_arr);
      echo $error_json;
    }
} catch (Exception $e) {
    $error_arr=array('code'=>$httpcode,'message'=>'Curl Exception:'.$e->getMessage());
    $error_json = json_encode($error_arr);
    echo $error_json;
}


function recursive($val){
global $NewOu;
global $keyOu;
    if(isset($val['parent'])){
      if($val['parent']['org_unit_type']['name']=="Company") {
        $NewOu[$keyOu][$val['parent']['org_unit_type']['name']] = $val['parent']['short_name'];
      } else {
        $NewOu[$keyOu][$val['parent']['org_unit_type']['name']] = $val['parent']['name'];
      }
      return recursive($val['parent']);
    }
}
?>
<script src="../assets/lib/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="../lib/DataTables/datatables.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  var table = $('#ouList').DataTable({
    "pageLength": 20,
    "scrollX": true,
    "dom": 'frtip'
  });
});
</script>
</body>
</html>
