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

$email = hello_church_get_email($_POST['id']);
$recipient = $_POST['recipients'];

$church = hello_church_church(true);
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
		$emailContent .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
                    <tbody>
                      <tr>
                        <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
                          <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                            <tbody>
                              <tr>
                                <td style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; border-radius: 4px; text-align: center; background-color: #142c8e;" valign="top" align="center" bgcolor="#0867ec"> <a href="'.$item.'" target="_blank" style="border: solid 2px #142c8e; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 16px; font-weight: bold; margin: 0; padding: 12px 24px; text-decoration: none; text-transform: capitalize; background-color: #142c8e; border-color: #142c8e; color: #ffffff;">Click Here</a> </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>';
	}
	
}
	
	$to = $_POST['recipients'];
	$message = $emailContent;

	echo 'Sent!';
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
	     'sender' => ['name' => 'Hello Church', 'email' => 'no-reply@hellochurch.tech'],
	     'replyTo' => ['name' => $church['churchName'], 'email' => $church['churchEmail']],
	     'to' => [[ 'email' => $recipient ]],
	     'htmlContent' => $template,
	     'params' => ['emailSubject' => $subject, 'emailContent' => $emailContent, 'senderPostalAddress' => $senderPostalAddress]
	]);
	
	try {
	    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
	    print_r($result);
	} catch (Exception $e) {
	    echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
	}
	

?>