<?php

require '../../../vendor/autoload.php';
include('../../../secrets.php');

send_link(perch_get('c'), perch_get('e'));

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
		     'sender' => ['name' => 'Hello Church', 'email' => 'email@hellochurch.tech'],
		     'replyTo' => ['name' => 'Hello Church', 'email' => 'email@hellochurch.tech'],
		     'to' => [[ 'email' => $email ]],
		     'htmlContent' => '<html><body>'.$emailContent.'</body></html>',
		]);
		
		try {
		    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
		    print_r($result);
		} catch (Exception $e) {
		    echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
		}
?>