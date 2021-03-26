<?php
/**
 * @Author : Pkgroup
 * @Package : Pkgroup_Customeroffer
 * @Developer : Puneet Kumar
 */
namespace Pkgroup\Customeroffer\Block;

class Index extends \Magento\Framework\View\Element\Template
{
	
	/**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
	
	protected $_productRepository;
    
	protected $_productImageHelper;
	
	protected $_categorycollection;
	
	const XML_PATH_CATEGORIES = 'pkgroup_customeroffer/configuration/categories';
  
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		\Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Helper\Image $productImageHelper,
		\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $category,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {    
		$this->scopeConfig = $scopeConfig;
		$this->_categorycollection = $category;
		$this->_productRepository = $productRepository;
        $this->_productImageHelper= $productImageHelper;
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }
	
	
	public function getCategories(){
			$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
			return $this->scopeConfig->getValue(self::XML_PATH_CATEGORIES, $storeScope);
	}
    
    
    public function getProductCollectionByCategories($id)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('type'=>['in' => 'simple']);
        $collection->addCategoriesFilter(['in' => $id]);
        return $collection;
    }
	
	public function getCategoryCollectionbyids()
    {
		$ids = explode(",",$this->getCategories());
        $collection = $this->_categorycollection->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter("entity_id",['in' => $ids]);
        return $collection;
    }
	
	public function getImageOriginalWidth($product, $imageId, $attributes = [])
    {
        $imageUrl = $this->_productImageHelper->init($product, $imageId, $attributes)
                ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
                ->resize(200)
                ->getUrl();
		return $imageUrl;
	}
    
    /**
     * Retrieve image height
     *
     * @return int|null
     */
    public function getImageOriginalHeight($product, $imageId, $attributes = [])
    {
        return $this->_productImageHelper->init($product, $imageId, $attributes)->getHeight();
    }

}