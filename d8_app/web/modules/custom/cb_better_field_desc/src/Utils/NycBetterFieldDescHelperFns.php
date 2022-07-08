<?php 
namespace Drupal\nyc_better_field_desc\Utils;

class NycBetterFieldDescHelperFns {

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

	/**
	 *
	 * @return Array of machine names for labels
	 */
	public static function getContentTypeMachineNames($selection = ['project','map', 'overview_page', 'document', 'news', 'event', 'video', 'project_link', 'list_page','page']) {
		$selected_content_types = [];
		$all_content_types = array_keys(\Drupal::service('nyc_better_field_desc.nyc_better_field_desc_helper_fns')->getAllContentTypes());
		foreach($selection as $selected) {
			$content_type_index = array_search($selected, $all_content_types);
			$selected_content_types[] = $all_content_types[$content_type_index];
		}
		return $selected_content_types;
	}
	/**
	 *
	 * @return Array of machine names
	 */
	public static function getContentTypeMachineName($selected = 'project') {
		$all_content_types = array_keys(\Drupal::service('nyc_better_field_desc.nyc_better_field_desc_helper_fns')->getAllContentTypes());
		$content_type_index = array_search($selected, $all_content_types);
		$selected_content_type = $all_content_types[$content_type_index];
		return $selected_content_type;
	}


	public static function getContentTypeByCurrentPath($current_path) {
		$all_content_types = array_keys(\Drupal::service('nyc_better_field_desc.nyc_better_field_desc_helper_fns')->getAllContentTypes());
  		$all_content_types_keys = array_keys($all_content_types);

	    $current_path_alias = \Drupal::service('path.alias_manager')->getAliasByPath($current_path); 
	    $path_parts = explode('/',$current_path_alias);
	    if(is_array($path_parts))
	    $content_type_part = end($path_parts); 
	    $content_type_index = array_search($content_type_part, $all_content_types); 
	    return isset( $all_content_types[ $content_type_index]) ? $all_content_types[ $content_type_index] : FALSE;
	}


	/**
	 * Maps a Drupal element ID to a saved editable config field value referenced in:

	  nyc_better_field_desc.nycbetterfielddescconfigurationproject
	  nyc_better_field_desc.nycbetterfielddescconfigurationmap

	  @todo resolve mismatched naming conventions, so we can remove this kind of processing.
	*/
	public static function elementIdToField($element_id) {

        $element_id= str_replace('edit-field-document-0-subform-','', $element_id);
        $element_id= str_replace('edit-field-document-subform-','', $element_id);
        $element_id= str_replace('edit-field-project-video-0-subform-','', $element_id);
        $element_id= str_replace('edit-field-project-video-subform-','', $element_id);


		$fld = str_replace('-field-document-subform-','',
			str_replace('-field-document-subform-','',
            str_replace('-field-project-video-subform-','',
			str_replace('--wrapper','', 
		      str_replace('-0-target-id', '', 
		        str_replace('-value', '', 
		          str_replace('-0', '', 
		            str_replace('-values', '', 
		              str_replace('-0-value', '', 
		                str_replace('-0-value-date', '', 
		                  (string) $element_id)
		              )
		            )                                                           
		          )
		        )
		      )
		    )
			)
			)
    	);
    	return $fld;
	}
}
