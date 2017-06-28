<?php

/**
 * 
 * Contains \Drupal\enquirycart\Controller\EnquirycartController.
 * have empty line in between everything,
 * have a space infornt of the comments.
 */

namespace Drupal\enquirycart\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\enquirycart\Form;


class EnquirycartController extends ControllerBase{
  
  private $config;  
    

  public function __construct() {
      $this->config = \Drupal::config('enquirycart.settings');
     
  }

  public function getTitle()
  {
      
      $title = $this->config->get('title');
      
      return $title;
  }

  public  function getEnquiryBasket()
  {
      $request = \Drupal::request();
      
      $session = $request->getSession();
      
      $arraychgeck = null;
      $value = $session->get('enquire');
     
      if(!empty($value)){
          
      $values['addproducts'] = array(
          '#type'=>'markup',
          '#prefix'=>'<div class="enquiremessge-full">',
          '#suffix'=>'</div>',
          '#markup'=>  $this->config->get('instructions.basketfull'),
      );     
          

      $arraychgeck = array_chunk($value,1);

      $arraykeys = array_keys($value);
      foreach($arraychgeck as $key=>$value)
      {
           $options['attributes'] =  array('rel'=>'nofollow');
          $value['operation']= Link::fromTextAndUrl( $this->t('Delete'), Url::fromRoute('enquirycart.deleteEnquiryBasket',['eid' =>$arraykeys[$key]],$options) );
         $arraychgeck[$key]=$value;
      }
      
        $values['basket'] = array(
          '#type' => 'table',
          '#header' => array($this->t('Product Names')),
          '#default'=>'No products have been added to the basket',
          '#rows' => (!empty($arraychgeck))? $arraychgeck: array('No products have been added to the basket') ,
         );
         
        
        $builtForm = \Drupal::formBuilder()->getForm('Drupal\enquirycart\Form\EnquiryForm');
        $values['form']  = $builtForm;
        
        
      
      }
      else{
          
          
           $values['noproductsinbasket'] = array(
            '#type'=>'markup',
            '#prefix'=>'<div class="enquiremessge-empty">',
            '#suffix'=>'</div>',
            '#markup'=>$this->config->get('instructions.basketempty'),
            '#weight'=>-1,        
              );
      
      }

      
   
     
   
      
      return $values;
  }
  
  
  public function  deleteFromEnquiryBasket($eid)
  {
     $request = \Drupal::request();
     $session = $request->getSession();
      
      $arraychgeck = null;
      $value = $session->get('enquire');


      if(isset($value[$eid]))
      {
     
       $message = $this->t("'@prod' has been removed from the enquiry basket.",array("@prod"=>$value[$eid]));
       unset($value[$eid]);
       $session->set('enquire', $value);
       drupal_set_message($message);
       
      }
 
      
       return $this->redirect('enquirycart.getEnquiryBasket');
  }
  
}