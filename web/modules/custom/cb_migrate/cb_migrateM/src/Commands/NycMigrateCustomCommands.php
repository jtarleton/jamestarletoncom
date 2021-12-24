<?php

namespace Drupal\nyc_migrate\Commands;

use Drush\Commands\DrushCommands;
use Drush\Style\DrushStyle;
use Drush\Drush;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\ConsoleOutput;
use Drupal\nyc_migrate\Utils\NycMigrateHelperFns;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\nyc_migrate\Utils\NycMigrateUpdater;

/**
 * A drush command file.
 *
 * @package Drupal\nyc_migrate\Commands
 */
class NycMigrateCustomCommands extends DrushCommands {

  /**
   * Entity type service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * Logger service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  private $loggerChannelFactory;

  /**
   * Constructs a new object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   *   Logger service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, LoggerChannelFactoryInterface $loggerChannelFactory) {
    $this->entityTypeManager = $entityTypeManager;
    $this->loggerChannelFactory = $loggerChannelFactory;
  }

  /**
   * Drush command that triggers a batch job.
   *
   * @command nyc_migrate:import
   * @aliases nmi
   * @usage nyc_migrate:import video
   */
  public function import($limit = 10, $offset = 0) {

    $options = array(
      'video' => 'video',
      'videos' => 'videos list page',
      'documents' => 'documents list page',
      'document' => 'document',
      'overview' => 'overview',
      'events' => 'events list page',
      'projectlink' => 'projectlink',
      'survey' => 'survey',
      'unpublish_maps' => 'unpublish maps',
      'unpublish_basic_pages' => 'unpublish basic pages',
      'rollback' => 'rollback [delete all target data]'
    );

    $io = new DrushStyle($this->input(), $this->output());
    $type = $io->choice('Please enter the source to migrate', $options);
    $limit = NULL;
    $offset = NULL;
    if (in_array($type, ['unpublish_maps', 'unpublish_basic_pages'])) {
      $limit = $io->ask('Please enter the limit [enter to skip]', 10000, function ($number) {
        if (!is_numeric($number)) {
          throw new \RuntimeException('You must type a number.');
        }

        return (int) $number;
      });
      $offset = $io->ask('Please enter the offset [enter to skip]', 0, function ($number) {
        if (!is_numeric($number)) {
          throw new \RuntimeException('You must type a number.');
        }

        return (int) $number;
      });
    }

    if ($type) {
      $this->output()->writeln(dt('Importing @choice...', array('@choice' => $options[$type])));

      switch ($type) {
        case 'video':
          // Add batch operations as new batch sets.
          \Drupal\nyc_migrate\Controller\NycMigrateBatchVideoController::executeBatchProcess();
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'videos':
          // Add batch operations as new batch sets.
          \Drupal\nyc_migrate\Controller\NycMigrateBatchVideosController::executeBatchProcess();
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'document':
          // Add batch operations as new batch sets.
          \Drupal\nyc_migrate\Controller\NycMigrateBatchDocumentController::executeBatchProcess();
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'documents':
          // Add batch operations as new batch sets.
          \Drupal\nyc_migrate\Controller\NycMigrateBatchDocumentsController::executeBatchProcess();
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'survey':
          // Add batch operations as new batch sets.
          \Drupal\nyc_migrate\Controller\NycMigrateBatchSurveyController::executeBatchProcess();
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'projectlink':
          // Add batch operations as new batch sets.
          \Drupal\nyc_migrate\Controller\NycMigrateBatchProjectlinkController::executeBatchProcess();
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'overview':
          // Add batch operations as new batch sets.
          \Drupal\nyc_migrate\Controller\NycMigrateBatchOverviewController::executeBatchProcess();
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'events':
          // Add batch operations as new batch sets.
          \Drupal\nyc_migrate\Controller\NycMigrateBatchEventsController::executeBatchProcess();
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'unpublish_maps':
          // Add batch operations as new batch sets.

          \Drupal\nyc_migrate\Controller\NycMigrateBatchUnpublishMapsController::executeBatchProcess($limit, $offset);
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'unpublish_basic_pages':
          // Add batch operations as new batch sets.
          \Drupal\nyc_migrate\Controller\NycMigrateBatchUnpublishBasicPagesController::executeBatchProcess($limit, $offset);
          // Process the batch sets.
          drush_backend_batch_process();
          break;
        case 'rollback':
          // Rollback - delete all newly imported data
          \Drupal\nyc_migrate\Controller\NycMigrateBatchRollbackController::executeBatchProcess();
          // Process the batch sets.
          drush_backend_batch_process();
          break;

        default:
          break;
      }
    }
  }

