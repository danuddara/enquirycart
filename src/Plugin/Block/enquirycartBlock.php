<?php

namespace Drupal\enquirycart\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\enquirycart\Form;

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
class EnquirycartBlock extends BlockBase implements BlockPluginInterface{

  
  /**
   * 
   * The default config for this module.
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_config = \Drupal::config('enquirycart.settings');
    
    
    return array(
      'label_display' => false,  
      'enquiry_email' => $default_config->get('enquirycart.email'),
    );
    
  }   
    
    /**
   * {@inheritdoc}
   */
  public function build() {
      
    $config = $this->getConfiguration();
  
    $builtForm = \Drupal::formBuilder()->getForm('Drupal\enquirycart\Form\EnquirycartButtonForm');
    $renderArray['form']  = $builtForm;

    return $renderArray;
    
  }
  
  /**
   * {@inheritdoc}
   * 
   */
  public function blockForm($form, FormStateInterface $form_state) {
   $form_new = parent::blockForm($form, $form_state);

    return $form_new;
    
  }
  
  
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
 
  }

  
}
?>

