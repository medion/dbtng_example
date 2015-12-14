<?php

/**
 * @file
 * Contains \Drupal\dbtng_example\DBTNGExampleManager.
 */

namespace Drupal\dbtng_example;

/**
 * Defines a DBTNG Example manager.
 */
class DBTNGExampleManager implements DBTNGExampleManagerInterface {

  /**
   * An entries array.
   *
   * @var array
   */
  protected $entries;

  /**
   * DBTNG Example storage.
   *
   * @var \Drupal\dbtng_example\DBTNGExampleStorageInterface
   */
  protected $dbtngStorage;

  /**
   * Constructs a DBTNGExampleManager object.
   */
  public function __construct(DBTNGExampleStorageInterface $dbtngStorage) {
    $this->dbtngStorage = $dbtngStorage;
  }

  /**
   * Set default values during installation.
   *
   * @param $fields
   */
  public function setDefaultValues($fields) {
    $this->dbtngStorage->insert($fields);
  }

  /**
   * Get list of entries.
   */
  public function getEntriesList() {
    if (!isset($this->entries)) {
      $this->entries = $this->dbtngStorage->load();
    }
    return $this->entries;
  }

  /**
   * Get list of entries.
   */
  public function getEntriesAdvancedList() {
    if (!isset($this->entries)) {
      $this->entries = $this->dbtngStorage->advancedLoad();
    }
    return $this->entries;
  }


}
