<?php

namespace Drupal\cb_command_checker\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_command_checker routes.
 */
class CbCommandCheckerController extends ControllerBase {

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
