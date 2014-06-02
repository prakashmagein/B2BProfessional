<?php

/**
 * Sitewards_B2BProfessional_Helper_Data
 *  - Helper containing the checks for
 *      - extension is active,
 *      - product is active,
 *      - is the category active,
 *
 * @category    Sitewards
 * @package     Sitewards_B2BProfessional
 * @copyright   Copyright (c) 2014 Sitewards GmbH (http://www.sitewards.com/)
 */
class Sitewards_B2BProfessional_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Path for the config for extension active status
     */
    const CONFIG_EXTENSION_ACTIVE = 'b2bprofessional/generalsettings/active';

    /**
     * Variable for if the extension is active
     *
     * @var bool
     */
    protected $_isExtensionActive;

    /**
     * Variable for if the extension is active by category
     *
     * @var bool
     */
    protected $_isExtensionActiveByCategory;

    /**
     * Check to see if the extension is active
     *
     * @return bool
     */
    public function isExtensionActive()
    {
        if (empty($this->_isExtensionActive)) {
            $this->_isExtensionActive = Mage::getStoreConfigFlag(self::CONFIG_EXTENSION_ACTIVE);
        }
        return $this->_isExtensionActive;
    }

    /**
     * Check to see if the extension is active by category
     *
     * @return bool
     */
    public function isExtensionActiveByCategory()
    {
        if (empty($this->_isExtensionActiveByCategory)) {
            $this->_isExtensionActiveByCategory = Mage::helper(
                'sitewards_b2bprofessional/category'
            )->isExtensionActivatedByCategory();
        }
        return $this->_isExtensionActiveByCategory;
    }

    /**
     * Check to see if the given product is active
     *  - In this case active means product behaves as normal in a magento shop
     *
     * @param Mage_Catalog_Model_Product $oProduct
     * @return bool
     */
    public function isProductActive(Mage_Catalog_Model_Product $oProduct)
    {
        $bIsProductActive = true;
        if ($this->isExtensionActive() === true) {
            $bIsCustomerActive = $this->isCustomerActive();

            $bCheckCategory = $this->isExtensionActiveByCategory();
            if (
                $bCheckCategory === true
                && $bIsCustomerActive === false
            ) {
                $bIsProductActive = !Mage::helper('sitewards_b2bprofessional/category')->isCategoryActiveByProduct(
                    $oProduct
                );
            }
        }

        return $bIsProductActive;
    }

    /**
     * From an array of category ids check to see if any are disabled via the extension
     *
     * @param array $aCategoryIds
     * @return bool
     */
    public function hasActiveCategories($aCategoryIds)
    {
        $bHasCategories = false;
        if ($this->isExtensionActive() === true) {
            $bIsCustomerActive = $this->isCustomerActive();

            $bCheckCategory = $this->isExtensionActiveByCategory();
            if (
                $bCheckCategory === true
                && $bIsCustomerActive === false
            ) {
                $bHasCategories = Mage::helper('sitewards_b2bprofessional/category')->hasActiveCategory($aCategoryIds);
            }
        }
        return $bHasCategories;
    }

    /**
     * Check if the customer is active
     *
     * @return bool
     */
    public function isCustomerActive()
    {
        return Mage::helper('sitewards_b2bprofessional/customer')->isCustomerActive();
    }
}