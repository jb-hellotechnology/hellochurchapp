<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require '../../../vendor/autoload.php';
require '../../../secrets.php';

$template = file_get_contents('../../../email_template.html');

if(!perch_member_logged_in()){
	header("location:/");
}

$email = hello_church_get_email($_POST['email_id']);
$church = hello_church_church(true);

if(!$_POST['recipient']){
	
	$recipients = array();

	$contacts = json_decode($_POST['contacts']);
	foreach($contacts as $contact){
		$recipients[] = $contact->id;
	}
	
	$groups = json_decode($_POST['groups']);
	foreach($groups as $group){
		$members = process_search_members_list($group->id);
		foreach($members as $member){
			$recipients[] = $member['contactID'];
		}
	}
	
	$bcc = array();
	
	foreach(array_unique($recipients) as $contact){
		$contact = hello_church_contact($contact);
		$bcc[] = (object) array('email' => $contact->contactEmail());
	}
	
	$to = array();
	$to[] = (object) array('email' => $church['churchEmail']);
	
}else{
	
	$to = array();	
	$to[] = (object) array('email' => $_POST['recipient']);
	
}

$senderPostalAddress = "$church[churchName], $church[churchAddress1], $church[churchCity], $church[churchCountry]";

$subject = $email['emailSubject'];
$email = json_decode($email['emailContent'], true);

$emailContent = '';
foreach($email as $type => $item){

	$typeParts = explode("_", $type);
	$type = $typeParts[0];
	
	if($type=='heading'){
		$emailContent .= '<h2 style="font-family: Helvetica, sans-serif; font-size: 24px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$item.'</h2>';
	}
	if($type=='text'){
		$emailContent .= '<p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">'.nl2br($item).'</p>';
	}
	if($type=='youtube'){
		$emailContent .= $item;
	}
	if($type=='bible'){
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, 'https://api.esv.org/v3/passage/html/?include-footnotes=false&q='.urlencode($item));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		
		
		$headers = array();
		$headers[] = 'Authorization: Token 0aa221b3e0dbb4ca9d1abe0438ceac27e2b81cee';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$resultESV = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		$json = json_decode($resultESV,true);
		$passage = strip_tags($json['passages'][0],"<p><a>");
		$emailContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;">'.$passage.'</div>';

	}
	if($type=='link'){
		
		if($typeParts[2]=='text'){
			$buttonText = $item;	
		}else{

			$emailContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
                    <tbody>
                      <tr>
                        <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
                          <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                            <tbody>
                              <tr>
                                <td style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="'.$item.'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">'.$buttonText.'</a> </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>';
        
        }
	}
	if($type=='image'){
		$image = hello_church_file($item);
		$emailContent .= '<img src="https://app.hellochurch.tech/feed/file-image/'.$image['churchID'].'/'.$image['fileID'].'" alt="Image" style="margin-bottom:16px;" />';
	}
	if($type=='file'){
		$file = hello_church_file($item);
		$emailContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
                    <tbody>
                      <tr>
                        <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
                          <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                            <tbody>
                              <tr>
                                <td style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="https://app.hellochurch.tech/feed/file/'.$file['churchID'].'/'.$file['fileID'].'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">Download: '.$file['fileName'].'</a> </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>';
	}
	if($type=='event'){
		$parts = explode("_", $item);
		$event = hello_church_event($parts[0]);
		$tParts = explode(" ", $parts[1]);
		$dParts = explode("-", $tParts[0]);
		$timeStamp = "$dParts[2]/$dParts[1]/$dParts[0] $tParts[1]";
		$emailContent .= '<div style="font-family: Helvetica, sans-serif !important; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;background:#f0f2f9;padding:16px;"><h2 style="font-family: Helvetica, sans-serif; font-size: 20px; font-weight: strong; margin: 0; margin-bottom: 16px;">'.$event->eventName.'</h2><p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; font-style:italic; margin: 0; margin-bottom: 16px;">'.$timeStamp.'</p><p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0;">'.nl2br($event->eventDescription).'</p></div>';

	}
	
}

$message = $emailContent;

// Configure API key authorization: api-key
$config = Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $brevoAPI);

$apiInstance = new Brevo\Client\Api\TransactionalEmailsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail([
	'subject' => $subject,
	'sender' => ['name' => $church['churchName'], 'email' => 'no-reply@hellochurch.tech'],
	'replyTo' => ['name' => $church['churchName'], 'email' => $church['churchEmail']],
	'to' => $to,
	'bcc' => $bcc,
	'htmlContent' => $template,
	'params' => ['emailSubject' => $subject, 'emailContent' => $emailContent, 'senderPostalAddress' => $senderPostalAddress]
]);

try {
    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
    hello_church_store_email_result($_POST['email_id'], $result);
    if($recipients){
		hello_church_log_email_contact($_POST['email_id'], array_unique($recipients));    
    }
} catch (Exception $e) {
    echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
}
	

?>