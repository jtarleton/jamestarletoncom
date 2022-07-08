<?php

namespace Drupal\cb_textmessage\Controller;

use Drupal\Core\Controller\ControllerBase;
use PHPMailer\PHPMailer;
/**
 * Returns responses for cb_textmessage routes.
 */
class CbTextmessageController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
