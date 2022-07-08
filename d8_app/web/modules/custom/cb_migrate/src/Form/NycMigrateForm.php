<?php

namespace Drupal\nyc_migrate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for this custom update.
 */
class NycMigrateForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nyc_migrate_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'nyc_migrate.nycmigratecconfiguration',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('nyc_migrate.nycmigratecconfiguration');
    $batchsize = $config->get('batchsize');
    $form['nyc_custom_updates'] = array(
      '#type' => 'fieldset',
      '#title' => t('nyc_migrate settings'),
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
      '#default_value' => !empty($batchsize) ? $batchsize : 5,
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
    $config_edit = \Drupal::configFactory()->getEditable('nyc_migrate.nycmigratecconfiguration');
    $config_edit->set('batchsize', $form_state->getValue('batchsize'));
    $config_edit->save();
    parent::submitForm($form, $form_state);
  }

}
