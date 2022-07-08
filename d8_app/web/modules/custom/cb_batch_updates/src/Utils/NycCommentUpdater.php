<?php 
namespace Drupal\nyc_custom_updates\Utils;

class NycCommentUpdater {
	/**
	 *  Get all Comments in need of an location summary update
	 * @param int
	 * @param int
	 */
	public function getAllEmptyLocSummaryComments() { 
		// SELECT cd.cid FROM comment_field_data cd 
		// JOIN  comment__field_comment_location_summary ls ON cd.cid = ls.entity_id
		// WHERE ls.field_comment_location_summary_value = '' AND ls.bundle = 'map_comments'
		
			/*
			$sql = 'SELECT cd.cid FROM {comment_field_data} cd 
			LEFT JOIN {comment__field_comment_location_summary} ls ON cd.cid = ls.entity_id
			WHERE ls.field_comment_location_summary_value = \'\' OR ls.entity_id IS NULL';

			$database = \Drupal::database();
			$query = $database->query($sql);
			$comment_ids = $query->fetchCol();
			return $comment_ids; */
		

		$query = \Drupal::database()->select('comment_field_data', 'cd');
		$query->leftJoin('comment__field_comment_location_summary', 'ls', 'cd.cid = ls.entity_id');

		// Let location summary be NULL, because the 
		// associated comment__field_comment_location_summary record may not exist yet
		$condition_or = new \Drupal\Core\Database\Query\Condition('OR');
		$condition_or->condition('field_comment_location_summary_value', '', '=');
		$condition_or->isNull('ls.entity_id');

		$result = $query->fields('cd', array('cid'))
			->condition($condition_or)
			->distinct(TRUE)
			->execute(); 
		$comment_ids = $result->fetchCol();    
		return $comment_ids; 
	}
	// Count all Comments in need of an location summary update
	public function getAllEmptyLocSummaryCommentsCt() {
		/* 
			$sql = 'SELECT COUNT(cd.cid) as ct FROM {comment_field_data} cd 
			LEFT JOIN {comment__field_comment_location_summary} ls ON cd.cid = ls.entity_id
			WHERE ls.field_comment_location_summary_value = \'\' OR ls.entity_id IS NULL';

			$database = \Drupal::database();
			$query = $database->query($sql);
			$ct_result = $query->fetchField();
		*/

		$query = \Drupal::database()->select('comment_field_data', 'cd');
		$query->leftJoin('comment__field_comment_location_summary', 'ls', 'cd.cid = ls.entity_id');
		
		// Let location summary be NULL, because the 
		// associated comment__field_comment_location_summary record may not exist yet
		$condition_or = new \Drupal\Core\Database\Query\Condition('OR');
		$condition_or->condition('field_comment_location_summary_value', '', '=');
		$condition_or->isNull('ls.entity_id');

		$ct_result = $query->fields('cd', array('cid'))
			->condition($condition_or)
			//->condition($condition)
			->distinct(TRUE)
			->countQuery()
			->execute()
			->fetchField(); 
		return (int)$ct_result;
	}
}