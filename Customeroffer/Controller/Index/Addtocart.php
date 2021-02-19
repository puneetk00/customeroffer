<?php
namespace Pkgroup\Customeroffer\Controller\Index;

class Addtocart extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $_quote;
	protected $_checkout;
	protected $_product;
	protected $_cart;
	protected $_url;


	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Quote\Api\CartRepositoryInterface $quote,
		\Magento\Checkout\Model\Session $checkout,
		\Magento\Catalog\Model\productFactory $product,
		\Magento\Checkout\Model\Cart $cart,
		\Magento\Framework\Data\Form\FormKey $formKey,
		\Magento\Checkout\Model\Session $checkoutSession,
		\Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
		\Magento\Quote\Model\QuoteFactory $quoteFactory
		)
	{
		$this->quoteFactory = $quoteFactory;
		$this->formKey = $formKey;
		$this->_cart = $cart;
		$this->_quote = $quote;
		$this->_checkout = $checkout;
		$this->_product = $product;
		$this->_pageFactory = $pageFactory;
		$this->_url = $context->getUrl();
		$this->_checkoutSession = $checkoutSession;
		$this->customerRepository = $customerRepository;
		return parent::__construct($context);
	}

	public function execute()
	{
		
		 $cart = $this->_cart;
		 $quoteItems = $this->_checkoutSession->getQuote()->getItemsCollection();
			 foreach($quoteItems as $item)
			 {
				$cart->removeItem($item->getId())->save(); 
			 }
		 
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		//$this->_cart->truncate()->save();
		$quote = $this->_checkoutSession->getQuote();
		
		$customerSession = $objectManager->get('\Magento\Customer\Model\Session');
		$urlInterface = $objectManager->get('\Magento\Framework\UrlInterface');
		
		if(!$customerSession->isLoggedIn()) {
			$resultRedirect = $this->resultRedirectFactory->create();
			$customerSession->setAfterAuthUrl($this->_url->getUrl('offer'));
			
			$customerSession->authenticate();
            $resultRedirect->setPath('customer/account/login/');
            return $resultRedirect;   
		}
		
		$data = $this->getRequest()->getParam("productsids"); 
		if($data == ''){
			$this->getResponse()->setRedirect('*/*/*');
		}
		
		//$this->_cart->createEmptyCart();
		$products = explode(",",$data);
		foreach($products as $productId){
			$product = $this->_product->create()->load($productId);
			$params = array();      
			$params['form_key'] = $this->formKey->getFormKey();
			$params['qty'] = 1;		
			$this->_cart->addProduct($product, $params);
		}
		
		$this->_cart->save();
		$this->_cart->getItems()->clear()->save();
		$quote->save();
        $quote->collectTotals(); 
        $this->_cart->getQuote()->setTotalsCollectedFlag(false)->collectTotals()->save();
		
		$this->_checkoutSession->setOfferSet('rhkgsk%$#0003');
		$resultRedirect = $this->resultRedirectFactory->create();
		$resultRedirect->setPath('checkout');
		return $resultRedirect;   
	}
}