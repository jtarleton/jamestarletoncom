<?php

namespace Drupal\onesip_role_promote\Plugin\Action;

use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;

use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
/**
 * Custom action to update roles
 *
 * @Action(
 *   id = "onesip_role_promote_process_access_request_action",
 *   label = @Translation("Process Additional Access Request"),
 *   type = "",
 *   confirm = FALSE,
 *   requirements = {
 *     "_permission" = "access content",
 *     "_custom_access" = FALSE,
 *   },
 * )
 */
class ProcessAccessRequestAction extends ViewsBulkOperationsActionBase {

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




    $role_to_grant =$this->context['exposed_input']['field_pending_roles_value'];
    //  $this->configuration['process_access_config_setting'];
 
    $role_added = 0;
    $pending_role_remainder = 0;
    if ($entity->getEntityTypeId() === 'user') {
      $saved_pending_roles_list = $entity->get('field_pending_roles')->getValue();
      foreach($saved_pending_roles_list as $saved_pending_role) {
        $saved_pending_roles[$saved_pending_role['value']] = $saved_pending_role['value'];
      }

      if(is_array($saved_pending_roles)){
        foreach ($saved_pending_roles as $saved_pending_role) {
          if($saved_pending_role === $role_to_grant) {
            $entity->addRole($saved_pending_role);
            if (count($saved_pending_roles) > 0) {
              $pending_role_remainder = count($saved_pending_roles) - 1; 
            }
            else {
              $pending_role_remainder = 0;
            }
            $role_added++;
          }
        }

        if (!empty($role_added)) { 
        // /  $role_to_grant_key = array_search($role_to_grant, $saved_pending_roles);
          unset($saved_pending_roles[$role_to_grant]); 
          //die(var_export($saved_pending_roles,1));
          $revised_pending_roles = array_values($saved_pending_roles);
        }
      }
      else {
        if($saved_pending_roles === $role_to_grant) {
          $entity->addRole($saved_pending_roles); 
          $pending_role_remainder = 0;
          $revised_pending_roles = NULL;
          $role_added++;

        }
      }
      $entity->bulk_update = TRUE;
      // Remove the pending role we just granted.
      if (!empty($role_added)) {
        $entity->set('field_pending_roles', $revised_pending_roles); 
      }
      $entity->save();
      

      if (!empty($role_added)) {
        $success_message = sprintf('Added 1 %s role of %s pending for %s', $all_possible_roles_labels[$role_to_grant], $pending_role_remainder + 1, $entity->label());
        drupal_set_message($success_message);
      }
    }

    /* 

    Configuration array resides in the action object $configuration parameter and can be retrieved simply by using $this->configuration in the action context. Both the preconfiguration and the end user configuration resides there


    The $this->context['sandbox'] variable contains batch progress data and can be used to pass additional data to subsequent execute operations (when execute method is used) or batches (when executeMultiple method is used).

    */

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

  /**
   * {@inheritdoc}

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

/* 



    $user_storage = \Drupal::entityManager()->getStorage('user');
    $requestor_uid = (int) $values['requestor_uid'];
    $roles_to_add = NULL;
    if (!empty($values['requested_role'])) {
      $roles_to_add = $values['requested_role']; 
    }
    
    
    $user = \Drupal\user\Entity\User::load($requestor_uid);

    // check if user already has the role
    $roles = $user->getRoles();

    $has_bo = in_array('business_owner', $roles);
    $has_ca = in_array('conten', $roles);
    $has_pm = in_array('pro', $roles);



 */


/*

    $possible_roles = [
'business_owner' => 'Business Owner','conten' => 'Content Admin', 'pro' => 'Project Manager'
    ];


    $form['process_access_config_setting'] = [
      '#title' => t('Process Onesip access setting pre-execute'),
      '#type' => 'radios',
      '#options' => $possible_roles,
      


      '#default_value' => $form_state->getValue('process_access_config_setting'),
    ];
    return $form; 
  }
   *

   * {@inheritdoc}

  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {

  }



  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['process_access_config_setting'] = $form_state->getValue('process_access_config_setting');
  }   */

  // The remaining methods of the action class..
}
