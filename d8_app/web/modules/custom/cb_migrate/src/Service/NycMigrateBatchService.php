<?php

namespace Drupal\nyc_migrate\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\node\Entity\Node;
use \Drupal\nyc_migrate\Utils\NycMigrateUpdater;
use \Drupal\nyc_migrate\Controller\NycMigrateBatchRollbackController;
use \Drupal\nyc_migrate\Entity\Overviewpage;
use \Drupal\nyc_migrate\Entity\Document;
use \Drupal\nyc_migrate\Entity\Video;
use \Drupal\nyc_migrate\Entity\DocumentsListingpage;
use \Drupal\nyc_migrate\Entity\EventsListingpage;
use \Drupal\nyc_migrate\Entity\VideosListingpage;
use \Drupal\nyc_migrate\Entity\Projectlink;
use \Drupal\nyc_migrate\Entity\Survey;
use \Drupal\nyc_migrate\Entity\Listingpage;

/**
 * Class BatchService.
 */
class NycMigrateBatchService {

  public function nyc_migrate_do_events_update($operations = [], &$context) {
    $i = 0;
    // \Drupal::logger('nyc_migrate')->notice('Operations count' . count($operations));
    foreach ($operations as $node) {
      if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
        try {
          if (!empty($node)) {

            EventsListingpage::init();
            $target = EventsListingpage::createFromProject($node, 'event');

            //   \Drupal::logger('nyc_migrate')->notice('incrementing...');
            // Increment currently processed entities.
            $i++;
          }
        }
        catch (Exception $e) {
          // Need to improve error handling, but for now check for empty value post-update
          $summary = $e->getMessage();
          $notice = 'Nyc_migrate - ' . $node->getTitle()
            . ' node ID ' . $node->id()
            . ' Exception. Details: '
            . $e->getMessage();
        }
        if (!empty($notice)) {
          \Drupal::logger('nyc_migrate')->notice($notice);
        }

        // A message to display during the update
        $context['message'] = 'Processing events list page from project ' . $node->getTitle();
      }
      else {
        \Drupal::logger('nyc_migrate')->notice('Empty node');
      }
    }
  }

  public function nyc_migrate_do_videos_update($operations = [], &$context) {
    $i = 0;
    // \Drupal::logger('nyc_migrate')->notice('Operations count' . count($operations));
    foreach ($operations as $node) {
      if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
        try {
          if (!empty($node)) {

            VideosListingpage::init();
            $target = VideosListingpage::createFromProject($node, 'video');

            //   \Drupal::logger('nyc_migrate')->notice('incrementing...');
            // Increment currently processed entities.
            $i++;
          }
        }
        catch (Exception $e) {
          // Need to improve error handling, but for now check for empty value post-update
          $summary = $e->getMessage();
          $notice = 'Nyc_migrate - ' . $node->getTitle()
            . ' node ID ' . $node->id()
            . ' Exception. Details: '
            . $e->getMessage();
        }
        if (!empty($notice)) {
          \Drupal::logger('nyc_migrate')->notice($notice);
        }

        // A message to display during the update
        $context['message'] = 'Processing videos list page from project ' . $node->getTitle();
      }
      else {
        \Drupal::logger('nyc_migrate')->notice('Empty node');
      }
    }
  }

  public function nyc_migrate_do_rollback($operations = [], &$context) {
    $storage_handler = \Drupal::entityTypeManager()->getStorage("node");
    foreach ($operations as $node) {
      if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
        try {
          if (!empty($node)) {
            $node->delete();
          }
        }
        catch (Exception $e) {
          $e->getMessage();
          if (!empty($notice)) {
            \Drupal::logger('nyc_migrate')->notice($notice);
          }
        }
      }
    }
  }

  public function nyc_migrate_do_video_update($operations = [], &$context) {
    $i = 0;

    /* */

    // \Drupal::logger('nyc_migrate')->notice('Operations count' . count($operations));
    foreach ($operations as $node) {
      if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
        try {
          if (!empty($node)) {

            $target = Video::createFromProject($node);

            // \Drupal::logger('nyc_migrate')->notice('incrementing...');
            // Increment currently processed entities.
            $i++;
          }
        }
        catch (Exception $e) {
          // Need to improve error handling, but for now check for empty value post-update
          $summary = $e->getMessage();
          $notice = 'Nyc_migrate - ' . $node->getTitle()
            . ' node ID ' . $node->id()
            . ' Exception. Details: '
            . $e->getMessage();
        }
        if (!empty($notice)) {
          \Drupal::logger('nyc_migrate')->notice($notice);
        }

        // A message to display during the update
        $context['message'] = 'Processing new video content item from project ' . $node->getTitle();
      }
      else {
        \Drupal::logger('nyc_migrate')->notice('Empty node');
      }
    }
  }

  public function nyc_migrate_do_docs_update($operations = [], &$context) {
    $i = 0;
    // \Drupal::logger('nyc_migrate')->notice('Operations count' . count($operations));


    /* $result = \Drupal::entityQuery("node")
      ->condition('type','document')
      ->condition('created', time(), '<=')
      ->execute();
      $storage_handler = \Drupal::entityTypeManager()->getStorage("node");

      $entities = $storage_handler->loadMultiple($result);
      $storage_handler->delete($entities); */


    foreach ($operations as $node) {
      if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
        try {
          if (!empty($node)) {

            $target = Document::createFromProject($node);

            // \Drupal::logger('nyc_migrate')->notice('incrementing...');
            // Increment currently processed entities.
            $i++;
          }
        }
        catch (Exception $e) {
          // Need to improve error handling, but for now check for empty value post-update
          $summary = $e->getMessage();
          $notice = 'Nyc_migrate - ' . $node->getTitle()
            . ' node ID ' . $node->id()
            . ' Exception. Details: '
            . $e->getMessage();
        }
        if (!empty($notice)) {
          \Drupal::logger('nyc_migrate')->notice($notice);
        }

        // A message to display during the update
        $context['message'] = 'Processing new document content item from project ' . $node->getTitle();
      }
      else {
        \Drupal::logger('nyc_migrate')->notice('Empty node');
      }
    }
  }

  public function nyc_migrate_do_link_update($operations = [], &$context) {
    // \Drupal::logger('nyc_migrate')->notice('nyc_migrate_do_link_update');
    foreach ($operations as $node) {
      if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
        try {
          if (!empty($node)) {

            // \Drupal::logger('nyc_migrate')->notice('Begin init');

            $target = Projectlink::createFromProject($node);

            // \Drupal::logger('nyc_migrate')->notice('Projectlink::createFromProject completed - incrementing...');
            // Increment currently processed entities.
            $i++;
          }
        }
        catch (Exception $e) {
          // Need to improve error handling, but for now check for empty value post-update
          $summary = $e->getMessage();
          $notice = 'Nyc_migrate - ' . $node->getTitle()
            . ' node ID ' . $node->id()
            . ' Exception. Details: '
            . $e->getMessage();
        }
        if (!empty($notice)) {
          \Drupal::logger('nyc_migrate')->notice($notice);
        }

        // A message to display during the update
        $context['message'] = 'Processing links from project ' . $node->getTitle();
      }
      else {
        \Drupal::logger('nyc_migrate')->notice('Empty node');
      }
    }
  }

  public function nyc_migrate_do_maps_unpublish($id, $nid, $operation_details, &$context) {
    // \Drupal::logger('nyc_migrate')->notice('nyc_migrate_do_survey_update');
    //foreach ($operations as $node) { 
    if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
      try {
        if (!empty($node)) {

          // \Drupal::logger('nyc_migrate')->notice('Begin nyc_migrate_do_maps_unpublish');
          /*
            $data = [
            'type' => 'map',
            'langcode' => 'en',
            'created' => $created_date,
            'changed' => $created_date,
            'uid' => $current_user->id(),
            'moderation_state' => 'published',
            'title'=>$node->getTitle() .' Document',
            'path' => [
            'alias' => $alias_path,
            'pathauto' => PathautoState::SKIP
            ],
            'field_project'=>$node->id(),
            //field_project is a project node reference
            'field_document'=>$target_para,
            //'field_show_in_project_menu' => TRUE,
            'body' =>  [
            'value' => $source_header,
            'summary'=>'',
            'format' => 'full_html'
            ]
            ]; */
          $node->setPublished(FALSE);
          $node->save();


          //$target = Survey::createFromProject($node);
          // \Drupal::logger('nyc_migrate')->notice('Survey::createFromProject completed - incrementing...');
          // Increment currently processed entities.
          $i++;
        }
      }
      catch (Exception $e) {
        // Need to improve error handling, but for now check for empty value post-update
        $summary = $e->getMessage();
        $notice = 'Nyc_migrate - ' . $node->getTitle()
          . ' node ID ' . $node->id()
          . ' Exception. Details: '
          . $e->getMessage();
      }
      if (!empty($notice)) {
        \Drupal::logger('nyc_migrate')->notice($notice);
      }

      // A message to display during the update
      $context['message'] = 'Processing map ' . $node->getTitle();
    }
    else {
      \Drupal::logger('nyc_migrate')->notice('Empty node');
    }
    //} 
  }

  public function nyc_migrate_do_basicpage_unpublish($id, $nid, $operation_details, &$context) {
    if (!empty($nid)) {
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    }
    if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
      try {
        if (!empty($node)) {
          \Drupal::logger('nyc_migrate')->notice('Begin nyc_migrate_do_basicpage_unpublish');
          $node->setPublished(FALSE);
          $node->save();
          $i++;
        }
      }
      catch (Exception $e) {
        // Need to improve error handling, but for now check for empty value post-update
        $summary = $e->getMessage();
        $notice = 'Nyc_migrate - ' . $node->getTitle()
          . ' node ID ' . $node->id()
          . ' Exception. Details: '
          . $e->getMessage();
      }
      if (!empty($notice)) {
        \Drupal::logger('nyc_migrate')->notice($notice);
      }

      // A message to display during the update
      $context['message'] = 'Processing basic page  ' . $node->getTitle();
    }
    else {
      \Drupal::logger('nyc_migrate')->notice('Empty node');
    }
  }

  public function nyc_migrate_do_survey_update($operations = [], &$context) {
    // \Drupal::logger('nyc_migrate')->notice('nyc_migrate_do_survey_update');
    foreach ($operations as $node) {
      if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
        try {
          if (!empty($node)) {

            // \Drupal::logger('nyc_migrate')->notice('Begin Survey::createFromProject');

            $target = Survey::createFromProject($node);

            // \Drupal::logger('nyc_migrate')->notice('Survey::createFromProject completed - incrementing...');
            // Increment currently processed entities.
            $i++;
          }
        }
        catch (Exception $e) {
          // Need to improve error handling, but for now check for empty value post-update
          $summary = $e->getMessage();
          $notice = 'Nyc_migrate - ' . $node->getTitle()
            . ' node ID ' . $node->id()
            . ' Exception. Details: '
            . $e->getMessage();
        }
        if (!empty($notice)) {
          \Drupal::logger('nyc_migrate')->notice($notice);
        }

        // A message to display during the update
        $context['message'] = 'Processing survey from project ' . $node->getTitle();
      }
      else {
        \Drupal::logger('nyc_migrate')->notice('Empty node');
      }
    }
  }

  public function nyc_migrate_do_documents_update($operations = [], &$context) {
    $i = 0;
    /* \Drupal::logger('nyc_migrate')->notice('Operations count' . count($operations));
      \Drupal::logger('nyc_migrate')->notice('nyc_migrate_do_documents_update'); */

    foreach ($operations as $node) {
      if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {
        try {
          if (!empty($node)) {

            // \Drupal::logger('nyc_migrate')->notice('Begin init');
            DocumentsListingpage::init();
            $target = DocumentsListingpage::createFromProject($node, 'document');

            // \Drupal::logger('nyc_migrate')->notice('DocumentsListingpage::createFromProject completed - incrementing...');
            // Increment currently processed entities.
            $i++;
          }
        }
        catch (Exception $e) {
          // Need to improve error handling, but for now check for empty value post-update
          $summary = $e->getMessage();
          $notice = 'Nyc_migrate - ' . $node->getTitle()
            . ' node ID ' . $node->id()
            . ' Exception. Details: '
            . $e->getMessage();
        }
        if (!empty($notice)) {
          \Drupal::logger('nyc_migrate')->notice($notice);
        }

        // A message to display during the update
        $context['message'] = 'Processing documents list page from project ' . $node->getTitle();
      }
      else {
        \Drupal::logger('nyc_migrate')->notice('Empty node');
      }
    }
  }

  public function nyc_migrate_do_update($operations = [], &$context) {
    $i = 0;
    \Drupal::logger('nyc_migrate')->notice('Operations count' . count($operations));
    foreach ($operations as $node) {
      if (!empty($node) && $node instanceof \Drupal\node\Entity\Node) {


        try {
          if (!empty($node)) {
            $target = Overviewpage::createFromProject($node);
            // Increment currently processed entities.
            $i++;
          }
        }
        catch (Exception $e) {
          // Need to improve error handling, but for now check for empty value post-update
          $summary = $e->getMessage();
          $notice = 'Nyc_migrate - ' . $node->getTitle()
            . ' node ID ' . $node->id()
            . ' Exception. Details: '
            . $e->getMessage();
        }
        if (!empty($notice)) {
          \Drupal::logger('nyc_migrate')->notice($notice);
        }

        // A message to display during the update
        $context['message'] = 'Processing overview from project ' . $node->getTitle();
      }
      else {
        \Drupal::logger('nyc_migrate')->notice('Empty node');
      }
    }
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
  public function processMyNode($id, $operation_details, &$context) {
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
   * Batch Finished callback.
   *
   * @param bool $success
   *   Success of the operation.
   * @param array $results
   *   Array of results for post processing.
   * @param array $operations
   *   Array of operations.
   */
  public function processMyNodeFinished($success, array $results, array $operations) {
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
