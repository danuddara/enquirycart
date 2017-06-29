<?php

namespace Drupal\enquirycart\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'enquirycart button' Block.
 *
 * @Block(
 *   id = "enquirycart_block",
 *   admin_label = @Translation("Enquiry button"),
 *   category = @Translation("Enquiry"),
 *
 * )
 */
class EnquirycartBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_config = \Drupal::config('enquirycart.settings');

    return [
      'label_display' => FALSE,
      'enquiry_email' => $default_config->get('enquirycart.email'),
    ];

  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $builtForm = \Drupal::formBuilder()->getForm('Drupal\enquirycart\Form\EnquirycartButtonForm');
    $renderArray['form'] = $builtForm;

    return $renderArray;

  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form_new = parent::blockForm($form, $form_state);

    return $form_new;

  }

}
