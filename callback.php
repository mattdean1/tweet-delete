<?php
require "init.php";

//***delete tweets
foreach ($_POST['tweet'] as $id){
  $statuses = $connection->post("statuses/destroy", array('id' => $id, 'trim_user' => 'true'));
}
//***redirect
header("Status: 301 Moved Permanently");
    header("Location:./index.php?maxid=".$_POST['maxid']);
    exit;
?>
