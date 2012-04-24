<?php
class Mage_Banners_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {	
		$this->loadLayout();  
		$this->renderLayout();
    }
	public function landingAction(){
			echo "hgfhgf";die;
		$this->loadLayout();  
		$this->renderLayout();
	}
}