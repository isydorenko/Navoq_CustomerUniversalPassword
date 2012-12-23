Magento doesn't display customer password in the admin panel. So the only way is to change customer password. But it's a bad solution. This module lets you to log in onto front-end of your website as one of your customers, using a universal password.

Workflow:
- Set a universal password in the admin panel. Password is stored as MD5 hash. Nobody can't get it as plaintext.
- Set an admin user in the admin panel. This step is needed for sending emails with a unique link for accessing to customers account.
- Try to log in with the universal password. An email with a unique link will be send to the admin user, which was set in previous step.
- Open the email and follow the link.
