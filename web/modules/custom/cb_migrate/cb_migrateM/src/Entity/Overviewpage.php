<?php

namespace Drupal\nyc_migrate\Entity;

use Drupal\node\Entity\Node;
use Drupal\pathauto\PathautoState;

class Overviewpage extends Node {

  use Listingpage;

  public static function createFromProject(
  \Drupal\node\Entity\Node $node) {
    $created_date = time();

    $current_user = \Drupal::currentUser();
    $source_overview_header = '';
    if ($node->hasField('field_overview_header')) {
      $source_overview_header = $node->field_overview_header->value;
    }

    $source_overview = '';
    if ($node->hasField('field_project_overview')) {
      $source_overview = $node->field_project_overview->value;
    }
    
    $show_in_project_menu_key = 17;
    
    foreach ($node->field_project_menu->getValue() as $checkbox) {
      $checked[] = (int) $checkbox['target_id'];
    }

    $show_in_project_menu = in_array($show_in_project_menu_key, $checked);
    
    if (empty($source_overview_header) && empty($source_overview) && !$show_in_project_menu) {
      \Drupal::logger('nyc_migrate')->notice('Skipping Overview for project ' . $node->getTitle());
      return;
    }



    $data = [];
    if (!empty($node->id())) {
      $alias_path = '/project/' . $node->id() . '/overview';
      $data['title'] = "Project Overview";
      $data['field_project'] = ['target_id' => $node->id()];
      $path = \Drupal::service('path.alias_storage')->save($alias_path, '/en');
    }

    $data += [
      'type' => 'overview_page',
      'langcode' => 'en',
      'created' => $created_date,
      'changed' => $created_date,
      'uid' => '129',
      'moderation_state' => 'published',
      'title' => '',
      'path' => [
        'alias' => $alias_path,
        'pathauto' => PathautoState::SKIP
      ],
      //'field_show_in_project_menu' => TRUE,
      'body' => [
        'value' => $source_overview,
        'summary' => '',
        'format' => 'full_html'
      ],
      'field_header' => [
        'value' => $source_overview_header,
        'summary' => '',
        'format' => 'full_html'
      ]
    ];


    $target = Node::create($data);

    $target->set('field_header', $source_overview_header);

    $target->set('body', $source_overview);
    $target->setPublished($published);
    $target->body->format = 'full_html';
    $target->field_header->format = 'full_html';
    $target->setPublished($show_in_project_menu);
    $status = $target->save();

    // \Drupal::logger('nyc_migrate')->notice('Entity::save status  ' . var_export($status, TRUE));
  }

}
