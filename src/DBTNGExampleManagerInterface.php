<?php

/**
 * @file
 * Contains \Drupal\dbtng_example\DBTNGExampleManagerInterface.
 */

namespace Drupal\dbtng_example;

/**
 * Provides an interface defining a DBTNG Example manager.
 */
interface DBTNGExampleManagerInterface {

  /**
   * Sets default data.
   *
   * @param $fields
   *   An array of entry fields.
   */
  public function setDefaultValues($fields);

  /**
   * Returns an array of entries.
   *
   * @return object
   *   An object of entries.
   */
  public function getEntriesList();

  /**
   * Returns an array of entries for advanced list.
   *
   * @return object
   *   An object of entries.
   */
  public function getEntriesAdvancedList();

}
