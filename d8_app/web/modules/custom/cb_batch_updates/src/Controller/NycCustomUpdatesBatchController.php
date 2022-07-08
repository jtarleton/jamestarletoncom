<?php

namespace Drupal\nyc_custom_updates\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Database\Driver\mysql\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\nyc_custom_updates\Utils\NycCommentUpdater;

/**
 * Returns responses for nyc_custom_updates routes.
 */
class NycCustomUpdatesBatchController extends ControllerBase {
  
  /**
   * Builds the response.
   */
  public function build() {
    self::executeCommentBatchProcess();
    $routeName = 'nyc_custom_updates.example';
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

    $routeName = 'nyc_custom_updates.example';
    $routeParameters = []; //['node' => $nodeId];
    $url = \Drupal::url($routeName, $routeParameters);
    \Drupal::messenger()->addStatus($message);

    return new RedirectResponse($url);
  }

  public static function executeCommentBatchProcess() {

      $updater = new NycCommentUpdater;

      $ct = $updater->getAllEmptyLocSummaryCommentsCt();
      $success = [];
      $results = [];
      $comment_batches = [];

      $batch_size = 5;

      $nyc_custom_updates_config = \Drupal::config('nyc_custom_updates.settings');
      $config_batch_size = $nyc_custom_updates_config->get('batchsize');

      if($config_batch_size >= 1) {
        $batch_size = (int)$config_batch_size;
      }

      $limit = 10000;
      $offset=0;
      
      $batch_operations = [];
      $batch_comment_ids = $updater->getAllEmptyLocSummaryComments();
      while ($offset < (int)$ct && ((int)$ct < $limit)) {
        
        if (!empty($batch_comment_ids)){
          
          $cmt_storage = \Drupal::entityTypeManager()->getStorage('comment');  
          $batch_comment_ids_slice = array_slice($batch_comment_ids, $offset, $batch_size);
          $offset += $batch_size;
          $comment_batch = $cmt_storage->loadMultiple($batch_comment_ids_slice);
          
          $batch_operations[] = ['nyc_custom_updates_do_update', [$comment_batch]];
        }
      }
      

      $batch = array(
        'title' => t('Execute Update'),
        'operations' => $batch_operations,
        //'finished' => self::nyc_custom_updates_do_update_finished_callback($success, $results, $operations),
        'init_message' => t('Starting.'),
        'progress_message' => t('Processed @current out of @total batches. Estimated time: @estimate.'),
        'error_message' => t('The process has encountered an error.'),
        'file' => drupal_get_path('module', 'nyc_custom_updates') . '/nyc_custom_updates.batch.inc',
      );

      batch_set($batch);

      return count($batch_comment_ids);

  }

}