  /**
   * Drush command that triggers a batch job to migrate content from Project node.
   *
   * @param string $type
   *   Type of content to migrate.
   *
   * @param string $limit
   *   Limit the items to process.
   *
   * @param string $offset
   *   Offset for the items to process.
   *
   *   Argument provided to the drush command.
   *
   * @command nyc_migrate:import-project-content
   * @aliases nm-ipc
   *
   * @usage nyc_migrate:import-project-content document 20 0
   *   document is the type of content to migrate from project node
   *   20 is for limit of records
   *   0 is for offset of records
   */
  public function importContent($type = 'document', $limit = 10, $offset = 0) {

    // 1. Log the start of the script.
    $this->loggerChannelFactory->get('nyc_migrate')->info('Migrate project content batch operations start');

    // Check the type of content given as argument, if not, set document as default.
    if (strlen($type) == 0) {
      $type = 'document';
    }

    

    $updater = new NycMigrateUpdater;

    // 2. Retrieve all nodes of project type.
    try {
      switch ($type) {
        case 'document':
          $this->output()->writeln(dt('Importing @type from Projects limit @limit offset @offset', array('@type' => $type, '@limit' => $limit, '@offset' => $offset)));
          $nids = $updater->getSourceNodeIds($limit, $offset, 'project');
          $op_name = '\Drupal\nyc_migrate\Service\NycMigrateImportBatchService::importDocumentContent';
          break;
        case 'page':
          $this->output()->writeln(dt('Updating @type limit @limit offset @offset', array('@type' => $type, '@limit' => $limit, '@offset' => $offset)));
          $nids = $updater->getMapsPagesSourceNodeIds($limit, $offset, 'page');
          $op_name = '\Drupal\nyc_migrate\Service\NycMigrateImportBatchService::updateMapPageContent';
          break;  
        case 'map':
          $this->output()->writeln(dt('Updating @type limit @limit offset @offset', array('@type' => $type, '@limit' => $limit, '@offset' => $offset)));
          $nids = $updater->getMapsPagesSourceNodeIds($limit, $offset, 'map');
          $op_name = '\Drupal\nyc_migrate\Service\NycMigrateImportBatchService::updateMapPageContent';
          break;    
        default:
          $op_name = '';
          //$this->output()->writeln(dt('Importing @type from Projects limit @limit offset @offset', array('@type' => $type, '@limit' => $limit, '@offset' => $offset)));
          //$nids = $updater->getSourceNodeIds($limit, $offset, 'project');
          //$op_name = '\Drupal\nyc_migrate\Service\NycMigrateImportBatchService::importDocumentContent';
          break; 
      }
      
    }
    catch (\Exception $e) {
      $this->output()->writeln($e);
      $this->loggerChannelFactory->get('nyc_migrate')->warning('Error found @e', ['@e' => $e]);
    }

    // 3. Create the operations array for the batch.
    $operations = [];
    $numOperations = 0;
    $batchId = 1;

    if (!empty($op_name)) {
      if (!empty($nids)) {
        foreach ($nids as $nid) {

          $operations[] = [
            $op_name,
            [
              $batchId, $nid,
              t('Importing/Updating content from node @nid', ['@nid' => $nid]),
            ],
          ];
          $batchId++;
          $numOperations++;
        }
      }
      else {
        $this->logger()->warning('No nodes available');
      }
    }
    
    // 4. Create the batch.
    $batch = [
      'title' => t('Updating @num node(s)', ['@num' => $numOperations]),
      'operations' => $operations,
      'finished' => '\Drupal\nyc_migrate\Service\NycMigrateImportBatchService::importContentFinished',
    ];

    // 5. Add batch operations as new batch sets.
    batch_set($batch);

    // 6. Process the batch sets.
    drush_backend_batch_process();

    // 6. Show some information.
    $this->logger()->notice("Batch operations end.");
    // 7. Log some information.
    $this->loggerChannelFactory->get('nyc_migrate')->info('Migrate project content batch operations end.');
  }

}
