<?php

namespace Drupal\cb_personal_inventory\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "cb_personal_inventory_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("cb_personal_inventory")
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
