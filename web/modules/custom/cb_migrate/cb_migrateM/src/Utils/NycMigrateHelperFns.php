<?php

namespace Drupal\nyc_migrate\Utils;

class NycMigrateHelperFns {

  /**
   *
   * @return Array
   */
  public static function getAllContentTypes() {
    $node_types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
    $options = [];
    foreach ($node_types as $node_type) {
      $options[$node_type->id()] = $node_type->label();
    }
    return $options;
  }

  public static function hasField($bundle, $field_name) {
    $query = \Drupal::entityQuery('node')
      ->condition('type', $bundle);
    $query->exists($field_name);
    $results = $query->execute;
    return !empty($results);
  }

  public static function hasListpage($project_id) {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'list_page')
      ->condition('field_project.0.target_id', $project_id);
    $results = $query->execute();
    return !empty($results);
  }
  
  /**
   * Checks if project has newly created content/nodes associated with it
   * 
   * @param type $project_id
   * @return type
   */
  public static function hasContent($project_id, $bundle = 'document') {
    
    $query = \Drupal::entityQuery('node')
      ->condition('type', $bundle)
      ->condition('field_project.0.target_id', $project_id);
    $results = $query->execute();
    return !empty($results);
  }

  /**
   * For a non-project Node (e.g. feedback map) with a field that contains a Entity Reference to a Project node, 
   * determine if the referenced project node's project menu (also an entity reference) contains a checked Feedback Map
   * option.
   */
  public static function hasFeedbackMapSelected($node) {
    return self::hasProjectMenuLabelSelected($node, 'Feedback Map');
  }

  /**
   * For a non-project Node (e.g. basic page) with a field that contains a Entity Reference to a Project node, 
   * determine if the referenced project node's project menu (also an entity reference) contains a checked Basic Page
   * option.
   */
  public static function hasBasicPageSelected($node) {
    return self::hasProjectMenuLabelSelected($node, 'Basic Page');
  }

  public static function hasProjectMenuLabelSelected($node, $label) {
    if (empty($node)) {
      return;
    }
    $first = $referenced_project = $node
      ->get('field_project')
      ->first();

    if (empty($first)) {
      return;
    }
    $referenced_project = $node
      ->get('field_project')
      ->first()
      ->get('entity');
    if (empty($referenced_project)) {
      return FALSE;
    }
    $referenced_project = $referenced_project->getTarget();
    if (empty($referenced_project)) {
      return FALSE;
    }
    $referenced_project = $referenced_project->getValue();

    $checked_project_menu_opts = [];
    foreach ($referenced_project->get('field_project_menu')->referencedEntities() as $opt) {
      $checked_project_menu_opts[] = $opt->getName();
    }
    return in_array($label, $checked_project_menu_opts);
  }

}
