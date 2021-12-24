<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\node\Entity\Node;
use \Drupal\nyc_custom_updates\Utils\NycCommentUpdater;
use \Drupal\comment\Entity\Comment;

function nyc_custom_updates_do_update($operations=[], &$context) {
  $i=0;

  foreach ($operations as $comment) { 



    if (!empty($comment) && $comment instanceof \Drupal\comment\Entity\Comment) { 
      //if ($comment->hasField('field_comment_location_summary')) {
      // Location summary generated from either lat/lon for pin-drop comments or
      // directly from address for accessible comments.
      $location_summary = $comment->get('field_comment_location_summary')->value;

      //if (empty($location_summary)) {
      try {
        $nyc_map_location_services = \Drupal::service('nyc_map.nyc_map_location_services');
        $summary = $nyc_map_location_services->generateLocationSummary($comment);
        if (empty($summary)) {


          $lat = $comment->get('field_map_comment_x')->value;
          $lon = $comment->get('field_map_comment_y')->value;

          $notice = NULL;
          $is_508_comment = intval($comment->get('field_map_comment_508_status')->getValue()[0]['value']);
          // For Ac Form-submitted comments
          if ($is_508_comment) {
            $field_map_comment_address = $comment->get('field_map_comment_address')->value;
            // Commenting this notice for 508 comments, as it fills the database logs on running updates
            //$notice = 'Nyc_custom_updates - empty summary returned for comment ID ' . $comment->id(). ' . field_map_comment_address is empty. Coordinates are '. $lat .' and ' . $lon;
            $notice = '';
          }
          if (!isset($notice)) {
            $notice = 'Nyc_custom_updates - empty summary returned for comment ID ' . $comment->id(). '. Tried coordinates ' . $lat . ' and ' . $lon; 
          }
        }
      } catch (Exception $e) { 
        
        // Need to improve error handling, but for now check for empty value post-update
        $summary = $e->getMessage();
        $notice = 'Nyc_custom_updates - comment ID ' . $comment->id() .' Exception. Details: ' . $e->getMessage() ;
      }
      if (empty($summary) && !empty($notice)) {
        
        \Drupal::logger('nyc_custom_updates')->notice($notice);
        
      }
      $comment->set('field_comment_location_summary', $summary);
      $comment->save();
      // Increment currently processed entities.
      $i++;
    }
 
    // A message to display during the update
    //$context['message'] = 'Processed ' . $i;     
  }
}