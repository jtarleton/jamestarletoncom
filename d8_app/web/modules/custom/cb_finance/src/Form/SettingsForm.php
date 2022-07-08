<?php

namespace Drupal\cb_finance\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure cb_finance settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cb_finance_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cb_finance.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Income
    $form['paycheck'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Paycheck - Primary Employment'),
      '#default_value' => $this->config('cb_finance.settings')->get('paycheck'),
    ];

    // Expenses
    $form['rent_or_mortgage_pmt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('rent or mortgage pmt'),
      '#default_value' => $this->config('cb_finance.settings')->get('rent_or_mortgage_pmt'),
    ];
    $form['food'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Food'),
      '#default_value' => $this->config('cb_finance.settings')->get('food'),
    ];
    $form['internet_service'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Internet Bill'),
      '#default_value' => $this->config('cb_finance.settings')->get('internet_service'),
    ];
    $form['electric'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Electricity and Natural Gas Bill'),
      '#default_value' => $this->config('cb_finance.settings')->get('electric'),
    ];
    $form['auto_ins'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Auto Insurance Bill'),
      '#default_value' => $this->config('cb_finance.settings')->get('auto_ins'),
    ];
    $form['auto_fuel_repairs'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Auto Fuel and Repairs/Maintenance'),
      '#default_value' => $this->config('cb_finance.settings')->get('auto_fuel_repairs'),
    ];
    $form['cell_service'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mobile Phone Bill'),
      '#default_value' => $this->config('cb_finance.settings')->get('cell_service'),
    ];
    $form['haircuts'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Haircuts'),
      '#default_value' => $this->config('cb_finance.settings')->get('haircuts'),
    ];
    $form['coffee_beans'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Coffee Beans'),
      '#default_value' => $this->config('cb_finance.settings')->get('coffee_beans'),
    ];
    $form['laundry_dry_cleaning'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Laundry and Dry Cleaning'),
      '#default_value' => $this->config('cb_finance.settings')->get('laundry_dry_cleaning'),
    ];



    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('example') != 'example') {
      $form_state->setErrorByName('example', $this->t('The value is not correct.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('cb_finance.settings')
      ->set('paycheck', $form_state->getValue('paycheck'))
      ->set('rent_or_mortgage_pmt', $form_state->getValue('rent_or_mortgage_pmt'))
      ->set('food', $form_state->getValue('food'))
      ->set('internet_service', $form_state->getValue('internet_service'))
      ->set('electric',$form_state->getValue('electric'))
      ->set('auto_ins', $form_state->getValue('auto_ins'))
      ->set('auto_fuel_repairs', $form_state->getValue('auto_fuel_repairs'))
      ->set('cell_service', $form_state->getValue('cell_service'))
      ->set('haircuts', $form_state->getValue('haircuts'))
      ->set('coffee_beans', $form_state->getValue('coffee_beans'))
      ->set('laundry_dry_cleaning', $form_state->getValue('laundry_dry_cleaning'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
