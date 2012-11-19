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
     * @return Navoq_CustomerUniversalPassword_Model_Nonce
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

        $nonce->setCustomerId($customer->getId())
            ->setTimestamp(time())
            ->setNonce($coreHelper->uniqHash())
            ->save();

        return $nonce;
    }

    /**
     * Send notification with unique link
     *
     * @param Navoq_CustomerUniversalPassword_Model_Nonce $nonce
     * @return bool
     */
    public function sendNotificationOnNonceGenerate(Navoq_CustomerUniversalPassword_Model_Nonce $nonce)
    {
        $url = Mage::getUrl('navoq_customeruniversalpassword/nonce/check', array('nonce' => $nonce->getNonce()));

        /* @var $mailTemplate Mage_Core_Model_Email_Template */
        $mailTemplate = Mage::getModel('core/email_template');

        $mailTemplate->setTemplateSubject('test subject');
        $mailTemplate->sendTransactional(
            $this->getEmailTemplate(),
            $this->getEmailIdentity(),
            $this->getEmail(),
            'Some Name',
            array(
                'url' => $url
            )
        );

        return $mailTemplate->getSentSuccess();
    }
}
