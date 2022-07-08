<?php

namespace Drupal\nyc_better_field_desc\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Path\AliasManagerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Class NycBetterFieldDescConfigurationFormProject
 */
class NycBetterFieldDescConfigurationFormStyling extends NycBetterFieldDescConfigurationForm {
  /**
   * @var string machine name for content type 
   */
  protected $contentType = NULL; 

  /**
   * Constructs a new NycBetterFieldDescConfigurationForm object.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
      AliasManagerInterface $path_alias_manager,
    ThemeHandlerInterface $theme_handler,
    ModuleHandlerInterface $module_handler
    ) {
    parent::__construct($config_factory, $path_alias_manager, $theme_handler, $module_handler);
    $this->contentType = NULL;
    $this->allContentTypes = array_keys(\Drupal::service('nyc_better_field_desc.nyc_better_field_desc_helper_fns')->getAllContentTypes());
    $this->cfg = $config_factory->getEditable('nyc_better_field_desc.nycbetterfielddescconfigurationstyling');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nyc_better_field_desc_configuration_form_styling';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['instructions_group_0'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Styling'),
    ];

    // style select options
    $better_desc_style_opts = [
      'nycbetterdesc' => 'Black',
      'nycbetterdesc2' => 'Gray',
      'nycbetterdesc3' => 'Black Italic',
      'nycbetterdesc4' => 'Gray Italic',

      'nycbetterdesc7' => 'Blue',
      'nycbetterdesc8' => 'Blue Italic',
    ];
    $saved_style = $this->cfg->get('better_desc_style');

    // If form_state value is empty and a saved value exists, use it as default.
    $default_better_desc_style = !empty($saved_style) ? $saved_style  : '';

    $form['instructions_group_0']['better_desc_style'] = [
      '#type' => 'select',
      '#title' => t('Select style'),
      '#description' => t('Select a style to apply to the help text.'),
      '#prefix' => '<div id="select_wrapper">',
      '#suffix' => '</div>',
      '#options' => $better_desc_style_opts,
      '#default_value' => isset($better_desc_style_opts[$form_state->getValue('better_desc_style')]) 
        ? $better_desc_style_opts[$form_state->getValue('better_desc_style')] 
        : $default_better_desc_style,
      '#required' => TRUE,
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
    $this->cfg->set('better_desc_style', $form_state->getValue('better_desc_style'));
    $this->cfg->save();  
    parent::submitForm($form, $form_state);
  }
}