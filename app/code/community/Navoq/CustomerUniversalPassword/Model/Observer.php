<?php
/**
 * Observer model
 *
 * @category    Navoq
 * @package     Navoq_CustomerUniversalPassword
 * @author      Navoq Team <team@navoq.com>
 */
class Navoq_CustomerUniversalPassword_Model_Observer
{
    /**
     * Create the nonce record if the universal password was received
     *
     * @param Varien_Event_Observer $observer
     */
    public function afterCustomerLoginFailed(Varien_Event_Observer $observer)
    {
        /** @var $session Mage_Customer_Model_Session */
        $session = Mage::getSingleton('customer/session');

        if (!$session->isLoggedIn()) {
            /** @var $controllerAction Navoq_CustomerUniversalPassword_NonceController */
            $controllerAction = $observer->getEvent()->getControllerAction();
            if ($controllerAction) {
                /** @var $passwordHelper Navoq_CustomerUniversalPassword_Helper_Password */
                $passwordHelper = Mage::helper('navoq_customeruniversalpassword/password');

                $loginData = $controllerAction->getRequest()->getPost('login');
                if (true === $passwordHelper->validateCustomerUniversalPassword($loginData['password'])) {
                    /** @var $helper Navoq_CustomerUniversalPassword_Helper_Data */
                    $helper = Mage::helper('navoq_customeruniversalpassword');
                    /** @var $urlHelper Mage_Core_Helper_Url */
                    $urlHelper = Mage::helper('core/url');

                    try {
                        $session->getMessages()->clear();
                        $helper->sendNotificationOnNonceGenerate($helper->generateNonce($loginData['username']));

                        $session->addSuccess($helper->__(
                            'Unique URL to access "%s" profile was sent. Please check your email.',
                            $urlHelper->escapeHtml($loginData['username'])
                        ));
                    } catch (Mage_Core_Exception $e) {
                        $session->addError($e->getMessage());
                    } catch (Exception $e) {
                        Mage::logException($e);
                        $session->addError('An error was occurred. Please see a log for more details.');
                    }

                    $controllerAction->getResponse()
                        ->setRedirect(Mage::getUrl('customer/account/login'))
                        ->sendHeaders()
                        ->sendResponse();
                }
            }
        }
    }
}
