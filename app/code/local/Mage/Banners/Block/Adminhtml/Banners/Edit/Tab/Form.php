<?php

class Mage_Banners_Block_Adminhtml_Banners_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('banners_form', array('legend'=>Mage::helper('banners')->__('Item information')));
     
	  $object = Mage::getModel('banners/banners')->load( $this->getRequest()->getParam('id') );
	  $imgPath = Mage::getBaseUrl('media')."Banners/images/thumb/".$object['bannerimage'];
	 
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('banners')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('bannerimage', 'file', array(
          'label'     => Mage::helper('banners')->__('Banner Image'),
          'required'  => false,
          'name'      => 'bannerimage',
	  ));
	  
	  if( $object->getId() ){
		  $tempArray = array(
				  'name'      => 'filethumbnail',
				  'style'     => 'display:none;',
			  );
		  $fieldset->addField($imgPath, 'file',$tempArray);
	  }
	  
	  $fieldset->addField('link', 'select', array(
          'label'     => Mage::helper('banners')->__('Link'),
          'required'  => false,
          'name'      => 'link',
		  'values'    => $this->getCategoryList(),
	  ));
	 
	  $fieldset->addField('sort_order', 'text', array(
          'label'     => Mage::helper('banners')->__('Sort Order'),
          'required'  => false,
          'name'      => 'sort_order',
      ));

	 
	 $fieldset->addField('textblend', 'select', array(
          'label'     => Mage::helper('banners')->__('Text Blend ?'),
          'name'      => 'textblend',
          'values'    => array(
              array(
                  'value'     => 'yes',
                  'label'     => Mage::helper('banners')->__('Yes'),
              ),

              array(
                  'value'     => 'no',
                  'label'     => Mage::helper('banners')->__('No'),
              ),
          ),
      ));
	
	 $fieldset->addField('store_id','multiselect',array(
			'name'      => 'stores[]',
            'label'     => Mage::helper('banners')->__('Store View'),
            'title'     => Mage::helper('banners')->__('Store View'),
            'required'  => true,
			'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
		));
	
	
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('banners')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('banners')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('banners')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('banners')->__('Content'),
          'title'     => Mage::helper('banners')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getBannersData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getBannersData());
          Mage::getSingleton('adminhtml/session')->setBannersData(null);
      } elseif ( Mage::registry('banners_data') ) {
          $form->setValues(Mage::registry('banners_data')->getData());
      }
      return parent::_prepareForm();
  }
  
  
  /**
	  * Get manufacturers list
	  */
	  
	protected function _loadData()
    {
		$category = Mage::getModel('catalog/category');
		$tree = $category->getTreeModel();
		$tree->load();
		$ids = $tree->getCollection()->getAllIds();
		$arr = array();
		if ($ids){
			foreach ($ids as $id){
				
					$cat = Mage::getModel('catalog/category');
					$cat->load($id);
					if($cat->getLevel() > 2):
					 array_push($arr, $cat);
					endif;
				
			}
		}
		$catArr = array();
		$checkCat = array();
		foreach($arr as $subcat):
		  if(!in_array($this->__(html_entity_decode($subcat->getName())),$checkCat)):
				array_push($checkCat, $this->__(html_entity_decode($subcat->getName())));
				array_push($catArr, $subcat);
		  endif;
		endforeach;
		foreach($catArr  as $subcat):
			
			$link[] = array('value' => $subcat->getUrl(),'label' => $subcat->getName());
		endforeach;      
		$this->_data = $link;
		//$this->count_data = count($this->_data);
        return $this;
    }
	
	public function setLimit(){
		$limit = Mage::getStoreConfig('catalog/manufacturerlist/limit');
		if($limit>0){
			$this->_data = array_slice($this->_data, 0, $limit);
		}
	}
	
	public function getCategoryList(){
		$this->_loadData();
		$this->setLimit();
		return $this->_data;
	}

  
}