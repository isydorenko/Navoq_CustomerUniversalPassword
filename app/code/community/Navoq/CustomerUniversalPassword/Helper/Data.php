<?php
/**
 * Data helper
 *
 * @category    Navoq
 * @package     Navoq_CustomerUniversalPassword
 * @author      Navoq Team <team@navoq.com>
 */
class Navoq_CustomerUniversalPassword_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**#@+
     * Module xpath config settings
     */
    const XML_PATH_EMAIL = 'customeruniversalpassword_settings/general/email';
    const XML_PATH_CUSTOMER_UNIVERSAL_PASSWORD = 'customeruniversalpassword_settings/general/password';
    /**#@-*/

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL);
    }

    /**
     * Get customer universal password
     *
     * @return string
     */
    public function getCustomerUniversalPassword()
    {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMER_UNIVERSAL_PASSWORD);
    }
}
