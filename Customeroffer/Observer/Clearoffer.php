<?php
/*
* @Author : Pkgroup
* @Package : Pkgroup_Customeroffer
* @Developer : Puneet Kumar
*/
namespace Pkgroup\Customeroffer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class Clearoffer implements ObserverInterface
{
	
	public function __construct(
		\Magento\Checkout\Model\Session $checkoutSession
		)
	{
		$this->_checkoutSession = $checkoutSession;
	}
	
	public function execute(\Magento\Framework\Event\Observer $observer) 
	{
		$this->_checkoutSession->setOfferSet('');
	}

}