<?php

namespace Drupal\cb_home\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "cb_home_addthis",
 *   admin_label = @Translation("Add This Block"),
 *   category = @Translation("cb_home")
 * )
 */
class CbHomeAddThisBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
	public function build() {
		$markup = '<a class="addthis_button_facebook"
        addthis:url="http://google.com/"
        addthis:title="Here\'s a title"   
        addthis:media="https://wunder.io/wp-content/uploads/2020/06/d9-launch-2-e1602162957119-2048x1669.png" 
        addthis:description="Heres a cool description">
        <i class="ico ico-facebook"></i>SHARE
    </a>';
		
$mk = '<div class="addthis_inline_share_toolbox" data-title="THE TITLE" data-description="THE DESCRIPTION" data-media="https://wunder.io/wp-content/uploads/2020/06/d9-launch-2-e1602162957119-2048x1669.png"></div>';
    $build['content'] = [
      '#markup' => $this->t($markup),
      '#attached' => [
        'library' => [
          'cb_home/cb_home',
        ],
      ],

    ];
    return $build;
  }

}
