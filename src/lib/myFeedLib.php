<?php
function getFeed($feed_url,$limit=1) {
  try {
    $content = @file_get_contents($feed_url);
    $xml = new SimpleXmlElement($content);
    $namespaces = $xml->getNamespaces(true);
    // echo "<ul>";
    $row=0;
    foreach($xml->entry as $entry) {
      $media = $entry->children($namespaces['media']);
      $mediathumbnail=$media->thumbnail[0]->attributes();
      $thumbnailurl=$mediathumbnail['url'];
      // echo "<li>";
      $date = DateTime::createFromFormat(DATE_ATOM, $entry->updated);
      $date -> setTimeZone(new DateTimeZone('Asia/Bangkok'));
      $dateUpdate=$date->format('d-m-Y H:i');
      ?>
      <div class="row">
        <div class="col-sm-1" style="width:50px">
          <img src="<?php echo $thumbnailurl;?>" title="<?php echo $entry->author->name;?>" width='50px' height='50px'>
        </div>
        <div class="col-sm-10">
          <div class="panel panel-info" style="width:600px;">
            <div class="panel-heading"style="padding:2px;">
              &nbsp;<?php echo $dateUpdate?>
            </div>
            <div class="panel-body" style="padding:2px">
              <div class="col-sm-10">
                <?php
                  $summary=nl2br($entry->summary);
                  echo $entry->title."<br/>".$summary;
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
      $row++;
      if($row==$limit) {
        break;
      }
    }
  } catch (Exception $e)   {
    echo "<div class='alert alert-primary' role='alert'>Cannot connect gitlab</div>";
  }
}
?>
