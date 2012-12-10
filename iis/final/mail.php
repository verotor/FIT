<?php

require_once 'classes/formparser/formparser.class.php';

class MailInfo extends FormParser
{
  public function __construct()
  {
    parent::__construct('MailInfo');
  }

  protected function validateData() {}
  protected function readData() {}
  protected function saveData() {}
  protected function updateData() {}
  protected function deleteData() {}

  protected function createResult($admin) {}
  protected function createResultOne($admin) {}
  protected function createAdditionals($admin) {}

  public function load() {}

  public function sendMails()
  {
    if ($stmt = $this->dbc->query(
      "SELECT reader.reader_email, title.title_title, title.title_subtitle "
      ."FROM reader, reservation, title "
      ."WHERE reservation.reader_id = reader.reader_id"
      ."  AND reservation.title_id = title.title_id"
      ."  AND title.title_copycountavail > 0"))
    {
      //$this->langs = array('cz' => array('name' => 'Čeština'));

      foreach ($stmt->fetch_all_array() as $item)
      {
        //$this->formdata = $stmt->fetch_row();
        print_r($stmt->fetch_all_array());

        //if (! mail($item[0], "Title ".$item[1]." available",
        //  "Title ".$item[1]." ".$item[2]." is available for borrowing."))
        //{
        //  print "<h1>Sending mail to ".$item[0]." failed!</h1>";
        //}
        ////FIXME debug
        //else print "<h1>OK, email sent!</h1>";
      }
    }
    else
    {
      print "<h1>No copies available (no mails send).</h1>";
    }

    /* 4 days before the borrow expires */
    if ($stmt = $this->dbc->query(
      "SELECT reader.reader_email, title.title_title, title.title_subtitle "
      ."FROM reader, borrow, copy, title "
      ."WHERE borrow.reader_id = reader.reader_id"
      ."  AND borrow.copy_id = copy.copy_id"
      ."  AND copy.title_id = copy.title_id"
      ."  AND borrow.borrow_to = DATE_ADD(".date("Y-m-d").", INTERVAL 4 DAY"))
    {
      //print "Hello, your borrow of "$item[1]." ".$item[2]." expires in 4 days.".
      //" Please don't forget to return your borrowed title in time, otherwise you will penalized.";
      print "ahoj";
    }
    else
    {
      print "<h1>No copies available (no mails send).</h1>";
    }
  }
}

$mailer = new MailInfo();
$mailer->sendMails();

?>
