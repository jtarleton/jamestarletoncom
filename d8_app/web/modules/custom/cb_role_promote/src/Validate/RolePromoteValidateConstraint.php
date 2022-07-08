<?php 
namespace Drupal\onesip_role_promote\Validate;

use Drupal\Core\Field\FieldException;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form API callback. Validate element value.
 */
class RolePromoteValidateConstraint {
    /**
     * Validates given element.
     *
     * @param array              $element      The form element to process.
     * @param FormStateInterface $formState    The form state.
     * @param array              $form The complete form structure.
     */
    public static function validate(array &$element, FormStateInterface $form_state, array &$form) {
      // $webform_key = $element['#webform_key'];
      // do some validation here...

      // Post data
      $values = $form_state->getValues();
      $user_storage = \Drupal::entityManager()->getStorage('user');
      $requestor_uid = (int) $values['requestor_uid'];
      $roles_to_add = NULL;


      if (!empty($values['requested_role'])) {
        foreach($values['requested_role'] as $requested_role_key => $requested_role) {
          if (!empty($requested_role)) {
            $roles_to_add[$requested_role] = $requested_role; 
          }
        }
      }

      $user = \Drupal\user\Entity\User::load($requestor_uid);

      // check if user already has the role
      $roles = $user->getRoles();

      // check if user already has the role as a pending request
      $field_pending_roles = $user->get('field_pending_roles')->value;
      $current_pending_roles = (is_array($field_pending_roles)) ? $field_pending_roles : [$field_pending_roles];

      $has_bo = in_array('business_owner', $roles); // || in_array('business_owner', $current_pending_roles);
      $has_ca = in_array('conten', $roles); // || in_array('conten', $current_pending_roles);
      $has_pm = in_array('pro', $roles); // || in_array('pro', $current_pending_roles);
      $is_admin = in_array('administrator', $roles);
      $is_previously_rejected = in_array('rejected_additional_access_', $roles);

      // Form ID
      //$webform = $this->getWebform();
      //$wid = $webform->id();

      // Type of role request
      $requests_bo = in_array('business_owner', $roles_to_add);  
      $requests_ca = in_array('conten', $roles_to_add);  
      $requests_pm = in_array('pro', $roles_to_add);  

      $new_roles[] = NULL;
      $error_msg = NULL;
      //Validate against rejected and admins
      if ($is_previously_rejected) {
        $error_msg = t('Users with a previously rejected access request should contact a Business Owner to use this feature.');
      }
      if ($is_admin) {
        $error_msg = t('Admin should not request additional roles through this feature.');
      }

      if ($requests_bo) {
        if (!$has_bo) {
          $new_roles[] = 'business_owner'; 
        }
        else {
          $error_msg = t('The user profile already has the Business Owner role.');
        }
      }

      if ($requests_ca) {
        if (!$has_ca) {
          $new_roles[] = 'conten'; 
        } 
        else {
          $error_msg = t('The user profile already has the Content Admin role.');
        }
      }

      if ($requests_pm) {
        if (!$has_pm) {
          $new_roles[] = 'pro'; 
        } 
        else {
          $error_msg = t('The user profile already has the Project Manager role.');
        }
      }


      if (isset($error_msg)) {
        $form_state->setError($element, $error_msg);
      } 
    }
}
