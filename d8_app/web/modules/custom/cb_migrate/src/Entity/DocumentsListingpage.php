<?php

namespace Drupal\nyc_migrate\Entity;

use Drupal\node\Entity\Node;
use Drupal\pathauto\PathautoState;
use Drupal\nyc_migrate\Entity\Listingpage;

class DocumentsListingpage extends Node {

  use Listingpage;

  public static $list_page_type;

  public static function init() {
    self::$list_page_type = 'document';
  }

}
