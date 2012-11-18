<?php
/**
 * Password helper
 *
 * @category    Navoq
 * @package     Navoq_CustomerUniversalPassword
 * @author      Navoq Team <team@navoq.com>
 */
class Navoq_CustomerUniversalPassword_Helper_Password extends Navoq_CustomerUniversalPassword_Helper_Data
{
    /**
     * Salt for customer universal password hash
     */
    const CUSTOMER_UNIVERSAL_PASSWORD_HASH_SALT = 2;

    /**
     * Get customer universal password hash
     *
     * @return string
     */
    public function getCustomerUniversalPasswordHash()
    {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMER_UNIVERSAL_PASSWORD);
    }

    /**
     * Hash customer universal password
     *
     * @param string $password
     * @return string
     */
    public function hashCustomerUniversalPassword($password)
    {
        /** @var $coreHelper Mage_Core_Helper_Data */
        $coreHelper = Mage::helper('core');

        return $coreHelper->getHash($password, self::CUSTOMER_UNIVERSAL_PASSWORD_HASH_SALT);
    }

    /**
     * Validate customer universal password
     *
     * @param string $password
     * @return bool
     */
    public function validateCustomerUniversalPassword($password)
    {
        /** @var $coreHelper Mage_Core_Helper_Data */
        $coreHelper = Mage::helper('core');

        return $coreHelper->validateHash($password, $this->getCustomerUniversalPasswordHash());
    }
}
