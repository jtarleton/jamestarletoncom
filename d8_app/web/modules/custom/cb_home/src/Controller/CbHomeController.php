<?php

namespace Drupal\cb_home\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_home routes.
 */
class CbHomeController extends ControllerBase {

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
