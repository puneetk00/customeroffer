<?php
/**
 * @Author : Pkgroup
 * @Package : Pkgroup_Customeroffer
 * @Developer : Puneet Kumar
 */
namespace Pkgroup\Customeroffer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class Clearoffer implements ObserverInterface
{
	
	protected $_registry;
	
	public function __construct(
		\Magento\Checkout\Model\Session $checkoutSession,
		\Magento\Framework\Registry $registry
		)
	{
		$this->_checkoutSession = $checkoutSession;
		$this->_registry = $registry;
	}
	
	public function execute(\Magento\Framework\Event\Observer $observer) 
	{
		if(!$this->_registry->registry('custom_price_active')) return;
		$discount = $this->_registry->registry('custom_price_active_discount');
		// print_r("puneet");
		// print_r($discount);
		// die($discount);
		$item = $observer->getEvent()->getData('quote_item');
        // Get parent product if current product is child product
        $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
        //Define your Custom price here
        $price_dis = (($item->getProduct()->getPrice()*$discount)/100);
        
		$price = ($item->getProduct()->getPrice() - $price_dis);
        
		//Set custom price
        //$item->setDiscountAmount(20);
        $item->setCustomPrice($price);
        $item->setOriginalCustomPrice($price);
        $item->getProduct()->setIsSuperMode(false);
		//$this->_checkoutSession->setOfferSet('');
	}

}