<?php

namespace Drupal\onesip_role_promote\Plugin\Action;

use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;

use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
/**
 * Custom action to add reject additional access role for users with pending roles.
 *
 * @Action(
 *   id = "onesip_role_promote_reject_access_request_action",
 *   label = @Translation("Reject Additional Access Request"),
 *   type = "",
 *   confirm = FALSE,
 *   requirements = {
 *     "_permission" = "access content",
 *     "_custom_access" = FALSE,
 *   },
 * )
 */
class RejectAccessRequestAction extends ViewsBulkOperationsActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    /*
     * All config resides in $this->configuration.
     * Passed view rows will be available in $this->context.
     * Data about the view used to select results and optionally
     * the batch context are available in $this->context or externally
     * through the public getContext() method.
     * The entire ViewExecutable object  with selected result
     * rows is available in $this->view or externally through
     * the public getView() method.

     */

    // List out all roles.
    $all_possible_roles_labels = [];
    $all_possible_roles =  \Drupal\user\Entity\Role::loadMultiple();
    foreach ($all_possible_roles as $all_possible_role_id => $all_possible_role) {
      $all_possible_roles_labels[$all_possible_role_id] = $all_possible_role->get('label');
    }

    $role_to_grant = 'rejected_additional_access_'; 
 
    $pending_role_remainder = 0;

    if ($entity->getEntityTypeId() === 'user') {
      $saved_pending_roles_list = $entity->get('field_pending_roles')->getValue();
      foreach($saved_pending_roles_list as $saved_pending_role) {
        $saved_pending_roles[$saved_pending_role['value']] = $saved_pending_role['value'];
      }
      $pending_role_remainder = count($saved_pending_roles); 
      $entity->addRole($role_to_grant); 
      $entity->save();
      $success_message = sprintf('%s added to %s.', $all_possible_roles_labels[$role_to_grant], $entity->label());
      drupal_set_message($success_message);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    if ($object->getEntityType() === 'node') {
      $access = $object->access('update', $account, TRUE)
        ->andIf($object->status->access('edit', $account, TRUE));
      return $return_as_object ? $access : $access->isAllowed();
    }

    // Other entity types may have different
    // access methods and properties.
    return TRUE;
  }
}
