<?php
/**
 * Nonce controller
 *
 * @category    Navoq
 * @package     Navoq_CustomerUniversalPassword
 * @author      Navoq Team <team@navoq.com>
 */
class Navoq_CustomerUniversalPassword_NonceController extends Mage_Core_Controller_Front_Action
{
    /**
     * Check the nonce value
     */
    public function checkAction()
    {
        $nonceValue = $this->getRequest()->getParam('nonce', null);

        /** @var $nonce Navoq_CustomerUniversalPassword_Model_Nonce */
        $nonce = Mage::getModel('navoq_customeruniversalpassword/nonce')->load($nonceValue, 'nonce');
        if (null === $nonce->getNonce()) {
            return $this->_redirect('customer/account/login');
        } else {
            /** @var $session Mage_Customer_Model_Session */
            $session = Mage::getSingleton('customer/session');

            if (true === $session->loginById($nonce->getCustomerId())) {
                $nonce->delete();

                return $this->_redirect('customer/account/index');
            }
        }
    }
}
