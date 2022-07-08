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
 * Class NycBetterFieldDescConfigurationFormListPage.
 */
class NycBetterFieldDescConfigurationFormListPage extends NycBetterFieldDescConfigurationForm {

  /**
   * @var string machine name for current content type 
   */
  protected $contentType;  

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

    $this->allContentTypes = array_keys(\Drupal::service('nyc_better_field_desc.nyc_better_field_desc_helper_fns')->getAllContentTypes());

    $this->cfg = $config_factory->getEditable('nyc_better_field_desc.nycbetterfielddescconfiguration' . $this->contentType);
  }
}