Magento doesn't display customer password in the admin panel. So the only way to know it is to change customer password itself. But it is a bad solution.

This module logs you to front-end of your website as one of your customers, using a universal password.

Workflow:
- Set an universal password in the admin panel. Password is stored as MD5 hash. Nobody can't get it as plain text.
- Set an admin user in the admin panel. This step is needed for sending emails with a unique link for accessing to customers account.
- Try to log in with the universal password. An email with an unique link will be sent to the admin user, which was set in the previous step.
- Open the email and follow the link.

Extension on Magento Connect: http://www.magentocommerce.com/magento-connect/catalog/product/view/id/15763/
