<?php

namespace Drupal\nyc_migrate\Entity;

use Drupal\node\Entity\Node;
use Drupal\pathauto\PathautoState;

class Survey extends Node {

  use Listingpage;

  public static function createFromProject(\Drupal\node\Entity\Node $node) {
    $created_date = time();

    $current_user = \Drupal::currentUser();
    
    $show_in_project_menu_key = 12;
    
    foreach ($node->field_project_menu->getValue() as $checkbox) {
      $checked[] = (int) $checkbox['target_id'];
    }

    $show_in_project_menu = in_array($show_in_project_menu_key, $checked);

    // \Drupal::logger('nyc_migrate')->notice('Begin Survey Entity::create from ' . var_export($node->getTitle(), TRUE));

    if ($node->hasField('field_project_survey') && !empty($node->field_project_survey->getValue())) {


      foreach ($node->field_project_survey->getValue() as $link) {

        $source_project_survey_url = $link['uri'];
        $source_project_survey_title = $link['title'];
        $data = [];
        if (!empty($node->id())) {
          $alias_path = '';
          $data['title'] = $node->getTitle() . ' Survey';
          $data['field_project'] = ['target_id' => $node->id()];
          //$path = \Drupal::service('path.alias_storage')->save($alias_path, '/en');
        }

        $data = [
          'type' => 'project_link',
          'langcode' => 'en',
          'created' => $created_date,
          'changed' => $created_date,
          'uid' => '129',
          'moderation_state' => 'published',
          'title' => $node->getTitle() . ' Survey',
          'path' => [
            'alias' => $alias_path,
            'pathauto' => PathautoState::SKIP
          ],
          'field_project' => $node->id(),
          'field_link_type' => 'survey',
          //field_project is a project node reference 
          'field_link' => [
            'uri' => $source_project_survey_url,
            'title' => $source_project_survey_title
          ],
          //'field_show_in_project_menu' => TRUE,
          /*'body' => [
            'value' => '',
            'summary' => '',
            'format' => 'full_html'
          ]*/
        ];
        $target = Node::create($data);
        $target->setPublished($show_in_project_menu);
        $target->save();
        // \Drupal::logger('nyc_migrate')->notice('Survey Entity::save status  ' . var_export($status, TRUE));
      }
    }
  }

}
