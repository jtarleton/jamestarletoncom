<?php

namespace Drupal\cb_automobile\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_automobile routes.
 */
class CbAutomobileController extends ControllerBase {

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
