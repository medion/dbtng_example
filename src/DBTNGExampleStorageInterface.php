<?php

/**
 * @file
 * Contains \Drupal\dbtng_example\DBTNGExampleStorageInterface.
 */

namespace Drupal\dbtng_example;

/**
 * Defines a common interface for DBTNG Example storage classes.
 */
interface DBTNGExampleStorageInterface {

  /**
   * Save an entry in the database.
   *
   * Exception handling is shown in this example. It could be simplified
   * without the try/catch blocks, but since an insert will throw an exception
   * and terminate your application if the exception is not handled, it is best
   * to employ try/catch.
   *
   * @param array $entry
   *   An array containing all the fields of the database record.
   *
   * @return int
   *   The number of updated rows.
   *
   * @throws \Exception
   *   When the database insert fails.
   *
   * @see \Drupal\Core\Database\Connection::insert()
   */
  public function insert($entry);

  /**
   * Update an entry in the database.
   *
   * @param array $entry
   *   An array containing all the fields of the item to be updated.
   *
   * @return int
   *   The number of updated rows.
   *
   * @see \Drupal\Core\Database\Connection::update()
   */
  public function update($entry);

  /**
   * Delete an entry from the database.
   *
   * @param array $entry
   *   An array containing at least the person identifier 'pid' element of the
   *   entry to delete.
   *
   * @see \Drupal\Core\Database\Connection::delete()
   */
  public function delete($entry);

  /**
   * Read from the database using a filter array.
   *
   * The standard function to perform reads was db_query(), and for static
   * queries, it still is.
   *
   * db_query() used an SQL query with placeholders and arguments as parameters.
   *
   * Drupal DBTNG provides an abstracted interface that will work with a wide
   * variety of database engines.
   *
   * db_query() is deprecated except when doing a static query. The following is
   * perfectly acceptable in Drupal 8. See
   * @link http://drupal.org/node/310072 the handbook page on static queries @endlink
   *
   * @code
   *   // SELECT * FROM {dbtng_example} WHERE uid = 0 AND name = 'John'
   *   db_query(
   *     "SELECT * FROM {dbtng_example} WHERE uid = :uid and name = :name",
   *     array(':uid' => 0, ':name' => 'John')
   *   )->execute();
   * @endcode
   *
   * But for more dynamic queries, Drupal provides the db_select()
   * API method, so there are several ways to perform the same SQL query. See
   * the
   * @link http://drupal.org/node/310075 handbook page on dynamic queries. @endlink
   *
   * @code
   *   // SELECT * FROM {dbtng_example} WHERE uid = 0 AND name = 'John'
   *   db_select('dbtng_example')
   *     ->fields('dbtng_example')
   *     ->condition('uid', 0)
   *     ->condition('name', 'John')
   *     ->execute();
   * @endcode
   *
   * Here is db_select with named placeholders:
   * @code
   *   // SELECT * FROM {dbtng_example} WHERE uid = 0 AND name = 'John'
   *   $arguments = array(':name' => 'John', ':uid' => 0);
   *   db_select('dbtng_example')
   *     ->fields('dbtng_example')
   *     ->where('uid = :uid AND name = :name', $arguments)
   *     ->execute();
   * @endcode
   *
   * Conditions are stacked and evaluated as AND and OR depending on the type of
   * query. For more information, read the conditional queries handbook page at:
   * http://drupal.org/node/310086
   *
   * The condition argument is an 'equal' evaluation by default, but this can be
   * altered:
   * @code
   *   // SELECT * FROM {dbtng_example} WHERE age > 18
   *   db_select('dbtng_example')
   *     ->fields('dbtng_example')
   *     ->condition('age', 18, '>')
   *     ->execute();
   * @endcode
   *
   * @param array $entry
   *   An array containing all the fields used to search the entries in the
   *   table.
   *
   * @return object
   *   An object containing the loaded entries if found.
   *
   * @see \Drupal\Core\Database\Connection::select()
   * @see http://drupal.org/node/310072
   * @see http://drupal.org/node/310075
   */
  public function load($entry = array());

  /**
   * Load dbtng_example records joined with user records.
   *
   * DBTNG also helps processing queries that return several rows, providing the
   * found objects in the same query execution call.
   *
   * This function queries the database using a JOIN between users table and the
   * example entries, to provide the username that created the entry, and
   * creates a table with the results, processing each row.
   *
   * SELECT
   *  e.pid as pid, e.name as name, e.surname as surname, e.age as age
   *  u.name as username
   * FROM
   *  {dbtng_example} e
   * JOIN
   *  users u ON e.uid = u.uid
   * WHERE
   *  e.name = 'John' AND e.age > 18
   *
   * @see \Drupal\Core\Database\Connection::select()
   * @see http://drupal.org/node/310075
   */
  public function advancedLoad();

}
