<?php

namespace Drupal\cb_maps\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_maps routes.
 */
class CbMapsController extends ControllerBase {

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
