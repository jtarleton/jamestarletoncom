<?php

namespace Drupal\nyc_migrate\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\node\Entity\Node;
use \Drupal\nyc_migrate\Utils\NycMigrateUpdater;
use \Drupal\nyc_migrate\Utils\NycMigrateHelperFns;
use \Drupal\nyc_migrate\Entity\Document;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class BatchService.
 */
class NycMigrateImportBatchService {

  /**
   * Batch process callback.
   *
   * @param int $id
   *   Id of the batch.
   * @param string $operation_details
   *   Details of the operation.
   * @param object $context
   *   Context for operations.
   */
  public function importContent($id, $operation_details, &$context) {

    // Simulate long process by waiting 100 microseconds.
    usleep(100);

    // Store some results for post-processing in the 'finished' callback.
    // The contents of 'results' will be available as $results in the
    // 'finished' function (in this example, batch_example_finished()).
    $context['results'][] = $id;

    // Optional message displayed under the progressbar.
    $context['message'] = t('Running Batch "@id" @details', ['@id' => $id, '@details' => $operation_details]
    );
  }

  /**
   * Batch process callback.
   *
   * @param int $id
   *   Id of the batch.
   * @param string $operation_details
   *   Details of the operation.
   * @param object $context
   *   Context for operations.
   */
  public function importDocumentContent($id, $nid, $operation_details, &$context) {

    if (!empty($nid)) {
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      $title = $node->label();
      $target = Document::createFromProject($node);
    }

    // Store some results for post-processing in the 'finished' callback.
    // The contents of 'results' will be available as $results in the
    // 'finished' function (in this example, batch_example_finished()).
    $context['results'][] = $id;

    // Optional message displayed under the progressbar.
    $context['message'] = t('Running Batch "@id" @details @title', ['@id' => $id, '@details' => $operation_details, '@title' => $title]
    );
  }

  /**
   * Batch process callback.
   *
   * @param int $id
   *   Id of the batch.
   * @param string $operation_details
   *   Details of the operation.
   * @param object $context
   *   Context for operations.
   */
  public function updateMapPageContent($id, $nid, $operation_details, &$context) {

    if (!empty($nid)) {
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      $title = $node->label();
      $project_menu_selected = TRUE; // assume default true

      // check if associated with a project
      $field_project = $node->hasField('field_project') ? $node->field_project->first()->getValue()['target_id'] : NULL;
      if (!empty($field_project)) {
        if ($node->bundle() == 'map') {
          $project_menu_selected = NycMigrateHelperFns::hasProjectMenuLabelSelected($node, 'Feedback Map');
        }
        else if ($node->bundle() == 'page') {
          $project_menu_selected = NycMigrateHelperFns::hasProjectMenuLabelSelected($node, 'Basic Page');
        }
        // if project menu unchecked, set show in project menu FALSE/0 for that map or basic page
        if (!$project_menu_selected && $node->hasField('field_show_in_project_menu')) {
          $node->set('field_show_in_project_menu',0);
          $node->save();
          \Drupal::logger('nyc_migrate')->notice('Updating show in project menu for ' . $node->getTitle());
        }
      }
      else {
        \Drupal::logger('nyc_migrate')->notice('Skipping as not associated with project ' . $node->getTitle());
      }
    }

    // Store some results for post-processing in the 'finished' callback.
    // The contents of 'results' will be available as $results in the
    // 'finished' function (in this example, batch_example_finished()).
    $context['results'][] = $id;

    // Optional message displayed under the progressbar.
    $context['message'] = t('Running Batch "@id" @details @title', ['@id' => $id, '@details' => $operation_details, '@title' => $title]
    );
  }

  /**
   * Batch Finished callback.
   *
   * @param bool $success
   *   Success of the operation.
   * @param array $results
   *   Array of results for post processing.
   * @param array $operations
   *   Array of operations.
   */
  public function importContentFinished($success, array $results, array $operations) {
    $messenger = \Drupal::messenger();
    if ($success) {
      // Here we could do something meaningful with the results.
      // We just display the number of nodes we processed...
      $messenger->addMessage(t('@count results processed.', ['@count' => count($results)]));
    }
    else {
      // An error occurred.
      // $operations contains the operations that remained unprocessed.
      $error_operation = reset($operations);
      $messenger->addMessage(
        t('An error occurred while processing @operation with arguments : @args', [
        '@operation' => $error_operation[0],
        '@args' => print_r($error_operation[0], TRUE),
          ]
        )
      );
    }
  }

}
