<?php
require "init.php";

//***verify cred query
//$content = $connection->get("account/verify_credentials");

if(isset($_GET['maxid'])){
  $maxid = $_GET['maxid'];
  //***get tweets
  $content = $connection->get("statuses/user_timeline", array(
                                                              'user_id' => $user_id,
                                                              'count' => '10',
                                                              'trim_user' => 'true',
                                                              'max_id' => $maxid
                                                            ));
}
else{
  $content = $connection->get("statuses/user_timeline", array(
                                                            'user_id' => $user_id,
                                                            'count' => '10',
                                                            'trim_user' => 'true'
                                                          ));
}
//manipulate tweets to create json string for api
$jsonarr = [];
foreach($content as $tweet){

  $jsonarr[] = array('text' => $tweet->text, 'id' => $tweet->id);
}
$data = array('data' => $jsonarr);
$jsonstring = json_encode($data);

//send to api and record response
$url = "http://www.sentiment140.com/api/bulkClassifyJson?appid=".$sentiment_email;

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $jsonstring);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

//decode and match to tweets
$negdata = json_decode($result);

?>
<!DOCTYPE html>
<html>
  <head>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
      <link href="style.css" rel="stylesheet" type="text/css" />
      <title>tweet.delete</title>
  </head>

  <body>
    <div class="container-fluid">
      <a href="home.php">
      <div class="header">
        <h1><i class="fa fa-trash-o"></i> tweet.delete</h1>
      </div>
    </a>
    </div>

    <div class="container">
    <div id="tweets">
      <form action="callback.php" method="post">
       <?php
       foreach($negdata->data as $tweet){
         if($tweet->polarity<=2){
        ?>
          <div>
            <div class='tweet'> <?php echo $tweet->text; ?></div>
              <div style='display:inline-block'>
                <label class='btn btn-danger' type='button'>
                  <input type='checkbox' name='tweet[]' value='<?php echo $tweet->id; ?>'>
                  Delete
                </label>
              </div>
          </div>
        <?php
        }
       }
       ?>

       <div>
         <div class="tweet" style="border: 0px;"></div>
         <div style='display:inline-block'>
           <button class="btn btn-success" type="submit">Submit</button>
         </div>
       </div>
    </div>
    <input type="hidden" name="maxid" value="<?php echo end($content)->id_str; ?>">

</div>

    </form>
  </body>

</html>
