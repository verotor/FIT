<?php

require_once 'classes/formparser/formparser.class.php';

class MailInfo extends FormParser
{
  private $wtf;

  public function __construct()
  {
    parent::__construct('MailInfo');
  }

  public function sendMail()
  {
    if ($stmt = $this->dbc->query(
      "SELECT reader.reader_email, title.title_title, title.title_subtitle ".
      "FROM reader, reservation, title ".
      "WHERE reservation.reader_id = reader.reader_id".
      "  AND reservation.title_id = title.title_id".
      "  AND title.title_copycountavail > 0"))
    {
      //$this->langs = array('cz' => array('name' => 'Čeština'));

      foreach ($stmt as $item)
      {
        $this->formdata = $stmt->fetch_row();

        if (! mail($item[0], "Title ".$item[1]." available",
          "Title ".$item[1]." ".$item[2]." is available for borrowing."))
        {
          print "<h1>Sending mail to ".$item[0]." failed!</h1>";
        }
        //FIXME debug
        else print "<h1>OK, email sent!</h1>";
      }
    }
    else
    {
      print "<h1>No copies available (no mails send).</h1>";
    }

    /* 4 days before the borrow expires */
    if ($stmt = $this->dbc->query(
      "SELECT reader.reader_email, title.title_title, title.title_subtitle ".
      "FROM reader, borrow, copy, title ".
      "WHERE borrow.reader_id = reader.reader_id".
      "  AND borrow.copy_id = copy.copy_id".
      "  AND copy.title_id = copy.title_id".
      "  AND borrow.borrow_to = DATE_ADD(".date("Y-m-d").", INTERVAL 4 DAY"))
    {
      print "Hello, your borrow of "$item[1]." ".$item[2]." expires in 4 days.".
      " Please return it back in time, otherwise you will penalized.";

    }
    else
    {
      print "<h1>No copies available (no mails send).</h1>";
    }
  }
}

mailer = new MailInfo();
mailer.sendMail();

?>
