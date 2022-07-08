<?php

namespace Drupal\cb_maps\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "cb_maps_france_block",
 *   admin_label = @Translation("France Map"),
 *   category = @Translation("cb_maps")
 * )
 */
class CbMapsFranceBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

	  $markup = '

<div class="container">
    <h1>Map with zoom-in, zoom-out buttons and zoom on mousewheel</h1>

    <div class="mapcontainer">
        <div class="map">
            <span>Alternative content for the map</span>
        </div>
    </div>

    <p><b>All example for jQuery Mapael are available <a href="https://www.vincentbroute.fr/mapael/">here</a>.</b></p>
</div>
';


    $build['content'] = [
	    '#markup' => $this->t($markup),

 '#attached' => [
	 'library' => [
		 'cb_mapael/cb_mapael',
          'cb_maps/cb_maps'
        ],
      ],










    ];
    return $build;
  }

}
