<?php
/**
 * Nonce model
 *
 * @category    Navoq
 * @package     Navoq_CustomerUniversalPassword
 * @author      Navoq Team <team@navoq.com>
 * @method string getNonce()
 * @method Navoq_CustomerUniversalPassword_Model_Nonce_Resource_Nonce setNonce() setNonce(string $nonce)
 * @method string getTimestamp()
 * @method Navoq_CustomerUniversalPassword_Model_Nonce setTimestamp() setTimestamp(string $timestamp)
 * @method int getCustomerId()
 * @method Navoq_CustomerUniversalPassword_Model_Nonce setCustomerId() setCustomerId(int $customerId)
 * @method Navoq_CustomerUniversalPassword_Model_Nonce_Resource_Nonce getResource()
 * @method Navoq_CustomerUniversalPassword_Model_Nonce_Resource_Nonce _getResource()
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
     * Delete old entries
     *
     * @param int $minutes Delete entries older than
     * @return int
     */
    public function deleteOldEntries($minutes)
    {
        if ($minutes > 0) {
            $adapter = $this->_getWriteAdapter();

            return $adapter->delete(
                $this->getMainTable(), $adapter->quoteInto('timestamp <= ?', time() - $minutes * 60, Zend_Db::INT_TYPE)
            );
        } else {
            return 0;
        }
    }
}
