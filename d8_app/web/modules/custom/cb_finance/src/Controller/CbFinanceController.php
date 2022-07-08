<?php

namespace Drupal\cb_finance\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_finance routes.
 */
class CbFinanceController extends ControllerBase {

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
