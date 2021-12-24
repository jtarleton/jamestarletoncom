<?php
namespace Drupal\onesip_role_promote\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\webformSubmissionInterface;
use Drupal\Core\Url;

/**
* Form submission handler.
*
* @WebformHandler(
*   id = "onesip_role_promote_role_promote_webform_handler",
*   label = @Translation("Onesip Role Promote - Additional Access Request handler"),
*   category = @Translation("Form Handler"),
*   description = @Translation("Processes user role request submissions for role promotions."),
*   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
*   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
* )
*/

class RolePromoteWebformHandler extends WebformHandlerBase {

 /**
  * {@inheritdoc}
  */
 public function defaultConfiguration() {
   return [];
 }

  /**
  * {@inheritdoc}
  *
 public function validateForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {


 }
 */

 /**
  * {@inheritdoc}
  */
 public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
    // Post data
    $values = $webform_submission->getData();
    $user_storage = \Drupal::entityManager()->getStorage('user');
    $requestor_uid = (int) $values['requestor_uid'];
    $new_roles = NULL;

    if (!empty($values['requested_role'])) {
      $new_roles = $values['requested_role'];
    }

    $user = \Drupal\user\Entity\User::load($requestor_uid);

    // check if user already has the role
    $roles = $user->getRoles();

    // check if user already has the role as a pending request
    $field_pending_roles = $user->get('field_pending_roles')->value;
    $current_pending_roles = (is_array($field_pending_roles)) ? $field_pending_roles : [$field_pending_roles];

    // send email?
    $email = $user->getEmail();

    // Set the field value new value.
    $user->set('field_pending_roles', $new_roles);
    $user->set('field_pending_roles_date_request', time());
    $user->save();


    // List out all roles.
    $all_possible_roles_labels = [];
    $all_possible_roles =  \Drupal\user\Entity\Role::loadMultiple();
    foreach ($all_possible_roles as $all_possible_role_id => $all_possible_role) {
      $all_possible_roles_labels[$all_possible_role_id] = $all_possible_role->get('label');
    }

    $saved_pending_roles = [];
    $saved_pending_roles_list = $user->get('field_pending_roles')->getValue();
    foreach($saved_pending_roles_list as $saved_pending_role) {
      $saved_pending_roles[$saved_pending_role['value']] = $saved_pending_role['value'];
    }


    $saved_pending_output = [];
    foreach($saved_pending_roles as $saved_pending_role) {
      $saved_pending_output[] = $all_possible_roles_labels[$saved_pending_role];
    }

    drupal_set_message(sprintf('Added pending roles %s', implode(',', $saved_pending_output)));
    return true;
 }
}
