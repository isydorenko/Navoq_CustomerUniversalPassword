<?php
/**
 * Nonce model
 *
 * @category    Navoq
 * @package     Navoq_CustomerUniversalPassword
 * @author      Navoq Team <team@navoq.com>
 * @method string getNonce()
 * @method Navoq_CustomerUniversalPassword_Model_Nonce setNonce() setNonce(string $nonce)
 * @method string getTimestamp()
 * @method Navoq_CustomerUniversalPassword_Model_Nonce setTimestamp() setTimestamp(string $timestamp)
 * @method int getCustomerId()
 * @method Navoq_CustomerUniversalPassword_Model_Nonce setCustomerId() setCustomerId(int $customerId)
 * @method Navoq_CustomerUniversalPassword_Model_Resource_Nonce getResource()
 * @method Navoq_CustomerUniversalPassword_Model_Resource_Nonce _getResource()
 */
class Navoq_CustomerUniversalPassword_Model_Nonce extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('navoq_customeruniversalpassword/nonce');
    }

    /**
     * "After save" actions
     *
     * @return Navoq_CustomerUniversalPassword_Model_Nonce
     */
    protected function _afterSave()
    {
        parent::_afterSave();

        //Cleanup old entries
        /** @var $helper Navoq_CustomerUniversalPassword_Helper_Data */
        $helper = Mage::helper('navoq_customeruniversalpassword');
        if ($helper->isCleanupEnabled()) {
            $this->_getResource()->deleteOldEntries($helper->getCleanupExpirationPeriod());
        }

        return $this;
    }
}
