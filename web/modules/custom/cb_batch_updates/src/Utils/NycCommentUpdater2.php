<?php 
namespace Drupal\nyc_custom_updates\Utils;

class NycCommentUpdater {
	/**
	 *  Get all Comments in need of an location summary update
	 * @param int
	 * @param int
	 */
	public function getAllEmptyLocSummaryComments($offset, $limit) { 
		
		// SELECT cd.cid FROM comment_field_data cd 
		// JOIN  comment__field_comment_location_summary ls ON cd.entity_id = ls.entity_id
		// WHERE ls.field_comment_location_summary_value = '' AND ls.bundle = 'map_comments'


		$cmt_storage = \Drupal::entityTypeManager()->getStorage('comment');
		$comment_ids =$cmt_storage->getQuery()
	        ->condition('comment_type', 'map_comments')
	        ->range($offset, $limit)
	        //->sort('cid', 'DESC')
	        ->execute();

	        /* 
		$query = \Drupal::database()->select('comment_field_data', 'cd');
		$query->join('comment__field_comment_location_summary', 'ls', 'cd.cid = ls.entity_id AND ls.bundle = :bundle', array(':bundle' => 'map_comments'));
		$result = $query->fields('cd', array('cid'))
			->condition('comment_type', 'map_comments', '=')
			->condition('field_comment_location_summary_value', '', '=')
			->distinct(TRUE)
			->orderBy('cd.cid')
			->range($offset, $limit)
			->execute(); 
		$comment_ids = $result->fetchCol(); */
		$updates = [];
  		if (!empty($comment_ids)){
  			 
			$comments = $cmt_storage->loadMultiple($comment_ids);

			//foreach($comments as $comment) {
				//if (empty($comment->get('field_comment_location_summary')->value)) {
				//	$updates[$comment->id()] = $comment;
				//}
			//}
			return $comments;
		}
	}
	// Count all Comments in need of an location summary update
	public function getAllEmptyLocSummaryCommentsCt() {
	//	$cids = self::getAllEmptyLocSummaryComments(0, 999999);
		$ct_result = 999999; // count($cids);
		/* 
		 $query = \Drupal::database()->select('comment_field_data', 'cd');
		 $query->join('comment__field_comment_location_summary', 'ls', 'cd.cid = ls.entity_id AND ls.bundle = :bundle', array(':bundle' => 'map_comments'));
		 $ct_result = $query->fields('cd', array('cid'))
			->condition('comment_type', 'map_comments')
			->condition('field_comment_location_summary_value', '', '=')
			->orderBy('cd.cid')
			->distinct(TRUE)
			->countQuery()
			->execute()
			->fetchField(); */

		return (int)$ct_result;
	}
}