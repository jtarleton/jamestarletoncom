<?php

namespace Drupal\nyc_migrate\Entity;

use Drupal\node\Entity\Node;
use Drupal\pathauto\PathautoState;

class Document extends Node {

  use Listingpage;

  public static function createFromProject(\Drupal\node\Entity\Node $node) {
    $created_date = time();

    $current_user = \Drupal::currentUser();

    // \Drupal::logger('nyc_migrate')->notice('Begin Document Entity::create from ' . var_export($node->getTitle(), TRUE));

    /*$source_header = '';
    if ($node->hasField('field_presentations_header')) {
      $source_header = $node->field_presentations_header->value;
    }
    else {
      //\Drupal::logger('nyc_migrate')->notice('Missing field field_presentations_header on project '.$node->getTitle());
    }*/

    $data = [];
    if (!empty($node->id())) {
      $alias_path = '';
      $data['title'] = $node->getTitle() . ' Document';
      $data['field_project'] = ['target_id' => $node->id()];

      //$path = \Drupal::service('path.alias_storage')->save($alias_path, '/en');
    }

    $source_paragraph = $node->field_project_documents->getValue();

    // \Drupal::logger('nyc_migrate')->notice('para ct ' . count($source_paragraph));
    $paras = [];

    $target_paras = [];
    $i = 0;

    // Loop through the result set.
    foreach ($source_paragraph as $element) {
      $p = \Drupal\paragraphs\Entity\Paragraph::load($element['target_id']);
      if (!empty($p)) {
        //paragraph will have the below fields:
        //field_document_description
        //field_document_document  - the actual file data
        //field_document_title
        //field_show_in_list - a checkbox
        //if(!empty($p->field_document_document)) {
        //   $file = $p->field_document_document->getValue();
        //}

        if (!empty($p->field_document_title) && !empty($p->field_document_document) && !empty($p->field_document_description) &&
          empty($p->field_document_title->getValue()) && empty($p->field_document_document->getValue()) && empty($p->field_document_description->getValue())) {
          continue;
        }

        if (!empty($p->field_document_title)) {
          $title = $p->field_document_title->getValue();
        }
        /*$target_id = NULL;
        //$p->is_new = TRUE;
        if (!empty($p)) {
          $target_id = $p->id();
        }*/

        $target_para = $p->createDuplicate();

        /*$target_para = [
          'target_id' => $target_id,
          'field_document_title' => [
            'value' => $title
          ],
          'target_revision_id' => $p->getRevisionId()
        ];*/

        /* if (!empty($p->field_show_in_list) && !empty($p->field_show_in_list->getValue()[0]['value'])) {
          $i++;
          $p->set('field_order', $i);
          } */

        $data = [
          'type' => 'document',
          'langcode' => 'en',
          'created' => $created_date,
          'changed' => $created_date,
          'uid' => '129',
          'moderation_state' => 'published',
          'title' => $node->getTitle() . ' Document',
          'path' => [
            'alias' => $alias_path,
            'pathauto' => PathautoState::SKIP
          ],
          'field_project' => $node->id(),
          //field_project is a project node reference 
          'field_document' => $target_para,
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
    }

    // \Drupal::logger('nyc_migrate')->notice('para saved' . var_export($target_paras, 1));
    // \Drupal::logger('nyc_migrate')->notice('Document Entity::save status  ' . var_export($status, TRUE));
  }

}
