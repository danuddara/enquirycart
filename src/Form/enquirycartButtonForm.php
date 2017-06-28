<?php

/**
 *
 * Contains \Drupal\enquirycart\Form\enquirycartButtonForm.
 */

namespace Drupal\enquirycart\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\HttpFoundation;
use Drupal\enquirycart\Controller\EnquirycartController;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * form with button
 */
class EnquirycartButtonForm extends FormBase
{
    
  private $config; 
  
    
  public function __construct() {
      $this->config = \Drupal::config('enquirycart.settings');
      
  }
  
   /**
   * {@inheritdoc}
   */
  public function getFormId() {
      return 'enquirycart_button_form';
  }
    
    
   /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
      
     $node = \Drupal::routeMatch()->getParameter('node');
   
     //check if the current page is a node and display the button.
     //we don't want to give errors for the ones that cannot be accessed.
     if(!empty($node))
     {
       $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->config->get('buttonTitle'),
      '#attributes' => array('class'=>array('buttonnew btn-primary pull-right'))  ,  
       );
     
        return $form;
     }
     
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

         $node = \Drupal::routeMatch()->getParameter('node');
         $request = \Drupal::request();

         if($node!=null)
         {
            $nodetitle = $node->getTitle();      
     
            $this->managesession($request, $nodetitle);

            $message = $this->t("'@prodtitle' has been added to the @pagetitle",array('@prodtitle'=>$nodetitle,'@pagetitle'=>$this->config->get('title')));
            drupal_set_message($message);

            $form_state->setRedirect('enquirycart.getEnquiryBasket');
     
         }
         else
         {
            $message = $this->t('Sorry this cannot be added to the basket');
            drupal_set_message($message,'error');
         }
  
  }
  
  
  
  /**
   * @param \Symfony\Component\HttpFoundation\Request $request request service for session
   * @param string $nodetitle title of the node
   */   
  private function managesession($request, $nodetitle )
  {  
      $session = $request->getSession();

      $value = $session->get('enquire');

      if($value == null && $nodetitle != null){
          
          $temp = array($nodetitle);
          $session->set('enquire', $temp);
          
      }
      else {
   
          if(!in_array($nodetitle, $value)){
              
            $value[] = $nodetitle;
            $session->set('enquire', $value);
            
          }
          
      }
            
  }
  

  
}