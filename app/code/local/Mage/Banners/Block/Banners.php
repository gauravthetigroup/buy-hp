<?php
class Mage_Banners_Block_Banners extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		Mage::helper('banners')->generateXML();
		return parent::_prepareLayout();
    }
    
     public function getBanners()     
     { 
        if (!$this->hasData('banners')) {
            $this->setData('banners', Mage::registry('banners'));
        }
        return $this->getData('banners');
        
    }
	/**
	  * Get banners collection
	  */
	public function getBannersCollection()     
     { 
        $resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bannersTable = $resource->getTableName('banners');
		
		$banners = Mage::getModel('banners/banners')->getCollection()
						->addStoreFilter(Mage::app()->getStore(true)->getId())
						//->addFieldToFilter('category', 'inner')
						->addOrder('main_table.sort_order', 'ASC')
						->getData();
        
		return $banners;
    }
	
	
}