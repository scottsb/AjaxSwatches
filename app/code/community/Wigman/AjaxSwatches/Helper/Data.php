<?php
class Wigman_AjaxSwatches_Helper_Data
{
	protected $_products;

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct($productId)
    {
        if (! $this->_products) {
        	// Load all the products that are needed for this page in one shot.
        	// ...
        }

        if (! isset($this->_products[$productId])) {
        	throw Exception('Unloaded product accessed in AjaxSwatches.');
        }

        return $this->_products[$productId];
    }
}
