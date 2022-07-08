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
 * Class NycBetterFieldDescConfigurationForm.
 */
class NycBetterFieldDescConfigurationForm extends ConfigFormBase {

  /**
   * Drupal\Core\Path\AliasManagerInterface definition.
   *
   * @var \Drupal\Core\Path\AliasManagerInterface
   */
  protected $pathAliasManager;

  /**
   * Drupal\Core\Extension\ThemeHandlerInterface definition.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * Drupal\Core\Extension\ModuleHandlerInterface definition.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * @var string machine name for content type 
   */
  protected $contentType, $fieldDescs;

  /**
   * Constructs a new NycBetterFieldDescConfigurationForm object.
   */
  public function __construct(
      ConfigFactoryInterface $config_factory,
      AliasManagerInterface $path_alias_manager,
      ThemeHandlerInterface $theme_handler,
      ModuleHandlerInterface $module_handler
    ) {
    parent::__construct($config_factory);
    $this->pathAliasManager = $path_alias_manager;
    $this->themeHandler = $theme_handler;
    $this->moduleHandler = $module_handler;
   
    $this->allContentTypes = array_keys(\Drupal::service('nyc_better_field_desc.nyc_better_field_desc_helper_fns')->getAllContentTypes());
    $current_path = \Drupal::service('path.current')->getPath();
    $current_path_alias = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
    $path_parts = explode('/',$current_path_alias);
    $content_type_part = end($path_parts);
    $content_type_index = array_search($content_type_part, $this->allContentTypes);
   
    $this->contentType = $this->allContentTypes[$content_type_index];

    $this->cfg = $config_factory->getEditable('nyc_better_field_desc.nycbetterfielddescconfiguration'
      . $this->contentType);
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('path.alias_manager'),
      $container->get('theme_handler'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'nyc_better_field_desc.nycbetterfielddescconfiguration' . $this->contentType
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nyc_better_field_desc_configuration_form_'. $this->contentType;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // machine name of the content type.
    $this->fieldDescs = $this->getFieldsFromCustomContentType($this->contentType);

    if (!empty($this->contentType)) {
      /*
        Page Field Descriptions Fieldset
      */
      $form['#title'] = 'Field Instructions';



      $form['instructions_group_1'] = [
        '#type' => 'fieldset',
        '#title' => $this->t(ucfirst($this->contentType) . ' Field Instructions'),
      ]; 
    }

    if (!empty($this->fieldDescs)) {
      foreach ($this->fieldDescs as $field) {
        // Sets form field title to Drupal field ID, but cleans up strings for 
        // readability 
        // (e.g "edit-field-city-wide-project" becomes "City Wide Project").
        $remove = ['edit-', 'field-', 'values', 
          'value', '-0-', '0', 
          'field', '-better-desc', 'better', 
          'duration   desc'
        ];
        $friendly_title = $field;
        foreach ($remove as $remove_item) {
          $friendly_title = str_replace($remove_item, ' ', $friendly_title);
        }
        
        $friendly_title = ucwords(str_replace('-', ' ', $friendly_title));
        $friendly_title = ucwords(str_replace('Duration   Desc', 'Duration', $friendly_title));
        $friendly_title = trim($friendly_title);
        $field = str_replace('title-0-value','title',$field);






        $form['instructions_group_1'][$field] = [
          '#type' => 'textarea',
          '#title' => $this->t($friendly_title),
          '#description' => $this->t('Enter the help text to be displayed below the title of the ' . $friendly_title . ' field.'),
          '#default_value' => $this->cfg->get($field),
        ]; 
      }
    }
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
    

    $nyc_better_field_desc_config = \Drupal::service('config.factory')->getEditable('nyc_better_field_desc.nycbetterfielddescconfiguration' . $this->contentType);




    $this->cfg->set('title', $form_state->getValue('title'));





    $this->cfg->set('better_desc_style_' . $this->contentType, 
        $form_state->getValue('better_desc_style_' . $this->contentType)
    );

    foreach ($this->fieldDescs as $field) {
      $this->cfg->set($field, $form_state->getValue($field));
    }

    $this->cfg->save();  


   parent::submitForm($form, $form_state);

  }

  /**
   * Gets field names for a paragraph type
   * @param string of paragraph machine name
   * @return array
   */
  private function getFieldsFromParagraphReference($paragraph_machine_name) {
    $paragraph_field_descs = [];
    switch ($paragraph_machine_name) {
      case 'document':
        foreach (array_keys(\Drupal::entityManager()->getFieldDefinitions('paragraph', 'document')) as $paragraph_field_name) {
          if (strpos($paragraph_field_name, 'field_') === 0) {
            $paragraph_field_name = str_replace('field_', 'field-', $paragraph_field_name);
            $paragraph_field_name = str_replace('_', '-', $paragraph_field_name);
            $paragraph_field_descs[$paragraph_field_name] = $paragraph_field_name;
          }
        }
        return $paragraph_field_descs;
      case 'video':
        foreach (array_keys(\Drupal::entityManager()->getFieldDefinitions('paragraph', 'video')) as $paragraph_field_name) {
          if (strpos($paragraph_field_name, 'field_') === 0) {
            $paragraph_field_name = str_replace('field_', 'field-', $paragraph_field_name);
            $paragraph_field_name = str_replace('_', '-', $paragraph_field_name);
            $paragraph_field_descs[$paragraph_field_name] = $paragraph_field_name;
          }
        }
        return $paragraph_field_descs;
      default:
        return $paragraph_field_descs;
    }
  }

  /** 
   * Return generated html ids from form fields.
   * @param string
   * @param string
   * @return array
   */
  private function getFieldsFromCustomContentType($bundle, $entity_type_id = 'node') {
    if (empty($bundle)) {
      return [];
    }
    
    // Find target node bundle (i.e. content type) from field definitions
    foreach (\Drupal::entityManager()->getFieldDefinitions($entity_type_id, $bundle) as $field_name => $field_definition) {
      if (!empty($field_definition->getTargetBundle())) {
        $content_type_machine_name = $field_definition->getTargetBundle();
        break;
      }
    }

    $better_desc_node = \Drupal::entityManager()->getStorage('node')->create(array(
          'type' => $content_type_machine_name,
        )
    );
    // Title is a built-in Drupal field value and needs to be added separately 
    $field_descs = ['title'];
    $field_descs[] = 'body';
    
    // Paragraph type fields are returned separately from content type fields
    $paragraph_field_descs = [];

    if ($content_type_machine_name === 'map') {
      $field_descs[] = 'feedback-map';
    }
    else {
      //If any content type has paragraph references in their fields it has to be added in the below if condition
      if ($content_type_machine_name === 'document' || $content_type_machine_name === 'video' || $content_type_machine_name === 'news' || $content_type_machine_name === 'project_link') {
        // For any paragraph reference fields, get fields from the paragraph type 
        $paragraph_field_descs = $this->getFieldsFromParagraphReference($content_type_machine_name);
      }
    }

    $better_desc_content_type_form = \Drupal::service('entity.form_builder')->getForm($better_desc_node, 'default');

    $better_desc_content_type_form_fields = array_keys($better_desc_content_type_form);
    foreach ($better_desc_content_type_form_fields as $better_desc_content_type_form_field) {
      if (($content_type_machine_name == 'document' && $better_desc_content_type_form_field == 'field_document')
        || ($content_type_machine_name == 'video' && $better_desc_content_type_form_field == 'field_project_video')) {
        continue;
      }
      else if (strpos($better_desc_content_type_form_field, 'field_') === 0) {
        $field_descs[] = $better_desc_content_type_form[$better_desc_content_type_form_field]['widget']['#attributes']['data-drupal-selector'];
      }
    }

    //$field_descs = array_merge($field_descs, $paragraph_field_descs);
    //PI2-1245 remove extra widget field showing in config form for BO role
    $field_descs_widget=array_diff($field_descs,array('edit-widget'));
    $field_descs = array_merge($field_descs_widget, $paragraph_field_descs);

    return array_unique($field_descs);
  }
}