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
class NycMigrateBatchOverviewController extends ControllerBase {

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

  /**
   * Name of an implementation of callback_batch_finished(). This is executed after the batch has 
   * completed. This should be used to perform any result massaging that may be needed, and possibly save 
   * data in $_SESSION for display after final page redirection.
   * @param $success
   * @param $results 
   * @param $operations array
   */
  public static function nyc_custom_updates_do_update_finished_callback($success, $results, $operations) {

    $routeName = 'nycmigrate.example';
    $routeParameters = []; //['node' => $nodeId];
    $url = \Drupal::url($routeName, $routeParameters);
    \Drupal::messenger()->addStatus($message);

    return new RedirectResponse($url);
  }

  public static function executeBatchProcess() {
    $updater = new NycMigrateUpdater;
    $ct = $updater->getSourcesCt();
    $success = [];
    $results = [];
    $node_batches = [];
    $batch_size = 5;

    $nyc_custom_updates_config = \Drupal::config('nyc_migrate.nycmigratecconfiguration');
    $config_batch_size = $nyc_custom_updates_config->get('batchsize');

    if ($config_batch_size >= 1) {
      $batch_size = (int) $config_batch_size;
    }

    $limit = 10000;
    $offset = 0;

    $batch_operations = [];
    $batch_node_ids = $updater->getSources();
    /*
      \Drupal::logger('nyc_migrate')->notice('Node IDs count' . count($batch_node_ids));
      \Drupal::logger('nyc_migrate')->notice('Offset ' . $offset
      .' Ct '. $ct . ' Limit ' . $limit );
     */

    while ($offset < (int) $ct && ((int) $ct < $limit)) {
      if (!empty($batch_node_ids)) {
        $node_storage = \Drupal::entityTypeManager()->getStorage('node');
        $batch_node_ids_slice = array_slice($batch_node_ids, $offset, $batch_size);
        $offset += $batch_size;
        // \Drupal::logger('nyc_migrate')->notice('Batch size '. $batch_size. '  batch_node_ids_slice ' . var_export($batch_node_ids_slice, TRUE) );
        try {
          $node_batch = $node_storage->loadMultiple($batch_node_ids_slice);

          // \Drupal::logger('nyc_migrate')->notice('Node storage loadMultiple returned ' . var_export($node_batch, TRUE)); 
        }
        catch (Exception $e) {
          \Drupal::logger('nyc_migrate')->notice('Node storage loadMultiple exception ' . $e->getMessage() . ' ' . var_export($node_batch, TRUE));
        }
        // \Drupal::logger('nyc_migrate')->notice('Loaded node operation count ' . count($node_batch) );
        // batch operations are calls to "nyc_migrate_do_update" function in nyc_miigrate.batch.inc
        // Passes project data $node_batch (array of project nodes)
        $batch_operations[] = [
          '\Drupal\nyc_migrate\Service\NycMigrateBatchService::nyc_migrate_do_update',
          [$node_batch]
        ];
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
    // Calling Drupal Batch API to execute batch job.
    batch_set($batch);
    return count($batch_node_ids);
  }

}
