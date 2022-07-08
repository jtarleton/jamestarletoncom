<?php

namespace Drupal\cb_textmessage\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_textmessage routes.
 */
class CbTextmessageSentController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

$request = \Drupal::request();
$session = $request->getSession();

$markup = [
	'<div role="success">OK - your text message has been sent.</div>',
	$session->get('smsMessage'),
        ];

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t(implode('<br />', $markup)),
    ];

    return $build;
  }

  /**
   * Builds the response.
   */
  public function sentAnyone() {
	$request = \Drupal::request();
	$session = $request->getSession();

	$markup = [ '<div role="success">OK - your text message has been sent.</div>',
        $session->get('smsMessage'),
        ];

    	$build['content'] = [
      		'#type' => 'item',
      		'#markup' => $this->t(implode('<br />', $markup)),
    	];
    	return $build;
  }
}
