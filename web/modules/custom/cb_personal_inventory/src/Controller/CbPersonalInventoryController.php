<?php

namespace Drupal\cb_personal_inventory\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_personal_inventory routes.
 */
class CbPersonalInventoryController extends ControllerBase {

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
