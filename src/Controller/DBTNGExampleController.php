<?php

/**
 * @file
 * Contains \Drupal\dbtng_example\Controller\DBTNGExampleController.
 */

namespace Drupal\dbtng_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dbtng_example\DBTNGExampleManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for DBTNG Example.
 */
class DBTNGExampleController extends ControllerBase {

  /**
   * The DBTNG Example manager.
   *
   * @var \Drupal\dbtng_example\DBTNGExampleManagerInterface
   */
  protected $dbtngManager;

  /**
   * Constructs a DBTNGExampleController object.
   *
   * @param \Drupal\dbtng_example\DBTNGExampleManagerInterface $dbtngManager
   *   The DBTNG Example manager.
   */
  public function __construct(DBTNGExampleManagerInterface $dbtngManager) {
    $this->dbtngManager = $dbtngManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dbtng.manager')
    );
  }

  /**
   * Render a list of entries in the database.
   */
  public function entryList() {
    $content = array();

    $content['message'] = array(
      '#markup' => $this->t('Generate a list of all entries in the database. There is no filter in the query.'),
    );

    $rows = array();
    $headers = array($this->t('Id'), $this->t('uid'), $this->t('Name'), $this->t('Surname'), $this->t('Age'));

    foreach ($this->dbtngManager->getEntriesList() as $entry) {
      // Sanitize each entry.
      $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', (array) $entry);
    }
    $content['table'] = array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No entries available.'),
    );
    // Don't cache this page.
    $content['#cache']['max-age'] = 0;

    return $content;
  }

  /**
   * Render a filtered list of entries in the database.
   */
  public function entryAdvancedList() {
    $content = array();

    $content['message'] = array(
      '#markup' => $this->t('A more complex list of entries in the database.') . ' ' .
      $this->t('Only the entries with name = "John" and age older than 18 years are shown, the username of the person who created the entry is also shown.'),
    );

    $headers = array($this->t('Id'), $this->t('Created by'), $this->t('Name'), $this->t('Surname'), $this->t('Age'));
    $rows = array();

    foreach ($this->dbtngManager->getEntriesAdvancedList() as $entry) {
      // Sanitize each entry.
      $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', (array) $entry);
    }
    $content['table'] = array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#attributes' => array('id' => 'dbtng-example-advanced-list'),
      '#empty' => $this->t('No entries available.'),
    );
    // Don't cache this page.
    $content['#cache']['max-age'] = 0;
    return $content;
  }

}
