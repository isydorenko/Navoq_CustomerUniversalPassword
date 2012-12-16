<?php
/**
 * Admin setting source model
 *
 * @category    Navoq
 * @package     Navoq_CustomerUniversalPassword
 * @author      Navoq Team <team@navoq.com>
 */
class Navoq_CustomerUniversalPassword_Model_Source_Settings_Admin
{
    public function toOptionArray()
    {
        $output = array('' => '-- Please select --');

        /** @var $adminCollection Mage_Admin_Model_Resource_User_Collection */
        $adminCollection = Mage::getResourceModel('admin/user_collection');

        /** @var $admin Mage_Admin_Model_User */
        foreach ($adminCollection as $admin) {
            $output[$admin->getId()] = $admin->getUsername();
        }

        return $output;
    }
}
