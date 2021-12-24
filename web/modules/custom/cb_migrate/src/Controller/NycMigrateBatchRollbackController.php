<?php

namespace Drupal\nyc_migrate\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Database\Driver\mysql\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\nyc_migrate\Utils\NycMigrateUpdater;
use Drupal\nyc_migrate\Entity\Overviewpage;

/**
 * Returns responses for nyc_custom_updates routes.
 */
class NycMigrateBatchRollbackController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    self::executeBatchProcess();
    $routeName = 'nycmigrate.example';
    $routeParameters = []; //['node' => $nodeId];
    $url = \Drupal::url($routeName, $routeParameters);
    return batch_process($url);
  }

  public static function executeBatchProcess() {
    $updater = new NycMigrateUpdater;
    $batch_size = 5;


    $nyc_custom_updates_config = \Drupal::config('nyc_migrate.nycmigratecconfiguration');
    $config_batch_size = $nyc_custom_updates_config->get('batchsize');

    if ($config_batch_size >= 1) {
      $batch_size = (int) $config_batch_size;
    }

    $limit = 10000;
    $offset = 0;

    $batch_operations = [];

    $target_content_types = [
      'video',
      'overview_page',
      'list_page',
      'project_link',
      'events',
      'document'
    ];
    $node_batches = [];
    $storage_handler = \Drupal::entityTypeManager()->getStorage("node");
    foreach ($target_content_types as $target_content_type) {

      $ct = $updater->getRollbackCt($target_content_type);
      $success = [];
      $results = [];
      $node_batches = [];
      $target_ids = [];

      $target_ids = $updater->getRollback(FALSE, $target_content_type);

      while ($offset < (int) $ct && ((int) $ct < $limit)) {
        if (!empty($target_ids)) {
          $node_storage = \Drupal::entityTypeManager()->getStorage('node');
          $batch_node_ids_slice = array_slice($target_ids, $offset, $batch_size);
          $offset += $batch_size;
          //\Drupal::logger('nyc_migrate')->notice('Batch size '. $batch_size. '  batch_node_ids_slice ' . var_export($batch_node_ids_slice, TRUE) );
          try {
            $node_batch = $node_storage->loadMultiple($batch_node_ids_slice);
          }
          catch (Exception $e) {
            \Drupal::logger('nyc_migrate')->notice('Node storage loadMultiple exception ' . $e->getMessage() . ' ' . var_export($node_batch, TRUE));
          }
          //\Drupal::logger('nyc_migrate')->notice('Loaded node operation count ' . count($node_batch) );
          $batch_operations[] = [
            '\Drupal\nyc_migrate\Service\NycMigrateBatchService::nyc_migrate_do_rollback',
            [$node_batch]
          ];
        }
      }
    }
    $batch = array(
      'title' => t('Execute Migration Update'),
      'operations' => $batch_operations,
      //'finished' => self::nyc_migrate_do_update_finished_callback($success, $results, $operations),
      'init_message' => t('Starting.'),
      'progress_message' => t('Processed @current out of @total batches. Estimated time: @estimate.'),
      'error_message' => t('The process has encountered an error.'),
      'file' => drupal_get_path('module', 'nyc_migrate') . '/nyc_migrate.batch.inc',
    );
    batch_set($batch);
    return count($target_ids);
  }

}
