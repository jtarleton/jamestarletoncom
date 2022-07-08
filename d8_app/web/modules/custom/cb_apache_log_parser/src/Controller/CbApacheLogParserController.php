<?php

namespace Drupal\cb_apache_log_parser\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for cb_apache_log_parser routes.
 */
class CbApacheLogParserController extends ControllerBase {

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
