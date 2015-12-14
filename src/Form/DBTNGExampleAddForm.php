<?php

/**
 * @file
 * Contains \Drupal\dbtng_example\Form\DBTNExampleAddForm
 */

namespace Drupal\dbtng_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Simple form to add an entry, with all the interesting fields.
 */
class DBTNGExampleAddForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'dbtng_add_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = array();

    $form['message'] = array(
      '#markup' => $this->t('Add an entry to the dbtng_example table.'),
    );

    $form['add'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Add a person entry'),
    );
    $form['add']['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#size' => 15,
    );
    $form['add']['surname'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#size' => 15,
    );
    $form['add']['age'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Age'),
      '#size' => 5,
      '#description' => $this->t("Values greater than 127 will cause an exception. Try it - it's a great example why exception handling is needed with DTBNG."),
    );
    $form['add']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Add'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Confirm that age is numeric.
    if (!intval($form_state->getValue('age'))) {
      $form_state->setErrorByName('age', $this->t('Age needs to be a number'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Gather the current user so the new record has ownership.
    $account = \Drupal::currentUser();
    // Save the submitted entry.
    $entry = array(
      'name' => $form_state->getValue('name'),
      'surname' => $form_state->getValue('surname'),
      'age' => $form_state->getValue('age'),
      'uid' => $account->id(),
    );
    $dbtng_storage = \Drupal::service('dbtng.storage')->insert($entry);
    if ($dbtng_storage) {
      drupal_set_message($this->t('Created entry @entry', array('@entry' => print_r($entry, TRUE))));
    }
  }

}
