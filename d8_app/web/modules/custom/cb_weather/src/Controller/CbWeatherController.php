<?php

namespace Drupal\cb_weather\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_weather routes.
 */
class CbWeatherController extends ControllerBase {

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
