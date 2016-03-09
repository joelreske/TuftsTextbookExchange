<?php

function make_offer(){

  include 'db_info.php';
  include 'settings.php';

  $conn = new mysqli($servername, $username, $password, $dbname);        
  $sql =<<<REQ
  SELECT listing.title, user.user_id, user.first, user.last, user.email FROM listing INNER JOIN user ON listing.seller_id = user.user_id WHERE listing.listing_id = '{$_POST['bookID']}' 
REQ;

  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  
  /* Construct a message to send */ 
  $message =<<<MES
    {$_SESSION['user_name']} made an offer for your book with ID = '{$_POST['bookID']}'.

    Their message is:
    '{$_POST['message']}'

    Their offer is:
    ${$_POST['price']}

    <a href="http://tufts-textbook-exchange.com/responce.php?BOOK_ID={$_POST['bookID']}&BUYER_ID={$_SESSION['user_id']}">Click here to accept this offer.</a>
MES;

  /* Reroute to test address if $DEV */
  if ($DEV){
    sendOfferMail("First", "Last", "joelreske@gmail.com", $message);
  }else{
    sendOfferMail($row['first'], $row['last'], $row['email'], $message);
  }

  /* Add offer into DB of all offers */
  $sql =<<<REQ
    INSERT INTO offer (book_id, buyer_id, amount)
    VALUES ({$_POST['bookID']}, '{$row['user_id']}', {$_POST['price']})
REQ;

  echo $sql;
  $result = $conn->query($sql);
}

/* This function handles the attachment of appropriate headers and actual sending of mail.*/
function sendOfferMail($first, $last, $email, $message){

  $message = wordwrap($message, 70, "\r\n");
  $to =<<<EMA
    '{$first} {$last}' <{$email}>
EMA;

  echo $to;

  $subject = 'Someone Made an Offer on your Textbook!';
  $headers = "From: " . "'Tufts Textbook Exchange' <no-reply@tufts-textbook-exchange.com>" . "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

  mail($to, $subject, $message, $headers);

}