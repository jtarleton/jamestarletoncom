<?php

namespace Drupal\cb_geoip\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_geoip routes.
 */
class CbGeoipController extends ControllerBase {

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
