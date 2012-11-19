<?php
/**
 * Customer Universal Password configuration option backend model
 *
 * @category    Navoq
 * @package     Navoq_CustomerUniversalPassword
 * @author      Navoq Team <team@navoq.com>
 */
class Navoq_CustomerUniversalPassword_Model_System_Config_Backend_Settings_General_Password
    extends Mage_Core_Model_Config_Data
{
    /**
     * Hash password value before saving
     *
     * @return Navoq_CustomerUniversalPassword_Model_System_Config_Backend_Settings_General_Password
     */
    public function _beforeSave()
    {
        parent::_beforeSave();

        /** @var $passwordHelper Navoq_CustomerUniversalPassword_Helper_Password */
        $passwordHelper = Mage::helper('navoq_customeruniversalpassword/password');

        if ($this->getOldValue() !== $this->getValue()) {
            $this->setValue($passwordHelper->hashCustomerUniversalPassword($this->getValue()));
        }
    }
}
