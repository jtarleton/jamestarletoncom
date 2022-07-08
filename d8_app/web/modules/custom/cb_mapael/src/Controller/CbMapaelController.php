<?php

namespace Drupal\cb_mapael\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_mapael routes.
 */
class CbMapaelController extends ControllerBase {

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
