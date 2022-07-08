<?php

namespace Drupal\nyc_migrate\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Database\Driver\mysql\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\nyc_migrate\Utils\NycMigrateUpdater;

/**
 * Returns responses for nyc_custom_updates routes.
 */
class NycMigrateController extends ControllerBase {

  /**
   * Builds the response.
   * this simply displays a link to the batch job
   */
  public function build() {
    $routeName = 'nycmigrate.batchoverview';
    $routeParameters = [];
    $url = \Drupal::url($routeName, $routeParameters);

    $link = "<a href=\"$url\">Run overview migration batch process</a><br />Source: Project > field_overview_header<br />Target: Overview > field_header</a><br />";


    $routeName = 'nycmigrate.batchdocuments';
    $routeParameters = [];
    $url = \Drupal::url($routeName, $routeParameters);

    $link .= "<a href=\"$url\">Run documents list page batch process</a><br />Source: Project > field_presentations_header<br />Target: List Page, type: Document > field_header</a><br />";

    $routeName = 'nycmigrate.batchevents';
    $routeParameters = [];
    $url = \Drupal::url($routeName, $routeParameters);

    $link .= "<a href=\"$url\">Run events list page batch process</a><br />Source: Project > field_events_header<br />Target: List Page, type: Events > field_header</a><br />";

    $routeName = 'nycmigrate.batchvideos';
    $routeParameters = [];
    $url = \Drupal::url($routeName, $routeParameters);

    $link .= "<a href=\"$url\">Run videos list page batch process</a><br />Source: Project > field_videos_header<br />Target: List Page, type: Videos > field_header</a><br />";
    $routeName = 'nycmigrate.batchdocs';
    $routeParameters = [];
    $url = \Drupal::url($routeName, $routeParameters);
    $link .= "<a href=\"$url\">Run documents batch process</a><br />Source: Project > field_project_documents<br />Target: Document > field_document</a><br />";


    $routeName = 'nycmigrate.batchvideo';
    $routeParameters = [];
    $url = \Drupal::url($routeName, $routeParameters);
    $link .= "<a href=\"$url\">Run video content item batch process</a><br />Source: Project > field_project_videos<br />Target: Video > field_header</a><br />";

    $routeName = 'nycmigrate.batchsurvey';
    $routeParameters = [];
    $url = \Drupal::url($routeName, $routeParameters);
    $link .= "<a href=\"$url\">Run survey content item batch process</a><br />Source: Project > field_project_survey<br />Target: Survey > field_header</a><br />";


    $routeName = 'nycmigrate.batchlinks';
    $routeParameters = [];
    $url = \Drupal::url($routeName, $routeParameters);
    $link .= "<a href=\"$url\">Run links content item batch process</a><br />Source: Project > field_project_links<br />Target: Project Links > field_header</a><br />";


    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t($link),
    ];
    return $build;
  }

}
