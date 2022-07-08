<?php

namespace Drupal\cb_textmessage\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Configure cb_textmessage settings for this site.
 */
class SmsTextmessageAnyoneForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cb_textmessage_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cb_textmessage.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
	  
	  $carriers = [
    'T-Mobile' => 'number@tmomail.net',
    'Virgin Mobile' => 'number@vmobl.com',
    'AT&T' => 'number@txt.att.net',
    'Sprint' => 'number@messaging.sprintpcs.com',
    'Verizon' => 'number@vtext.com',
    'Tracfone' => 'number@mmst5.tracfone.com',
    'Ting' => 'number@message.ting.com',
    'Boost Mobile' => 'number@myboostmobile.com',
    'U.S. Cellular' => 'number@email.uscc.net',
    'Metro PCS' => 'number@mymetropcs.com'
		];
	  
	  $carriers = array_flip($carriers);
	  $form['sms']['carrier'] = [
	    '#type' => 'select',
     '#type' => 'select',
      '#title' => $this->t('Carrier'),
      '#required' => TRUE,
'#options'  => $carriers,
      '#default_value' => $this->config('cb_textmessage.settings')->get('carrier'),
    ];
    $form['sms']['smsMessage'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#default_value' => $this->config('cb_textmessage.settings')->get('smsMessage'),
    ];
    
    $form['sms']['phoneNumber'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone Number'),
      '#default_value' => $this->config('cb_textmessage.settings')->get('phoneNumber'),
    ];

    $form['sms']['robotChallenge'] = [
      '#type' => 'textfield',
      '#title' => $this->t('What is 3 + 4?'),
      '#description' => '<p style="color:#ccc; font-size:8px;">Math challenge to deter robots (hint: less than 9)</p>',
      '#default_value' => '9',
    ];

        // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form. This is not required, but is convention.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];



    return $form; // parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ((int)$form_state->getValue('robotChallenge') != 7) {
    	$form_state->setErrorByName('robotChallenge', $this->t('The robotChallenge answer is not correct.'));
    }
    return parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {








// Instantiate Class
$mail = new PHPMailer();
 
// Set up SMTP
$mail->IsSMTP();                // Sets up a SMTP connection
$mail->SMTPDebug  = 2;          // This will print debugging info
$mail->SMTPAuth = true;         // Connection with the SMTP does require authorization
$mail->SMTPSecure = "tls";      // Connect using a TLS connection
$mail->Host = "smtp.elasticemail.com";
$mail->Port = 2525;
$mail->Encoding = '7bit';       // SMS uses 7-bit encoding
$mail->isHTML(true); 
// Authentication
$mail->Username   = "crystalbit-us@protonmail.com"; // Login
$mail->Password   = '1A34E344B4B2109EDF09E2A791BD3CA548CB'; // "9kQvqmTXGGidzWx"; // Password
$from = $mail->Username;


$request = \Drupal::request();
$session = $request->getSession();
foreach(['robotChallenge', 'carrier','smsMessage','phoneNumber'] as $k=>$v) {

$session->set($v, $form_state->getValue($v) );
}
$session->set('subject', 'Text from website');
$session->save();
// Compose
$mail->Subject = $session->get('subject');     // Subject (which isn't required)
$mail->Body = $session->get('smsMessage');        // Body of our message
$subject  = $mail->Subject;
$body = $mail->Body;
$to = str_replace('number', $session->get('phoneNumber'),  $session->get('carrier'));

// Send 
$mail->AddAddress( $to ); // Where to send it

try {
    
 // https://api.elasticemail.com/v2/sms/send?
	// apikey=your-apikey &to=%2b 100 000 000 &body=text_body_of_your_message

	$httpparams = [
		'subject'=>$subject,
		'from'=>$from,
		'fromName'=>'Crystalbit Mailer',
		'sender'=>$from,
		'senderName'=>'Crystalbit Mailer',
		'msgFrom'=>$from,
		'msgFromName'=>'Crystalbit Mailer',
		'replyTo'=>'crystalbit-us@protonmail.com',
		'replyToName'=>'Crystalbit Mailer',
		'to'=>$to,
		'msgTo'=>$to,
		'msgCC'=>'',
		'msgBcc'=>'',
		'lists'=>'',
		'segments'=>'',
		'mergeSourceFilename'=>'',
		'dataSource'=>'',
		'channel'=>'',
		'bodyHtml'=>'<p>'. $body .'</p>',
		'bodyText'=>$body,
		'charset'=>'',
		'charsetBodyHtml'=>'',
		'charsetBodyText'=>'',
		'encodingType'=>1,
		'template'=>'',
		'headers_firstname'=>'firstname: myValueHere',
		'postBack'=>'',
		'merge_firstname'=>'Bob',
		'timeOffSetMinutes'=>'',
		'poolName'=>'My Custom Pool',
		'isTransactional'=>'false',
		'attachments'=>'',
		'trackOpens'=>'true',
		'trackClicks'=>'true',
		'utmSource'=>'source1',
		'utmMedium'=>'medium1',
		'utmCampaign'=>'campaign1',
		'utmContent'=>'content1',
		'bodyAmp'=>'',
		'charsetBodyAmp'=>''	
	];
$httpparams['apikey'] = '24928C123DA08B5AE8012535C0B842CCAEDB514E0FB117972F278AE2A917039F8389467C21B9C3C57353F44B25F06C8B';
$client = \Drupal::httpClient();
$mailurl = 'https://api.elasticemail.com/v2/email/send';
$response = $client->post($mailurl, [
      'verify' => false,
      'form_params' => $httpparams,
        'headers' => [
          'Content-type' => 'application/x-www-form-urlencoded',
        ],
]);

if ($response->getStatusCode() == '200') {
	$data = $response->getBody()->getContents();
	$notice = implode(' ',json_decode( $data , true));
	\Drupal::logger('cbtextmessage')->notice($notice);
}



	$mail->send();

    $this->messenger()->addMessage($this->t('Form message.'));
    \Drupal::messenger()->addMessage("Message has been sent successfully");
\Drupal::logger('cbtextmessage')->error('Mail sent ok'); 

} catch (Exception $e) {
	\Drupal::messenger()->addMessage("Mailer Error: " . $mail->ErrorInfo);
\Drupal::logger('cbtextmessage')->error($mail->ErrorInfo); 
}


//$this->config('cb_textmessage.settings')
    //  ->set('example', $form_state->getValue('example'))
	  //  ->save();
	  //
	  //
	  //
	  //
	  //
  $dest_url = "/cb-textmessage/sent-anyone";
  $url = Url::fromUri('internal:' . $dest_url);

   // $form_state->setRedirect('route', $args, $options);
$form_state->setRedirectUrl($url);

   // parent::submitForm($form, $form_state);
  }

}
