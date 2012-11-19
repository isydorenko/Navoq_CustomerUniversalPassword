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

    /**#@+
     * Email template
     */
    const XML_PATH_EMAIL_TEMPLATE = 'customeruniversalpassword_settings/email/template';
    const XML_PATH_EMAIL_IDENTITY = 'customeruniversalpassword_settings/email/identity';
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
     * Get email template
     *
     * @return string
     */
    public function getEmailTemplate()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE);
    }

    /**
     * Get email identity
     *
     * @return string
     */
    public function getEmailIdentity()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY);
    }

    /**
     * Generate nonce for customer
     *
     * @param $customerEmail
     * @return string
     */
    public function generateNonce($customerEmail)
    {
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer');
        $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($customerEmail);

        if (null === $customer->getId()) {
            /** @var $helper Navoq_CustomerUniversalPassword_Helper_Data */
            $helper = Mage::helper('navoq_customeruniversalpassword');

            Mage::throwException($helper->__('Customer was not found.'));
        }

        /** @var $nonce Navoq_CustomerUniversalPassword_Model_Nonce */
        $nonce = Mage::getModel('navoq_customeruniversalpassword/nonce')->load($customer->getId(), 'customer_id');
        /** @var $coreHelper Mage_Core_Helper_Data */
        $coreHelper = Mage::helper('core');

        $nonceValue = $coreHelper->uniqHash();

        $nonce->setCustomerId($customer->getId())
            ->setTimestamp(time())
            ->setNonce($nonceValue)
            ->save();

        return $nonceValue;
    }
}
