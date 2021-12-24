<?php

namespace Drupal\nyc_migrate\Entity;

use Drupal\node\Entity\Node;
use Drupal\pathauto\PathautoState;
use Drupal\nyc_migrate\Utils\NycMigrateHelperFns;

trait Listingpage {
  
  /*
    1. Create events, documents, video listing page for Projects that have it enabled
    2. Move events, documents, video header from Project node to the new content type 'List Page'
   */
  public static function createFromProject(
  \Drupal\node\Entity\Node $node, $list_page_type) {

    $show_in_project_menu_keys = [
      'document' => 13, //16,
      'event' => 14, //13,
      'video' => 16 //14
    ];
    $checked = [];

    $created_date = time();

    $current_user = \Drupal::currentUser();
    $source_header = '';

    foreach ($node->field_project_menu->getValue() as $checkbox) {
      $checked[] = (int) $checkbox['target_id'];
    }

    $show_in_project_menu = in_array($show_in_project_menu_keys[self::$list_page_type], $checked);
    $project_id = $node->id();
    //$has_list_page = NycMigrateHelperFns::hasListpage($project_id);
    $type = (self::$list_page_type === 'document') ? 'presentation' : self::$list_page_type;
    $fld = 'field_' . $type . 's_header';
    // header
    if ($node->hasField($fld)) {      
      $source_header = $node->$fld->value;
      $source_header_is_empty = empty($source_header);
      \Drupal::logger('nyc_migrate')->notice('source header empty ' . $node->getTitle() . "|". $source_header_is_empty);
    }
    else {
      \Drupal::logger('nyc_migrate')->notice('Missing field_' . $type . 's_header on project ' . $node->getTitle());
    }
    
    if ($source_header_is_empty && empty($show_in_project_menu)) {
      // check if project has that type of content, otherwise skip creating list pages
      $has_content = NycMigrateHelperFns::hasContent($project_id, $list_page_type);
      if (empty($has_content)) {
        \Drupal::logger('nyc_migrate')->notice('Skipping list page for project ' . $node->getTitle());
        return;       
      }
    }

    // \Drupal::logger('nyc_migrate')->notice('Begin Entity::create from ' . var_export($node->getTitle(), TRUE));

    $data['title'] = '';
    $data['field_project'] = NULL;
    $title = '';
    switch ($list_page_type) {
      case 'document':
        $title = 'NYC Documents';
        break;
      case 'video':
        $title = 'NYC Videos';
        break;
      case 'event':
        $title = 'NYC Events';
        break;
      default:
        $title = 'NYC List Page';
        break;
    }

    $data = [];
    $nid = NULL;
    //if (!empty($node->id())) {
    $data['title'] = $title; //$node->getTitle() . ' '. ucfirst(self::$list_page_type) .'s';
    $data['field_project'] = ['target_id' => $node->id()];
    $nid = $node->id();
    //}

    $data += [
      'body' => '',
      'type' => 'list_page',
      'langcode' => 'en',
      'created' => $created_date,
      'changed' => $created_date,
      'uid' => '129', //$current_user->id(),
      'moderation_state' => 'published',
      'title' => '',
      'path' => [
        'alias' => self::generateUrlAlias($nid),
        'pathauto' => PathautoState::SKIP
      ],
      //'field_show_in_project_menu'=>$show_in_project_menu,
      'field_list_page_type' => $list_page_type,
      'field_header' => [
        'value' => $source_header,
        'summary' => '',
        'format' => 'full_html'
      ]
    ];
    // This check does not take into account the list page type exists or not for project, so commenting it
    //if(!$has_list_page) {      // !$source_header_is_empty
    $target = Node::create($data);
    $target->set('field_header', $source_header);
    $target->setPublished($show_in_project_menu);
    $target->field_header->format = 'full_html';
    $status = $target->save();
    /* }
      else {
      if($has_list_page) {
      \Drupal::logger('nyc_migrate')->notice('List page for project ' . $nid . ' already exists...skipping.');
      }
      if($source_header_is_empty) {
      \Drupal::logger('nyc_migrate')->notice('Source header is empty...skipping.');
      }
      // \Drupal::logger('nyc_migrate')->notice('Entity::save status  '
      } */
  }

  public static function generateUrlAlias($nid) {
    $type = (self::$list_page_type === 'document') ? 'presentation' : self::$list_page_type;

    if (!empty($nid)) {
      $alias_path = '/project/' . $nid . '/' . $type . 's';
      $path = \Drupal::service('path.alias_storage')->save($alias_path, '/en');
      return $alias_path;
    }
  }

}
