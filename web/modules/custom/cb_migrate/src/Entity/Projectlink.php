<?php

namespace Drupal\nyc_migrate\Entity;

use Drupal\node\Entity\Node;
use Drupal\pathauto\PathautoState;

class Projectlink extends Node {

  use Listingpage;

  public static function createFromProject(\Drupal\node\Entity\Node $node) {
    $created_date = time();

    $current_user = \Drupal::currentUser();

    // \Drupal::logger('nyc_migrate')->notice('Begin Projectlink Entity::create from ' . var_export($node->getTitle(), TRUE));
        
    $show_in_project_menu_key = 143;
    
    foreach ($node->field_project_menu->getValue() as $checkbox) {
      $checked[] = (int) $checkbox['target_id'];
    }

    $show_in_project_menu = in_array($show_in_project_menu_key, $checked);

    $source_project_link = '';
    if ($node->hasField('field_project_link') && !empty($node->field_project_link->getValue())) {

      foreach ($node->field_project_link->getValue() as $link) {
        $source_project_link_url = $link['uri'];
        $source_project_link_title = $link['title'];

        $data = [];
        if (!empty($node->id())) {
          $alias_path = '';
          $data['title'] = $node->getTitle() . ' Project Link';
          $data['field_project'] = ['target_id' => $node->id()];
          //$path = \Drupal::service('path.alias_storage')->save($alias_path, '/en');
        }

        $target_paras = [];

        $data = [
          'type' => 'project_link',
          'langcode' => 'en',
          'created' => $created_date,
          'changed' => $created_date,
          'uid' => '129',
          'moderation_state' => 'published',
          'title' => $node->getTitle() . ' Project Link',
          'path' => [
            'alias' => $alias_path,
            'pathauto' => PathautoState::SKIP
          ],
          'field_project' => $node->id(),
          'field_link_type' => 'link',
          //field_project is a project node reference 
          'field_link' => [
            'uri' => $source_project_link_url,
            'title' => $source_project_link_title
          ],
          //'field_show_in_project_menu' => TRUE,
          'body' => [
            'value' => '',
            'summary' => '',
            'format' => 'full_html'
          ]
        ];
        $target = Node::create($data);
        $target->setPublished($show_in_project_menu);
        $saved = $target->save();

        // \Drupal::logger('nyc_migrate')->notice('para saved' . var_export($target_paras, 1));               
        // \Drupal::logger('nyc_migrate')->notice('Document Entity::save status  ' . var_export($saved, TRUE));
      }
    }
  }

}
