<?php

namespace Drupal\dh_utils\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'H-m-s' formatter.
 *
 * @FieldFormatter(
 *   id = "cb_duration_formatters_h_m_s",
 *   label = @Translation("H-m-s"),
 *   field_types = {
 *     "integer"
 *   }
 * )
 */
class CbDurationFormattersHms extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    foreach ($items as $delta => $item) {

      $element[$delta] = [
        '#markup' => $this->getHms($item->value),
      ];
    }

    return $element;
  }

  private function getHms($seconds) {
    //hours, minutes, and seconds
		$hrs = floor($seconds/3600);
		$remainder = ($seconds % 3600);
		$mins = floor($remainder / 60);
		$secs = ($remainder % 60);

    //leading zeroes if needed
    $secs = str_pad($secs, 2, "0", STR_PAD_LEFT);

    //leading zeroes if needed
    if(!empty($hrs)) {
      $mins = str_pad($mins, 2, "0", STR_PAD_LEFT); 
    }

    $hms = sprintf('%s:%s:%s', $hrs, $mins, $secs);
    if (empty($hrs)) {
      //ignore zero hours
      $hms = sprintf('%s:%s', $mins, $secs);
    }
    return $hms;
  }
}
