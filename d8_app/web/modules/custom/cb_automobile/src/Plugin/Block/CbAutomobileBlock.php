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
$html = '';
$cb_automobile_config = \Drupal::config('cb_automobile.settings');
    $html .= '<table class="table table-bordered"><tr><td> ' .$cb_automobile_config->get('make').'</td></tr>';
    $html .= '<tr><td> ' . $cb_automobile_config->get('model').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('trim').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('year').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('vin').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('reg_state').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('reg_plate').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('reg_info').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('engine_info').'</td></tr>';

    $html .= '<tr><td> ' .$cb_automobile_config->get('other_notes').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('maintenance_history_info').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('insurer').'</td></tr>';
    $html .= '<tr><td> ' .$cb_automobile_config->get('ins_notes') .'</td></tr></table>';
    $build['content'] = [
      '#markup' => $this->t($html),
    ];
    return $build;
  }

}
