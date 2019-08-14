# nextcloud-custom_apps


## Customized Emails{#customized-emails}

add HTML templates for theming the NextCloud Mail messages

### Objective

For all the Mail message sent to the user, NextCloud uses the EmailTemplate.php with a single HTML structure 

<<add a snapshot of existing email>>

The *customized-emails* makes possible to add your own HTML email template (files), specific for each action/notification cases.

- Welcome.html for settings.Welcome / first email sent to a new User
- EmailCHanged.html for settings.EmailChanged / User has changed his email
- PasswordChanged.html for settings.PasswordChanged / User has changed his password
- FilesSharing.html for files_sharing.RecipientNotification / email after a file sharing
- FilesSharing.html for defaultShareProvider.sendNote / email after a note
- ResetPassword.html for core.ResetPassword / User has requested a password reset
- NewPassword.html for core.NewPassword / User has activate his account and set up his first password
by default, use by delegation the existing NextCloud EmailTemplate

by setup, it would be possible to 
- add extra HTML files for any new emailId / new email sent by apps
- control emails sending , by activate / desactivate temporary the emails of specific actions



### How to Setup