<?php

namespace Drupal\dh_podcasts\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'Social Media Link' formatter.
 *
 * @FieldFormatter(
 *   id = "dh_podcasts_social_media_link",
 *   label = @Translation("Social Media Link"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *     "text",
 *     "text_long",
 *     "text_with_summary"
 *   }
 * )
 */
class SocialMediaLinkFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'social_media_icon' => '<i class="fa fa-facebook" aria-hidden="true"></i>',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $elements['social_media_icon'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Social Media Icon'),
      '#default_value' => (!empty($this->getSetting('social_media_icon'))) ? $this->getSetting('social_media_icon') : '<i class="fa fa-facebook" aria-hidden="true"></i>'
      ,
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary[] = $this->t('Social Media Icon: @icon', ['@icon' => $this->getSetting('social_media_icon')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#markup' =>  $this->getSetting('social_media_icon') .' '. $item->value,
      ];
    }

    return $element;
  }

}
