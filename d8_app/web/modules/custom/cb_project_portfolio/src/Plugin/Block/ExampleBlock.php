<?php

namespace Drupal\cb_project_portfolio\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "cb_project_portfolio_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("cb_project_portfolio")
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
