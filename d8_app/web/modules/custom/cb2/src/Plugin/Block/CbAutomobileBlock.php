<?php

namespace Drupal\cb_automobile\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "cb_automobile",
 *   admin_label = @Translation("CbAutomobile"),
 *   category = @Translation("cb_automobile")
 * )
 */
class CbAutomobileBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $cb_automobile_config = \Drupal::config('cb_automobile.settings');
    $cb_automobile_make = $cb_automobile_config->get('make');
    $html = '<div>' . $cb_automobile_make . '</div>';
    $build['content'] = [
      '#markup' => $this->t($html),
    ];
    return $build;
  }

}
