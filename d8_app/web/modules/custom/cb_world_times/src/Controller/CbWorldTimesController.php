<?php

namespace Drupal\cb_world_times\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for CB World Times routes.
 */
class CbWorldTimesController extends ControllerBase {

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
