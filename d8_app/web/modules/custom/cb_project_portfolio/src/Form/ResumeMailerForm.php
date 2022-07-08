<?php

namespace Drupal\cb_project_portfolio\Form;
use GuzzleHttp\Psr7;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use ElasticEmailClient\ElasticClient as ElasticClient;
use ElasticEmailClient\ApiConfiguration as ElasticConfiguration;
use ElasticEmailEnums\File as File;

/**
 * Provides a cb_project_portfolio form.
 */
class ResumeMailerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cb_project_portfolio_resume_mailer';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


	  $form['mailer']['text'] = [
	  
		  '#markup'=>'
Thank you for your interest in my professional background.
		  '
	  ];



    $form['mailer']['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('E-mail Address'),
      '#description' => 'Please enter your recipient e-mail address',
      '#required' => TRUE,
    ];

    $form['mailer']['robotChallenge'] = [
      '#type' => 'textfield',
      '#title' => $this->t('What is 3 + 4?'),
      '#description' => '<p style="color:#ccc; font-size:8px;">Math challenge to deter robots (hint: less than 9)</p>',
      '#default_value' => '9',
    ];





 $form['mailer']['text_profiles'] = [

                  '#markup'=>'
https://schemas.liquid-technologies.com/hr-xml/2007-04-15/

http://microformats.org

http://microformats.org/wiki/h-resume

PROFESSIONAL PROFILES / SOCIAL MEDIA

    LinkedIn
    StackOverflow
    Github
    Vettery
    Hired
    Underdog.io
    Glassdoor
    '
];





    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('email')) < 7) {
      $form_state->setErrorByName('email', $this->t('Email should be at least 7 characters.'));
    }

    if ((int)$form_state->getValue('robotChallenge') != 7) {
        $form_state->setErrorByName('robotChallenge', $this->t('The robotChallenge answer is not correct.'));
    }

        return parent::validateForm($form, $form_state);



  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   
  $from   = "crystalbit-us@protonmail.com"; // Login
  $request = \Drupal::request();
  $session = $request->getSession();
  foreach(['robotChallenge','email'] as $k=>$v) {
    $session->set($v, $form_state->getValue($v) );
  }

  $session->save();
  $subject  = 'Greetings - here is your document ';
  $body = ' test body - pls find attached PDF document.';
  $to =  'jamestarleton@gmail.com';

  /*
   * 	'multipart' => [
        [
            'name'     => 'foo',
            'contents' => 'data',
            'headers'  => ['X-Baz' => 'bar']
        ],
	// 'Content-Type' => 'multipart/form-data'
  */
$file_name = 'My File';
  $tmpf = realpath('/var/www/d8_app/web/sites/default/files/2021-12/jamestarleton-2021-11-15.pdf');
  $file = new \CURLFile($tmpf, 'application/pdf', $file_name);

  $file = new File;
  //$file-> 

  $postfields = [
	'apikey' => '24928C123DA08B5AE8012535C0B842CCAEDB514E0FB117972F278AE2A917039F8389467C21B9C3C57353F44B25F06C8B',
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
	'isTransactional' => false,
	'file_1' => $file
  ];
 $mailurl = 'https://api.elasticemail.com/v2/email/send';
  $curlopts = [
	CURLOPT_URL => $mailurl,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => $postfields,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HEADER => false,
	CURLOPT_SSL_VERIFYPEER => false
  ];
  $client = \Drupal::httpClient();
  
  /* 
  $response = $client->post($mailurl, [
	  'verify' => false,
	'curl' => $curlopts,
  ]);
 */

            $configuration = new ElasticConfiguration([
'apiUrl' => 'https://api.elasticemail.com/v2/',
'apiKey' => '24928C123DA08B5AE8012535C0B842CCAEDB514E0FB117972F278AE2A917039F8389467C21B9C3C57353F44B25F06C8B'
]);
$client = new ElasticClient($configuration);


	$client->Email->Send(
		'test subj', //$subject = null, 
		'crystalbit-us@protonmail.com', //$from = null, 
		'CB Mailer', //$fromName = null, 
		'crystalbit-us@protonmail.com', //$sender = null, 
		'CB Mailer', //$senderName = null, 
		'crystalbit-us@protonmail.com', //$msgFrom = null, 
		'CB Mailer', //$msgFromName = null, 
		'crystalbit-us@protonmail.com', //$replyTo = null, 
		'CB Mailer', //$replyToName = null, 
		['jamestarleton@gmail.com'], //array $to = array(), 
		['jamestarleton@gmail.com'], //array $msgTo = array(), 
		[], //array $msgCC = array(), 
		[], //array $msgBcc = array(), 
		[], //array $lists = array(), 
		[], //array $segments = array(), 
		null, //$mergeSourceFilename = null, 
		null, //$dataSource = null, 
		null, //$channel = null, 
		null, //$bodyHtml = null, 
		null, //$bodyText = null, 
		null, //$charset = null, 
		null, //$charsetBodyHtml = null, 
		null, //$charsetBodyText = null, 
		\ElasticEmailEnums\EncodingType::None, //$encodingType = \ElasticEmailEnums\EncodingType::None, 
		null, //$template = null, 
		[ $tmpf], //array $attachmentFiles = array(), 
		[], //array $headers = array(), 
		null, //$postBack = null, 
		[], //array $merge = array(), 
		null, //$timeOffSetMinutes = null, 
		null, //$poolName = null, 
		false, //$isTransactional = false, 
		[ $tmpf], //array $attachments = array(), 
		null, //$trackOpens = null, 
		null, //$trackClicks = null, 
		null, //$utmSource = null, 
		null, //$utmMedium = null, 
		null, //$utmCampaign = null, 
		null, //$utmContent = null, 
		
		null, //$bodyAmp = null, 
		null //$charsetBodyAmp = null	
	);













  //$response = $client->send($request);
  if ($response->getStatusCode() == '200') {
    $data = $response->getBody()->getContents();
    $notice = implode(' ',json_decode( $data , true));
    \Drupal::logger('cbtextmessage')->notice($notice);
    $this->messenger()->addStatus($this->t('<div role="success">OK - the message has been sent.</div>'));
    $form_state->setRedirect('<front>');
  }  }
}
