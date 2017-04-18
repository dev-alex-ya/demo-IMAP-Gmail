<?php
require_once 'entities/cmdArgsParcer.php';

$argv;



set_time_limit(3000);

$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'somebody@gmail.com'; # например somebody@gmail.com

$password = 'qwerty';

$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
$pathToSave = "/home/alex/Public/mail/";


$since = '';
$before = '';

$emails = imap_search($inbox,'SINCE "1 Apr 2017" BEFORE "13 Apr 2017"');
//$emails = imap_search($inbox,'ALL');
$max_emails = 500;

if($emails) {

    $count = 1;
     rsort($emails);
     foreach($emails as $email_number)
    {
        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox, $email_number, 0);

        /* get mail message */
        $message = imap_fetchbody($inbox, $email_number, 2, FT_PEEK);

        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);

        $attachments = array();

        /* if any attachments found... */
        if(isset($structure->parts) && count($structure->parts))
        {
            for($i = 0; $i < count($structure->parts); $i++)
            {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );

                if($structure->parts[$i]->ifdparameters)
                {
                    foreach($structure->parts[$i]->dparameters as $object)
                    {
                        if(strtolower($object->attribute) == 'filename')
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                if($structure->parts[$i]->ifparameters)
                {
                    foreach($structure->parts[$i]->parameters as $object)
                    {
                        if(strtolower($object->attribute) == 'name')
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }

                if($attachments[$i]['is_attachment'])
                {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);

                    /* 4 = QUOTED-PRINTABLE encoding */
                    if($structure->parts[$i]->encoding == 3)
                    {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 3 = BASE64 encoding */
                    elseif($structure->parts[$i]->encoding == 4)
                    {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }

        /* iterate through each attachment and save it */
        foreach($attachments as $attachment)
        {
            if($attachment['is_attachment'] == 1)
            {
                $filename = $attachment['name'];
                if(empty($filename)) $filename = $attachment['filename'];

                if(empty($filename)) $filename = time() . ".dat";

                /* prefix the email number to the filename in case two emails
                 * have the attachment with the same file name.
                 */
                $fp = fopen($pathToSave . $email_number . "-" . $filename, "w+");
                echo 'Сохранено: ' . $pathToSave . $email_number . "-" . $filename . "\n";
                fwrite($fp, $attachment['attachment']);
                fclose($fp);
            }

        }

        //if($count++ >= $max_emails) break;
    }

}

/* close the connection */
imap_close($inbox);

echo "Done\n";
