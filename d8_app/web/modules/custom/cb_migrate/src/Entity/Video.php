<?php

namespace Drupal\nyc_migrate\Entity;

use Drupal\node\Entity\Node;
use Drupal\pathauto\PathautoState;

class Video extends Node {

  use Listingpage;

  public static function createFromProject(\Drupal\node\Entity\Node $node) {
    $created_date = time();

    $current_user = \Drupal::currentUser();

    // \Drupal::logger('nyc_migrate')->notice('Begin Video Entity::create from ' . var_export($node->getTitle(), TRUE));

    /*$source_header = '';
    if ($node->hasField('field_videos_header')) {
      $source_header = $node->field_videos_header->value;
    }*/

    $data = [];
    if (!empty($node->id())) {
      $alias_path = '';
      $data['title'] = $node->getTitle() . ' Video';
      $data['field_project'] = ['target_id' => $node->id()];
      //$path = \Drupal::service('path.alias_storage')->save($alias_path, '/en');
    }

    //old paragraph attached to project
    $source_paragraph = $node->field_project_videos->getValue();

    // \Drupal::logger('nyc_migrate')->notice('para ct ' . count($source_paragraph));
    $paras = [];


    $target_paras = [];
    // Loop through the result set.
    foreach ($source_paragraph as $element) {
      $p = \Drupal\paragraphs\Entity\Paragraph::load($element['target_id']);
      
      if (!empty($p)) {  
        
        if (!empty($p->field_video_title) && !empty($p->field_video_link) &&
          empty($p->field_video_title->getValue()) && empty($p->field_video_link->getValue()) ) {
          continue;
        }

        //paragraph will have the below fields:
        //field_video_link
        //field_video_title 
        //field_show_in_list - a checkbox
        $entity = $p->entity;
        $has_link = !empty($p->get('field_video_link')->value);
        if ($has_link) {

          /*$target_para = [
            'target_id' => $p->id(),
            'target_revision_id' => $p->getRevisionId()
          ];*/

          $target_para = $p->createDuplicate();
          $data = [
            'type' => 'video',
            'langcode' => 'en',
            'created' => $created_date,
            'changed' => $created_date,
            'uid' => '129',//$current_user->id(),
            'moderation_state' => 'published',
            'title' => $node->getTitle() . ' Video',
            'path' => [
              'alias' => $alias_path,
              'pathauto' => PathautoState::SKIP
            ],
            'field_project' => $node->id(),
            //field_project is a project node reference 
            'field_project_video' => $target_para,
            //'field_show_in_project_menu' => TRUE,
            /*'body' => [
              'value' => $source_header,
              'summary' => '',
              'format' => 'full_html'
            ]*/
          ];
          $target = Node::create($data);
          $target->save();
        }
        else {
          //\Drupal::logger('nyc_migrate')->notice('Project ' . $node->getTitle() . ' - empty Video link - skipping.');
        }
      }
    }
  }

}
