<?php

namespace Drupal\nyc_custom_updates\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Database\Driver\mysql\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\nyc_custom_updates\Utils\NycCommentUpdater;

/**
 * Returns responses for nyc_custom_updates routes.
 */
class NycCustomUpdatesController extends ControllerBase {

  /**
   * Builds the response.
   * this simply displays a link to the batch job
   */
  public function build() {

  $routeName = 'nyc_custom_updates.batch';
  $routeParameters = [];
  $url = \Drupal::url($routeName, $routeParameters);

    $link = "<a href=\"$url\">Run comment location summary batch update</a>";
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t($link),
    ];

    return $build;
  }

}