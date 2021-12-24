<?php

namespace Drupal\cb_health\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_health routes.
 */
class CbHealthController extends ControllerBase {

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
