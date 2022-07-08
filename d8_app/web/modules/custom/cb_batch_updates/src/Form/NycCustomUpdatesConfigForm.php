<?php

namespace Drupal\nyc_custom_updates\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for this custom update.
 */
class NycCustomUpdatesConfigForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nyc_custom_updates_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'nyc_custom_updates.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('nyc_custom_updates.settings');
    $batchsize = $config->get('batchsize');
    $form['nyc_custom_updates'] = array(
      '#type' => 'fieldset',
      '#title' => t('nyc_custom_updates settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,  
    );
    $range = range(1, 100);
    $sizes = array_combine($range, $range);
    $form['nyc_custom_updates']['batchsize'] = [
      '#type' => 'select',
      '#attributes' => [
        'class' => ['pull-left'],
        'tabindex' => 0,
      ],
      '#default_value' => !empty($batchsize) ? $batchsize: 50,
      '#title' => t('Batch Size'),
      '#description' => t('Choose a batch size'),
      '#empty_value' => '',
      '#empty_option' => '-- Select --',
      '#options' => $sizes,
      '#required' => TRUE
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      $config_edit = \Drupal::configFactory()->getEditable('nyc_custom_updates.settings');
      $config_edit->set('batchsize', $form_state->getValue('batchsize'));
      $config_edit->save();
      parent::submitForm($form, $form_state);
  }
}