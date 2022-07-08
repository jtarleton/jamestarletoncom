<?php

namespace Drupal\onesip_role_promote\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\views_bulk_operations\ViewsBulkOperationsEvent;

/**
 * Event subscriber class.
 */
class OnesipRolePromoteEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    // The next line prevents hard dependency on VBO module.
    if (class_exists(ViewsBulkOperationsEvent::class)) {
      $events['views_bulk_operations.view_data'][] = ['provideViewData', 0];
    }
    return $events;
  }

  /**
   * Provide entity type data and entity getter to VBO.
   *
   * @param \Drupal\views_bulk_operations\ViewsBulkOperationsEvent $event
   *   The event object.
   */
  public function provideViewData(ViewsBulkOperationsEvent $event) {
    if ($event->getProvider() === 'onesip_role_promote') {
      $event->setEntityTypeIds(['node']);
      $event->setEntityGetter([
        'file' => drupal_get_path('module', 'onesip_role_promote') . '/src/someClass.php',
        'callable' => '\Drupal\onesip_role_promote\someClass::getEntityFromRow',
      ]);
    }
  }
}
