<?php

/**
 * @file
 * Contains \Drupal\dbtng_example\DBTNGExampleStorage
 */

namespace Drupal\dbtng_example;

use Drupal\Core\Database\Connection;

class DBTNGExampleStorage implements DBTNGExampleStorageInterface {

  /**
   * Database service object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a DBTNGExampleStorage object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function insert($entry) {
    $return_value = NULL;
    try {
      $return_value = $this->connection->insert('dbtng_example')
        ->fields($entry)
        ->execute();
    }
    catch (\Exception $e) {
      drupal_set_message(t('db_insert failed. Message = %message, query= %query', array(
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      )), 'error');
    }
    return $return_value;
  }

  /**
   * {@inheritdoc}
   */
  public function update($entry) {
    $count = NULL;
    try {
      $count = $this->connection->update('dbtng_example')
        ->fields($entry)
        ->condition('pid', $entry['pid'])
        ->execute();
    }
    catch (\Exception $e) {
      drupal_set_message(t('db_update failed. Message = %message, query= %query', array(
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      )), 'error');
    }
    return $count;
  }

  /**
   * {@inheritdoc}
   */
  public function delete($entry) {
    $this->connection->delete('dbtng_example')
      ->condition('pid', $entry['pid'])
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function load($entry = array()) {
    // Read all fields from the dbtng_example table.
    $query = $this->connection->select('dbtng_example', 'e');
    $query->fields('e');

    // Add each field and value as a condition to this query.
    foreach ($entry as $field => $value) {
      $query->condition($field, $value);
    }

    // Return the result in object format.
    return $query->execute()->fetchAll();
  }

  /**
   * {@inheritdoc}
   */
  public function advancedLoad() {
    $select = $this->connection->select('dbtng_example', 'e');
    // Join the users table, so we can get the entry creator's username.
    $select->join('users_field_data', 'u', 'e.uid = u.uid');
    // Select these specific fields for the output.
    $select->addField('e', 'pid');
    $select->addField('u', 'name', 'username');
    $select->addField('e', 'name');
    $select->addField('e', 'surname');
    $select->addField('e', 'age');
    // Filter only persons named "John".
    $select->condition('e.name', 'John');
    // Filter only persons older than 18 years.
    $select->condition('e.age', 18, '>');
    // Make sure we only get items 0-49, for scalability reasons.
    $select->range(0, 50);

    $entries = $select->execute()->fetchAll();

    return $entries;
  }

}
