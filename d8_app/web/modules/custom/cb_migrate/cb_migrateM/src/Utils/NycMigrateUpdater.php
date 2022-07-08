<?php

namespace Drupal\nyc_migrate\Utils;

use Drupal\nyc_migrate\Utils\NycMigrateHelperFns;
use Drupal\node\Entity\Node;

class NycMigrateUpdater {

  /**
   *  Get  all source project node IDs we plan to migrate
   * @param int
   * @param int
   */
  public function getSources() {
    //Example: get source node with node id 12 - Downtown Jamaica

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'project');

    $nids = $query->execute();
    // \Drupal::logger('nyc_migrate')->notice('Read ' . count($nids) . ' source projects node IDs' . var_export($nids, TRUE));
    return $nids;
  }

  public function getRollback($count = FALSE, $bundle = NULL) {
    $nids = \Drupal::entityQuery("node")
      ->condition('type', $bundle)
      ->condition('created', time(), '<=')
      ->execute();
    return ($count) ? count($nids) : $nids;
  }

  public function getDocumentSources($count = false) {
    //Example: get source node with node id 12 - Downtown Jamaica
    $list_page_project_ids = [];
    // Check if documents checkbox on project is checked
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'list_page');
    $list_page_nids = $query->execute();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $list_page_nodeids = [];
    foreach ($node_storage->loadMultiple($list_page_nids) as $node) {
      // header
      if ($node->hasField('field_list_page_type')) {
        if (!empty($node->field_list_page_type->getValue() === 'document')) {
          $list_page_project_id = $node->get('field_project')->first()->getValue()['target_id'];

          $list_page_project_ids[] = $list_page_project_id;
        }
      }
    }
    //\Drupal::logger('nyc_migrate')->notice('Project IDs with existing List pages ' . var_export($list_page_project_ids,1));

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'project');

    $nids = $query->execute();
    $has_field = NycMigrateHelperFns::hasField('video', 'field_project_documents');
    $has_field_value = NULL;

    /* if ($has_field) {
      $nid = current($nids);
      $first_node = Node::load($nid);
      $type = 'project_documents';
      $fld = 'field_' . $type;
      if ($first_node->hasField($fld)) {
      $has_field_value = TRUE;
      }
      }
      if (empty($has_field_value)) {
      return [];
      } */
    
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodeids = [];
    foreach ($node_storage->loadMultiple($nids) as $node) {
      $type = 'project_documents';

      $fld = 'field_' . $type;
      // header
      if ($node->hasField($fld)) {
        if (!empty($node->$fld->getValue())) {
          $nodeids[] = $node->id();
        }
      }
    }

    //\Drupal::logger('nyc_migrate')->notice('Read ' . count($nodeids) . ' source projects node IDs' . var_export($nids, TRUE));

    return ($count) ? count($nodeids) : $nodeids;
  }

  public function getBasicpageSources($count = FALSE) {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'page');

    $nids = $query->execute();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodeids = [];
    foreach ($node_storage->loadMultiple($nids) as $node) {

      $type = 'show_in_project_menu';
      $fld = 'field_' . $type;

      $project_has_page_selected = NycMigrateHelperFns::hasBasicpageSelected($node);
      if ($node->hasField($fld) || $project_has_page_selected) {
        if (!empty((int) $node->$fld->getValue()[0]['value'])) {
          $nodeids[] = $node->id();
        }
      }
    }

    // \Drupal::logger('nyc_migrate')->notice('Read ' . count($nodeids) . ' source projects node IDs' . var_export($nodeids, TRUE));
    return ($count) ? count($nodeids) : $nodeids;
  }

  public function getBasicpageSourcesCt() {
    $ct_result = $this->getBasicpageSources(TRUE);
    return (int) $ct_result;
  }

  public function getMapSources($count = FALSE) {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'map');
    $nids = $query->execute();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodeids = [];
    foreach ($node_storage->loadMultiple($nids) as $node) {
      $type = 'show_in_project_menu';
      $fld = 'field_' . $type;

      $project_has_map_selected = NycMigrateHelperFns::hasFeedbackMapSelected($node);

      if ($node->hasField($fld) || $project_has_map_selected) {
        if (!empty($node->$fld->getValue())) {
          $nodeids[] = $node->id();
        }
      }
    }
    // \Drupal::logger('nyc_migrate')->notice('Read ' . count($nodeids) . ' source projects node IDs' . var_export($nodeids, TRUE));
    return ($count) ? count($nodeids) : $nodeids;
  }

  public function getMapSourcesCt() {
    $ct_result = $this->getMapSources(TRUE);
    return (int) $ct_result;
  }

  public function getVideoSources($count = false) {
    //Example: get source node with node id 12 - Downtown Jamaica

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'project');
    $nids = $query->execute();

    $has_field = NycMigrateHelperFns::hasField('video', 'field_project_videos');
    $has_field_value = NULL;

    /* if ($has_field) {
      $nid = current($nids);
      $first_node = Node::load($nid);
      $type = 'project_videos';
      $fld = 'field_' . $type;
      if ($first_node->hasField($fld)) {
      $has_field_value = TRUE;
      }
      }
      if (empty($has_field_value)) {
      return [];
      } */

    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodeids = [];
    foreach ($node_storage->loadMultiple($nids) as $node) {

      $type = 'project_videos';
      $fld = 'field_' . $type;
      // header
      if ($node->hasField($fld)) {
        if (!empty($node->$fld->getValue())) {
          $nodeids[] = $node->id();
        }
      }
    }

    // \Drupal::logger('nyc_migrate')->notice('Read ' . count($nodeids) . ' source projects node IDs' . var_export($nodeids, TRUE));
    return ($count) ? count($nodeids) : $nodeids;
  }

  public function getEventSources($count = false) {
    //Example: get source node with node id 12 - Downtown Jamaica

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'project');

    $nids = $query->execute();
    $has_field = NycMigrateHelperFns::hasField('video', 'field_events_header');
    $has_field_value = NULL;

    /* if ($has_field) {
      $nid = current($nids);
      $first_node = Node::load($nid);
      $type = 'events_header';
      $fld = 'field_' . $type;
      if ($first_node->hasField($fld)) {
      $has_field_value = TRUE;
      }
      }
      if (empty($has_field_value)) {
      return [];
      } */

    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodeids = [];
    foreach ($node_storage->loadMultiple($nids) as $node) {

      $type = 'events_header';

      $fld = 'field_' . $type;
      // header
      if ($node->hasField($fld)) {
        if (!empty($node->$fld->getValue())) {
          $nodeids[] = $node->id();
        }
      }
    }

    // \Drupal::logger('nyc_migrate')->notice('Read ' . count($nodeids) . ' source projects node IDs' . var_export($nodeids, TRUE));
    return ($count) ? count($nodeids) : $nodeids;
  }

  // Count all source project nodes we plan to migrate
  public function getSourcesCt() {
    $ct_result = \Drupal::entityQuery('node')->condition('type', 'project')->count()->execute();
    return (int) $ct_result;
  }

  // Count all source project nodes we plan to migrate
  public function getDocumentSourcesCt() {
    $ct_result = $this->getDocumentSources(TRUE);
    return (int) $ct_result;
  }

  // Count all source project nodes we plan to migrate
  public function getEventSourcesCt() {
    $ct_result = $this->getEventSources(TRUE);
    return (int) $ct_result;
  }

  // Count all source project nodes we plan to migrate
  public function getVideoSourcesCt() {
    $ct_result = $this->getVideoSources(TRUE);
    return (int) $ct_result;
  }

  // Count all nodes we plan to rollback
  public function getRollbackCt($bundle) {
    $ct_result = $this->getRollback(TRUE, $bundle);
    return (int) $ct_result;
  }

  /*
   * Get node IDs with custom query
   */

  public function getSourceNodeIds($limit = 0, $offset = 0, $type = 'project') {
    $query = \Drupal::database()->select('node', 'n');
    $query->addField('n', 'nid');
    $query->condition('n.type', $type);
    if (!empty($limit)) {
      $query->range($offset, $limit);
    }
    $result = $query->execute()->fetchAll();
    $nids = [];
    foreach ($result as $row) {
      $nids[] = $row->nid;
    }
    return $nids;
  }

  /*
   * Get node IDs for maps/pages
   */
  public function getMapsPagesSourceNodeIds($limit = 0, $offset = 0, $type = 'map') {
    $query = \Drupal::database()->select('node_field_data', 'nfd');
    $query->addField('nfd', 'nid');
    $query->condition('nfd.type', $type);
    $query->condition('nfd.status', 1);
    $query->leftJoin('node__field_show_in_project_menu', 'fspm', "nfd.nid = fspm.entity_id and fspm.bundle = :bundle", ['bundle' => $type]);
    $query->condition('fspm.field_show_in_project_menu_value', 1);

    if (!empty($limit)) {
      $query->range($offset, $limit);
    }
    $result = $query->execute()->fetchAll();
    $nids = [];
    foreach ($result as $row) {
      $nids[] = $row->nid;
    }
    return $nids;
  }

}
