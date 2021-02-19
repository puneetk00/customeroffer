<?php
namespace Pkgroup\Customeroffer\Controller\Index;

class Test extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $_quote;
	protected $_checkout;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Quote\Api\CartRepositoryInterface $quote,
		\Magento\Checkout\Model\Session $checkout)
	{
		$this->_quote = $quote;
		$this->_checkout = $checkout;
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		
		 $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
		 $cartObject = $objectManager->create('Magento\Checkout\Model\Cart')->truncate(); 
		 $cartObject->saveQuote();
		 die("it workes");
		// $quote = $this->_quote->getQuote();
        // $this->_checkout->setQuoteId(null);
        // $quote->setIsActive(false);
        // $this->_checkout->save($quote);
		 // die("resert");
		
		$quote = $this->_quote->get(13);
		$quote->setIsActive(1);
		$quote->save($quote);
		$this->_checkout->replaceQuote($quote);
		die("test");
		//return $this->_pageFactory->create();
	}
}