<?php

namespace Drupal\cb_world_times\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an CbWorldTimesDisplayBlock block.
 *
 * @Block(
 *   id = "cb_world_times_cb_world_times_display_block",
 *   admin_label = @Translation("CbWorldTimesDisplayBlock"),
 *   category = @Translation("CB World Times")
 * )
 */
class CbWorldTimesDisplayBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $times = [
      'Los_Angeles'=>'America/Los_Angeles',
      'Denver'=>'America/Denver',
      'Chicago'=>'America/Chicago',
      'New_York'=>'America/New_York',
      'Tokyo'=>'Asia/Tokyo',
      'Shanghai'=>'Asia/Shanghai',
      'Jakarta'=>'Asia/Jakarta',
      'Manila'=>'Asia/Manila',
      'Bangkok'=>'Asia/Bangkok',
      'Hong_Kong'=>'Asia/Hong_Kong',
      'Singapore'=>'Asia/Singapore',
      'Taipei'=>'Asia/Taipei',
      'Seoul'=>'Asia/Seoul',
      'Dubai'=>'Asia/Dubai',
      'Minsk'=>'Europe/Minsk',
      'Paris'=>'Europe/Paris',
      'Frankfurt'=>'Europe/Berlin',
      'Berlin'=>'Europe/Berlin',
      'London'=>'Europe/London',
      'Moscow'=>'Europe/Moscow',
      'Sydney'=>'Australia/Sydney',
      'Nairobi'=>'Africa/Nairobi',
      'Johannesburg'=>'Africa/Johannesburg'
    ];
    $markup = '';
    foreach($times as $city=>$time) {
      $datetime = new DateTime();
      $timezone = new DateTimeZone($timezone);
      $datetime->setTimezone($timezone);
      $markup .= sprintf('<tr><td>%s</td><td>%s</td></tr>',$city, $datetime->format('F d, Y H:i'));
    }

    $build['content'] = [
      '#markup' => $this->t( $markup),
    ];
    return $build;
  }

}
