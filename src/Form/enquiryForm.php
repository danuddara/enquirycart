<?php

/**
 * @file
 * 
 */

namespace Drupal\enquirycart\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\block\Entity;

class EnquiryForm extends FormBase
{
  private $config; 
    
  public function __construct() {
      $this->config = \Drupal::config('enquirycart.settings');
      //dpm($this->config);
  } 
    
   /**
   * {@inheritdoc}
   */
  public function getFormId() {
      return 'enquiry_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    
      
       $form['name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Name'),
            '#required'=>true,
        );
      
      $form['email'] = array(
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#required'=>true,
        );
            
         
        $form['text'] = array(
            '#type' => 'textarea',
            '#title' => $this->t('Message'),
            '#required'=>true,
          );
        
       $form['break'] = array(
            '#type' => 'markup',
            '#markup' =>'<div class="row"><br/></div>',
          );
        
        $form['submit'] = array(
                '#prefix' => '<div class="row clearfix">',
                '#suffix' => '</div>',
                '#type' => 'submit',
                '#value' => $this->config->get('sendbuttonTitle'),
                '#attributes'=>array('class'=>array('btn-primary')),
            );
        
       return $form; 
  }
  
    /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }
  
   /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      
      $config = \Drupal::config('enquirycart.settings');
      $to=$config->get('enquirycart.email');
      if(empty($to))
      {
        $system_site_config = \Drupal::config('system.site');
        $to = $system_site_config->get('mail');
      }
        
       $request = \Drupal::request();
                  
       $session = $request->getSession();
       $value = $session->get('enquire');
       
       if(!empty($value)){
           
        $enquiryTitleList = implode(', ',$value);   
           
        $message = $form_state->getValue('text');
        $mailManager = \Drupal::service('plugin.manager.mail');
        $module = "enquirycart";
        $name = $form_state->getValue('name');
        $key = 'Send_enquiry';
        $reply = $form_state->getValue('email');
        $params['subject']  = "Enquiry for Milk Meters from {$name}";
     
        $body = "Name: {$name}\n "
        . "Email: {$reply}"
        . "List items: \n{$enquiryTitleList}\n"
        . "Message:\n {$message}\n";
      
        
        $params['message'] = $body;
        
  
        $send = true;
        $result = $mailManager->mail($module, $key, $to, 'en', $params, $reply, $send);
        if ($result['result'] !== true) {
          drupal_set_message($this->t('There was a problem sending your message and it was not sent.'), 'error');
          
          
        }
        else {
          drupal_set_message($this->t('Your message has been sent.'));
          $session->clear('enquire');
        }
        
       }
       else
       {
           drupal_set_message($this->t('Your enquiry basket is empty. cannot send any email'), 'error');
       }
      
  }
    
}
