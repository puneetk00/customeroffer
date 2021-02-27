<?php
/**
 * @Author : Pkgroup
 * @Package : Pkgroup_Customeroffer
 * @Developer : Puneet Kumar
 */
namespace Pkgroup\Customeroffer\Controller\Index;

class Addtocart extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $_quote;
	protected $_checkout;
	protected $_product;
	protected $_cart;
	protected $_cartadd;
	protected $_url;
	protected $_messageManager;
	protected $_customersession;


	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Quote\Api\CartRepositoryInterface $quote,
		\Magento\Catalog\Model\productFactory $product,
		\Magento\Checkout\Model\Cart $cart,
		\Magento\Framework\Data\Form\FormKey $formKey,
		\Magento\Checkout\Model\Session $checkoutSession,
		\Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
		\Magento\Quote\Model\QuoteFactory $quoteFactory,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Magento\Customer\Model\Session $customersession
		)
	{
		$this->_customersession = $customersession;
		$this->quoteFactory = $quoteFactory;
		$this->formKey = $formKey;
		$this->_cartadd = $cart;
		$this->_cart = $cart;
		$this->_quote = $quote;
		$this->_product = $product;
		$this->_pageFactory = $pageFactory;
		$this->_url = $context->getUrl();
		$this->_checkoutSession = $checkoutSession;
		$this->customerRepository = $customerRepository;
		$this->_messageManager = $messageManager;
		return parent::__construct($context);
	}

	public function execute()
	{
		
		$this->_cart->getQuote()->setTotalsCollectedFlag(false)->collectTotals()->save();
		$resultRedirect = $this->resultRedirectFactory->create();
		$cart = $this->_cart;
		$quoteItems = $this->_checkoutSession->getQuote()->getItemsCollection();
		foreach($quoteItems as $item)
		{
			$cart->removeItem($item->getId())->save(); 
		}
		
		$customerSession = $this->_customersession;
		
		if(!$customerSession->isLoggedIn()) {
			$customerSession->setAfterAuthUrl($this->_url->getUrl('offer'));
			$customerSession->authenticate();
			$message = "You are not logged in.";
			$this->_messageManager->addError($message);
            $resultRedirect->setPath('customer/account/login/');
            return $resultRedirect;   
		}
		
		$data = $this->getRequest()->getParam("productsids"); 
		$products = explode(",",$data);
		if(count($products) == 0 or $data == ''){
			$message = "You have not selected any product.";
			$this->_messageManager->addError($message);
			$resultRedirect->setPath('offer');
			return $resultRedirect;
		}
		
		foreach($products as $productId){
			$product = $this->_product->create()->load($productId);
			$params = array();      
			$params['form_key'] = $this->formKey->getFormKey();
			$params['qty'] = 1;		
			$this->_cart->addProduct($product, $params)->save();
		}
		$this->_cart->getItems()->clear()->save();
		$quote = $this->_checkoutSession->getQuote();
		$quote->save();
        $quote->collectTotals();
		$this->_cart->getQuote()->setTotalsCollectedFlag(false)->collectTotals()->save();
		
		
		$this->_checkoutSession->setOfferSet('rhkgsk%$#0003');
		$resultRedirect->setPath('checkout');
		return $resultRedirect;   
	}
}