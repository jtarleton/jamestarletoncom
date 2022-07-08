<?php

namespace Drupal\cb_automobile\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure cb_automobile settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cb_automobile_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cb_automobile.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['automobile']['make'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Make'),
      '#default_value' => $this->config('cb_automobile.settings')->get('make'),
    ];
    $form['automobile']['model'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Model'),
      '#default_value' => $this->config('cb_automobile.settings')->get('model'),
    ];
    $form['automobile']['trim'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Trim'),
      '#default_value' => $this->config('cb_automobile.settings')->get('trim'),
    ];
    $form['automobile']['year'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Year'),
      '#default_value' => $this->config('cb_automobile.settings')->get('year'),
    ];
    $form['automobile']['vin'] = [
      '#type' => 'textfield',
      '#title' => $this->t('VIN'),
      '#default_value' => $this->config('cb_automobile.settings')->get('vin'),
    ];
    $form['automobile']['reg_state'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Registration State'),
      '#default_value' => $this->config('cb_automobile.settings')->get('reg_state'),
    ];
    $form['automobile']['reg_plate'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Registration Plate Number'),
      '#default_value' => $this->config('cb_automobile.settings')->get('reg_plate'),
    ];
    $form['automobile']['reg_info'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Registration Info'),
      '#default_value' => $this->config('cb_automobile.settings')->get('reg_info'),
    ];
    $form['automobile']['tires'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tires'),
      '#default_value' => $this->config('cb_automobile.settings')->get('tires'),
    ];
    $form['automobile']['engine_info'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Engine Info'),
      '#default_value' => $this->config('cb_automobile.settings')->get('engine_info'),
    ];
    
    $form['automobile']['maintenance_history_info'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Maintenance History Info'),
      '#default_value' => $this->config('cb_automobile.settings')->get('maintenance_history_info'),
    ];
    $form['automobile']['other_notes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Notes'),
      '#default_value' => $this->config('cb_automobile.settings')->get('other_notes'),
    ];
    $form['automobile']['insurer'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Insurer'),
      '#default_value' => $this->config('cb_automobile.settings')->get('insurer'),
    ];
    $form['automobile']['ins_info'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Insurance Policy Info'),
      '#default_value' => $this->config('cb_automobile.settings')->get('ins_info'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    //if ($form_state->getValue('example') != 'example') {
      //$form_state->setErrorByName('example', $this->t('The value is not correct.'));
    //}
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('cb_automobile.settings')
      ->set('make', $form_state->getValue('make'))
      ->set('model', $form_state->getValue('model'))
      ->set('trim', $form_state->getValue('trim'))
      ->set('year', $form_state->getValue('year'))
      ->set('vin', $form_state->getValue('vin'))
      ->set('reg_state', $form_state->getValue('reg_state'))
      ->set('reg_plate', $form_state->getValue('reg_plate'))
      ->set('reg_info', $form_state->getValue('reg_info'))
      ->set('tires', $form_state->getValue('tires'))
      ->set('engine_info', $form_state->getValue('engine_info'))
      ->set('maintenance_history_info', $form_state->getValue('maintenance_history_info'))
      ->set('other_notes', $form_state->getValue('other_notes'))
      ->set('insurer', $form_state->getValue('insurer'))
      ->set('ins_info', $form_state->getValue('ins_info'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
