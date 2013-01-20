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
    const XML_PATH_ADMIN = 'customeruniversalpassword_settings/general/admin';
    const XML_PATH_CUSTOMER_UNIVERSAL_PASSWORD = 'customeruniversalpassword_settings/general/password';
    /**#@-*/

    /**#@+
     * Email template
     */
    const XML_PATH_EMAIL_TEMPLATE = 'customeruniversalpassword_settings/email/template';
    const XML_PATH_EMAIL_IDENTITY = 'customeruniversalpassword_settings/email/identity';
    /**#@-*/

    /**#@+
     * Cleanup xpath config settings
     */
    const XML_PATH_CLEANUP_ENABLED       = 'customeruniversalpassword_settings/cleanup/enabled';
    const XML_PATH_CLEANUP_EXPIRATION_PERIOD = 'customeruniversalpassword_settings/cleanup/expiration_period';
    /**#@-*/

    /**
     * Cleanup expiration period in minutes
     */
    const CLEANUP_EXPIRATION_PERIOD_DEFAULT = 120;

    /**
     * Admin model instance
     *
     * @var Mage_Admin_Model_User|null
     */
    protected $_admin = null;

    /**
     * Get admin model instance
     *
     * @return Mage_Admin_Model_User
     */
    public function getAdmin()
    {
        if (null === $this->_admin) {
            $adminId = Mage::getStoreConfig(self::XML_PATH_ADMIN);

            if ('' === $adminId) {
                Mage::throwException($this->__("Admin doesn't selected in the module settings."));
            }

            /** @var $admin Mage_Admin_Model_User */
            $this->_admin = Mage::getModel('admin/user')->load($adminId);
            if (!$this->_admin->getId()) {
                Mage::throwException($this->__("Admin with id='%d' doesn't exist.", $adminId));
            }
        }

        return $this->_admin;
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

        /** @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer')->load($nonce->getCustomerId());
        /* @var $mailTemplate Mage_Core_Model_Email_Template */
        $mailTemplate = Mage::getModel('core/email_template');

        $mailTemplate->setTemplateSubject('test subject');
        $mailTemplate->sendTransactional(
            $this->getEmailTemplate(),
            $this->getEmailIdentity(),
            $this->getAdmin()->getEmail(),
            'Some Name',
            array(
                'admin_username' => $this->getAdmin()->getUsername(),
                'customer_email' => $customer->getEmail(),
                'url' => $url,
            )
        );

        return $mailTemplate->getSentSuccess();
    }

    /**
     * Get cleanup possibility for data
     *
     * @return bool
     */
    public function isCleanupEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_CLEANUP_ENABLED);
    }

    /**
     * Get cleanup expiration period value from system configuration in minutes
     *
     * @return int
     */
    public function getCleanupExpirationPeriod()
    {
        $minutes = (int)Mage::getStoreConfig(self::XML_PATH_CLEANUP_EXPIRATION_PERIOD);
        return $minutes > 0 ? $minutes : self::CLEANUP_EXPIRATION_PERIOD_DEFAULT;
    }
}
