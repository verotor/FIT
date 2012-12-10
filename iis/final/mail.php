<?php

require_once 'classes/utilities/common.class.php';
Common::setNewLineEscape();
require_once 'config/db_connect.php';
require_once 'classes/db/db_connector.class.php';
require_once 'classes/formparser/formparser.class.php';

class MailInfo extends FormParser
{
  public function __construct()
  {
    parent::__construct('MailInfo');
    $this->setDBC(new DB_Connector());
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
      ."  AND reader.reader_email IS NOT NULL"
      ."  AND title.title_copycountavail > 0"))
    {
      foreach ($stmt->fetch_all_array() as $item)
      {
        print "<p>Sending email to ".$item[0]." ...<br>";

        if (! mail($item[0], "Reserved title available for borrow [".$item[1]."]" ,
          "Title ".$item[1]." ".$item[2]." is available for borrowing."))
        {
          print "Sending failed!</p>";
        }
        else
        {
          print "Email successfully sent.</p>";
        }
      }
    }
    else
    {
      print "<h3>No suitable reservations available (no mails send).</h3>";
    }

    /* 4 days before the borrow expires */
    if ($stmt = $this->dbc->query(
      "SELECT reader.reader_email, title.title_title, title.title_subtitle "
      ."FROM reader, borrow, copy, title "
      ."WHERE borrow.reader_id = reader.reader_id"
      ."  AND borrow.copy_id = copy.copy_id"
      ."  AND copy.title_id = copy.title_id"
      ."  AND reader.reader_email IS NOT NULL"
      ."  AND borrow.borrow_to = DATE_ADD(".date("Y-m-d").", INTERVAL 4 DAY"))
    {
      foreach ($stmt->fetch_all_array() as $item)
      {
        print "<p>Sending email to ".$item[0]." ...<br>";

        if (! mail($item[0], "Your borrow expires in 4 days [".$item[1]."]",
          "Your borrow of the title ".$item[1]." ".$item[2]." expires in 4 days.\n".
          "Please do not forget to return the copy back in time to avoid penalization."))
        {
          print "Sending failed!</p>";
        }
        else
        {
          print "Email successfully sent.</p>";
        }
      }
    }
    else
    {
      print "<h3>No borrows available (no mails send).</h3>";
    }

    $this->dbc->databaseReport();
  }
}

$mailer = new MailInfo();
$mailer->sendMails();

?>
