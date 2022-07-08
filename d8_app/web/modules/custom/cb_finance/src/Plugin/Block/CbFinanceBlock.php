<?php

namespace Drupal\cb_finance\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "cb_finance_example",
 *   admin_label = @Translation("Cb Finance"),
 *   category = @Translation("cb_finance")
 * )
 */
class CbFinanceBlock extends BlockBase {

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
