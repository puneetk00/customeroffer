<?php
/**
 * @Author : Pkgroup
 * @Package : Pkgroup_Customeroffer
 * @Developer : Puneet Kumar
 */
namespace Pkgroup\Customeroffer\Model\Total\Quote;

class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{


	 protected $_checkoutSession;
	 public function __construct(
	 \Magento\Framework\Event\ManagerInterface $eventManager,
	 \Magento\Store\Model\StoreManagerInterface $storeManager,
	 \Magento\SalesRule\Model\Validator $validator,
	 \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
	 \Magento\Checkout\Model\Session $checkoutSession
	 ) 
	 {
		$this->setCode('specialdiscount');
		$this->_checkoutSession = $checkoutSession;
		$this->eventManager = $eventManager;
		$this->calculator = $validator;
		$this->storeManager = $storeManager;
		$this->priceCurrency = $priceCurrency;
	 }
 
	public function collect(
	\Magento\Quote\Model\Quote $quote,
	\Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
	\Magento\Quote\Model\Quote\Address\Total $total
	)
	{
			if($this->_checkoutSession->getOfferSet() != 'rhkgsk%$#0003'){
				return $this;
			}
			
			parent::collect($quote, $shippingAssignment, $total);
			 $address = $shippingAssignment->getShipping()->getAddress();
			 foreach($quote->getAllItems() as $item){
				 if($item->getQty() > 1){
					 return $this;
				 }
			 }
			 $numberitem = $quote->getItemsCount();
			 $dis = 0;
			 switch($numberitem){
				 case 2:
				 $dis = 5;
				 break;
				 case 3:
				 $dis = 10;
				 break;
				 case 4:
				 $dis = 15;
				 break;
				 case 5:
				 $dis = 20;
				 break;
				 default:
				 $dis = 0;
				 
			 }
			 if($dis == 0){
				 return $this;
			 }
			 $label = "special discount $dis%";
			 $TotalAmount=$total->getSubtotal();
			 $TotalAmount=($TotalAmount*$dis)/100;
		 
			 $discountAmount ="-".$TotalAmount; 
			 $appliedCartDiscount = 0;
		 
			if($total->getDiscountDescription())
			 {
				 $appliedCartDiscount = $total->getDiscountAmount();
				 $discountAmount = $total->getDiscountAmount()+$discountAmount;
				 $label = $total->getDiscountDescription().', '.$label;
			 } 
		 
			 $total->setDiscountDescription($label);
			 $total->setDiscountAmount($discountAmount);
			 $total->setBaseDiscountAmount($discountAmount);
			 $total->setSubtotalWithDiscount($total->getSubtotal() + $discountAmount);
			 $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $discountAmount);
		 
			 if(isset($appliedCartDiscount))
			 {
				$total->addTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
				$total->addBaseTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
			 } 
			 else 
			 {
				$total->addTotalAmount($this->getCode(), $discountAmount);
				$total->addBaseTotalAmount($this->getCode(), $discountAmount);
			 }
		return $this;
	}
 
	public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
	{
		$result = null;
		$amount = $total->getDiscountAmount();
		 
		if ($amount != 0)
		{ 
				$description = $total->getDiscountDescription();
				$result = [
				'code' => $this->getCode(),
				'title' => strlen($description) ? __('Discount (%1)', $description) : __('Discount'),
				'value' => $amount
				];
		}
		return $result;
	}
}