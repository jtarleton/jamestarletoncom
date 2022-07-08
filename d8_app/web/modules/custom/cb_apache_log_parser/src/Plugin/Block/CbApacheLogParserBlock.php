<?php

namespace Drupal\cb_apache_log_parser\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an cb_apache_log_parser block.
 *
 * @Block(
 *   id = "cb_apache_log_parser_cb_apache_log_parser",
 *   admin_label = @Translation("cb_apache_log_cb_apache_log_parser"),
 *   category = @Translation("cb_apache_log_parser")
 * )
 */
class CbApacheLogParserBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $cb_apache_log_parser = \Drupal::service('cb_apache_log_parser.cb_apache_log_parser');
    $out = $cb_apache_log_parser->doParse();
    $outs = [];
    foreach($out as $line) {
      $outs[] = sprintf('<tr><td>%s</td></tr>', implode('</td><td>', $line));
    }

    
    $html = sprintf('<table>%s</table>', implode('', $outs));
   
    $build['content'] = [
      '#markup' => $html
    ];
    return $build;
  }

}
