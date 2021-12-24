<?php

namespace Drupal\cb_stock_quotes\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "cb_stock_quotes_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("cb_stock_quotes")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

}
