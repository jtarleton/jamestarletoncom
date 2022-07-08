<?php

namespace Drupal\nyc_migrate\Entity;

use Drupal\node\Entity\Node;
use Drupal\pathauto\PathautoState;
use Drupal\nyc_migrate\Entity\Listingpage;

class VideosListingpage extends Node {

  use Listingpage;

  public static $list_page_type;

  public static function init() {
    self::$list_page_type = 'video';
  }

  public function __construct($values = [], $entity_type = 'node') {
    /*
      video|Videos
     */
    $this->list_page_type = 'video';
  }

}
