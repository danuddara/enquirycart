<?php

/**
 * The configuration file for enquiry form
 */

namespace Drupal\enquirycart\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class EnquiryConfigForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'enquiry_config_form';
    }
    
     /**
     * {@inheritdoc}
     * Set the configurations that is editable 
     */
    public function getEditableConfigNames() {
        return array(
            'enquirycart.settings',
        );
    }
    
    public function buildForm(array $form, FormStateInterface $form_state) {
        
         $config = $this->config('enquirycart.settings');
       
         $site_email = $config->get('enquirycart.email');
          if(empty($site_email))
            {
              $system_site_config = \Drupal::config('system.site');
              $site_email = $system_site_config->get('mail');
            }
                  
         $form['title'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Title of the page'),
            '#default_value' => $config->get('title'),
            '#description' => $this->t('Type in the page title that you want to display in the enquiry basket'),
          );  

          $form['email'] = array(
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#default_value' => $site_email,
            '#description' => $this->t('Type in the email address that you need to send the enquiry to. By default it uses the site email configured in the website.'),
          ); 
          
          $form['addtoenquirybtntitle'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Title of button "add to enquiry"'),
            '#default_value' => $config->get('buttonTitle'),
            '#description' => $this->t('Type in a title that you want to display in the button'),
          );  
          
          $form['sendbuttonTitle'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Title of button "Send Enquiry"'),
            '#default_value' => $config->get('sendbuttonTitle'),
            '#description' => $this->t('Type in a title that you want to display in the button to send the enquiry'),
          );  
          
          $form['basketfullmsg'] = array(
                '#type' => 'text_format',
                '#title' => $this->t('Basket full message'),
                '#default_value' => $config->get('instructions.basketfull'),
                '#format' => 'full_html', 
                          );
          
           $form['basketemptymsg'] = array(
                '#type' => 'text_format',
                '#title' => $this->t('Basket empty message'),
                '#default_value' => $config->get('instructions.basketempty'),
                '#format' => 'full_html',
                        );
        
        
        
        return parent::buildForm($form, $form_state);
    }
    
    /** 
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        
        //dpm($form_state->getValue('Basketfullmsg'));
        $basketfullvalue = $form_state->getValue('basketfullmsg'); 
        $basketemptyvalue = $form_state->getValue('basketemptymsg'); 
      // Retrieve the configuration
      $this->config('enquirycart.settings')
        // Set the submitted configuration setting
        ->set('title', $form_state->getValue('title'))
        // You can set multiple configurations at once by making
        // multiple calls to set()
        ->set('enquirycart.email', $form_state->getValue('email'))
        ->set('instructions.basketfull', $basketfullvalue['value'])
        ->set('instructions.basketempty', $basketemptyvalue['value']) 
        ->set('buttonTitle', $form_state->getValue('addtoenquirybtntitle'))    
        ->set('sendbuttonTitle', $form_state->getValue('sendbuttonTitle'))       
        ->save();

      parent::submitForm($form, $form_state);
    }

    
}
