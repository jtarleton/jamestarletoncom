<?php

namespace Drupal\Tests\nyc_migrate\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;
use Drupal\nyc_migrate\Utils\NycMigrateHelperFns;

/**
 * Simple test to ensure that main page loads with module enabled.
 *
 * @group nyc_better_field_desc
 */
class LoadTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['nyc_migrate'];

  /**
   * A user with permission to administer site configuration.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->user = $this->drupalCreateUser(['administer site configuration']);
    $this->drupalLogin($this->user);
  }

  /**
   * Tests that the home page loads with a 200 response.
   */
  public function testLoad() {
    $this->drupalGet(Url::fromRoute('<front>'));
    $this->assertSession()->statusCodeEquals(200);
  }

  public function testHasListPage() {
    $project_id = 37;
    $has_list_page = NycMigrateHelperFns::hasListpage($project_id);
    die(var_dump($has_list_page));
  }

}
