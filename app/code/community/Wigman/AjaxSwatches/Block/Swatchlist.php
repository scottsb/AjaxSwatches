<?php
class Wigman_AjaxSwatches_Block_Swatchlist extends Mage_Core_Block_Template
{
    protected $_product;

    protected function _construct()
    {
        parent::_construct();
    }

    protected function _toHtml()
    {
        $pid = $this->getPid();

        $storeId = Mage::App()->getStore()->getId();

        /* @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product')
            ->setStoreId($storeId)
            ->load($pid);

        /* @var $helper Mage_ConfigurableSwatches_Helper_Mediafallback */
        $helper = Mage::helper('configurableswatches/mediafallback');

        $productArrayWrapper = array($pid => $product);
        $helper->attachChildrenProducts($productArrayWrapper, $storeId);
        $helper->attachConfigurableProductChildrenAttributeMapping($productArrayWrapper, $storeId);
        $helper->attachGallerySetToCollection($productArrayWrapper, $storeId);
        $helper->groupMediaGalleryImages($product);
        Mage::helper('configurableswatches/productimg')
            ->indexProductImages($product, $product->getListSwatchAttrValues());

        $this->_product = $product;

        return parent::_toHtml();
    }

    public function getProduct()
    {
        return $this->_product;
    }

    protected function _isCacheActive()
    {
        /* if there are any messages dont read from cache to show them */
        if (Mage::getSingleton('core/session')->getMessages(true)->count() > 0) {
            return false;
        }
        return true;
    }

    public function getCacheLifetime()
    {
        if ($this->_isCacheActive()) {
            return false;
        }
    }

    public function getCacheKey()
    {
        if (!$this->_isCacheActive()) {
            parent::getCacheKey();
        }
        $cacheKey = 'SwatchList_'.
            /* Create different caches for different categories */
            $this->getPid().'_'.
            /* ... stores */
            Mage::App()->getStore()->getCode();

        return $cacheKey;
    }

    public function getCacheTags()
    {
        if (!$this->_isCacheActive()) {
            return parent::getCacheTags();
        }
        $cacheTags = array(
            Mage_Catalog_Model_Product::CACHE_TAG,
            Mage_Catalog_Model_Product::CACHE_TAG.'_'.$this->getPid()
        );

        return $cacheTags;
    }
}
