<?php
declare(strict_types=1);
/**
 *
 * @author Guillaume COMPAGNON <gcompagnon@outlook.com>
 * @copyright Copyright (c) 2019, Guillaume COMPAGNON <gcompagnon@outlook.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * https://nextcloudappstore.readthedocs.io/en/latest/developer.html
 */

namespace OCA\CustomizedEmails;

use OCP\Defaults;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Mail\IEMailTemplate;
use OC\Mail\EMailTemplate;

/**
 * Class CustomizedEmails
 *
 * Generate customized HTML for serveral EmailId values, if EmailId is not part of the value, delegate to the normal EmailTemplate
 *
 * @package OCA\SagisEmailTemplate
 */
class CustomizedEmails implements IEMailTemplate {

	/**
 	* Set of found HTML contents found by $emailId
	 *  @var array
	 */
	protected static $HTMLEMAILS = array();

	/** @var EmailTemplate */
	protected $delegate = null;

	/** @var Defaults */
	protected $themingDefaults;
	/** @var IURLGenerator */
	protected $urlGenerator;
	/** @var IL10N */
	protected $l10n;
	/** @var string */
	protected $emailId;
	/** @var array */
	protected $data;

	/** @var string */
	protected $subject = '';
	/** @var string */
	protected $htmlBody = '';
	/** @var string */
	protected $plainBody = '';
	/** @var bool indicated if the footer is added */
	protected $headerAdded = false;
	/** @var bool indicated if the body is already opened */
	protected $bodyOpened = false;
	/** @var bool indicated if there is a list open in the body */
	protected $bodyListOpened = false;
	/** @var bool indicated if the footer is added */
	protected $footerAdded = false;	
	/** @var string */
	protected $emailContent = null;

	/**
 	* get all the html files content from the $dir path, and add to the $HTMLEMAILS array
 	*/
	protected static function scanHTMLPath($dir) {
		if (is_dir($dir)) {
			$files = scandir($dir);
			if ($files !== false) {
				foreach ($files as $file) {
					if (substr($file, -5) === '.html' && is_null(self::$HTMLEMAILS[substr($file, 0, -5)])) {
						$content = file_get_contents($dir . $file, false);
						if( $content !== false )
							self::$HTMLEMAILS[substr($file, 0, -5)] = $content;
					}
				}
			}
		}
	}

	/**
 	* get the html files content from the $dir path, and add to the $HTMLEMAILS array
 	*/
	 protected static function getHTMLFile($dir,$emailId) {
		$content = null;
		if ( is_null(self::$HTMLEMAILS[$emailId]) ) {
			if (is_dir($dir) && is_readable($dir . $emailId . '.html')) {
						$content = file_get_contents($dir . $emailId . '.html', false);
						if( $content != null ) {
							self::$HTMLEMAILS[$emailId] = $content;
						} else if( $content == "" ) {
							$content = "EMPTY";
						}
			}
		}
		return $content;
	}

	/**
	 * Static initializer for getting the html content from all the files available , by priority in:
	 *  themes directory and then
	 *  customizedemails Email template default file
	 *  
	 * init the html bodys for all known emailId , in 
	 */
	public static function init() {
/*
		if( count( CustomizedEmails::$HTMLEMAILS) === 0 )
		{
			// Read the selected theme from the config file
			$theme = \OC_Util::getTheme();

			// Priority 1: emails html template coming from the Themes
			CustomizedEmails::scanHTMLPath(\OC::$SERVERROOT . '/themes/' . $theme . '/apps/customizedemails/html/');

			// Priority 2: emails html template coming from the Apps path
			CustomizedEmails::scanHTMLPath(\OC_App::getAppPath('customizedemails') . '/html/');
		}
*/
	}	

	/**
	 * @param Defaults $themingDefaults
	 * @param IURLGenerator $urlGenerator
	 * @param IL10N $l10n
	 * @param string $emailId
	 * @param array $data
	 * @throws \Exception If mail could not be sent
	 */
	public function __construct(Defaults $themingDefaults,
								IURLGenerator $urlGenerator,
								IL10N $l10n,
								$emailId,
								array $data) {

		$this->themingDefaults = $themingDefaults;
		$this->urlGenerator = $urlGenerator;
		$this->l10n = $l10n;
		$this->emailId = $emailId;
		$this->data = $data;

		if(array_key_exists($emailId,self::$HTMLEMAILS)) {
			$this->emailContent = self::$HTMLEMAILS[$emailId];
		} else {
			// Read the selected theme from the config file
			$theme = \OC_Util::getTheme();

			// Priority 1: emails html template coming from the Themes
			$this->emailContent = CustomizedEmails::getHTMLFile(\OC::$SERVERROOT . '/themes/' . $theme . '/apps/customizedemails/html/',$emailId);

			// Priority 2: emails html template coming from the Apps path
			if( $this->emailContent == null ) {
				$this->emailContent = CustomizedEmails::getHTMLFile(\OC_App::getAppPath('customizedemails') . '/html/',$emailId);
			}

			// Priority 3: default template coming from Nextcloud
			if( $this->emailContent == null ) {
				$this->delegate = new EMailTemplate(
					$themingDefaults,
					$urlGenerator,
					$l10n,
					$emailId,
					$data
				);
			}

			// if the content of the file is empty => do not send an email
			if( $this->emailContent == 'EMPTY' )
				throw new \UnexpectedValueException(
					$emailId . '.html exists but is empty so the email has been desactivated, no email sent' 
				);
	
		}
	}

	public function setSubject(string $subject) {
		if(isset($this->delegate)) {
			return( $this->delegate->setSubject($subject));
		}

		// pour faciliter l integration SAGIS
		switch($this->emailId) {
			case "settings.Welcome":
				$this->subject = "SAGIS : COFFRE-FORT BIENVENUE";
			break;
			case "core.NewPassword":
				$this->subject = "SAGIS : COFFRE-FORT Première connexion";
			break;
			case "settings.PasswordChanged":
				$this->subject = "SAGIS : COFFRE-FORT Mot de passe";
			break;
			case "settings.EmailChanged":
				$this->subject = "SAGIS : COFFRE-FORT Adresse email";
			break;
			case "core.ResetPassword":
				$this->subject = "SAGIS : COFFRE-FORT Mot de passe oublié ?";
			break;
			case "files_sharing.RecipientNotification":
				$this->subject = vsprintf('SAGIS : COFFRE-FORT Nouveau document partagé par %1$s: »%2$s«',array($this->data['initiator'], $this->data['filename']) );
			break;
		}
	}

	/**
	 * Adds a header to the email
	 *
	 * @since 12.0.0
	 */
	public function addHeader() {
		if(isset($this->delegate)) {
			return( $this->delegate->addHeader());
		}
		// build the URL to the logo
		$logoUrl = $this->urlGenerator->getAbsoluteURL($this->themingDefaults->getLogo(false));
	}

	/**
	 * Adds a heading to the email
	 *
	 * @param string $title
	 * @param string|bool $plainTitle Title that is used in the plain text email
	 *   if empty the $title is used, if false none will be used
	 *
	 * @since 12.0.0
	 */
	public function addHeading(string $title, $plainTitle = '') {
		if(isset($this->delegate)) {
			return( $this->delegate->addHeading($title, $plainTitle));
		}
		if ($plainTitle === '') {
			$plainTitle = $title;
		}
		if ($plainTitle !== false) {
			$this->plainBody .= $plainTitle . PHP_EOL . PHP_EOL;
		}
	}


	/**
	 * Adds a paragraph to the body of the email
	 *
	 * @param string $text; Note: When $plainText falls back to this, HTML is automatically escaped in the HTML email
	 * @param string|bool $plainText Text that is used in the plain text email
	 *   if empty the $text is used, if false none will be used
	 *
	 * @since 12.0.0
	 */
	public function addBodyText(string $text, $plainText = '') {
		if(isset($this->delegate)) {
			return( $this->delegate->addBodyText($text, $plainText));
		}
		if ($plainText === '') {
			$plainText = $text;
			$text = htmlspecialchars($text);
		}

		if ($plainText !== false) {
			$this->plainBody .= $plainText . PHP_EOL . PHP_EOL;
		}
	}

	/**
	 * Adds a list item to the body of the email
	 *
	 * @param string $text; Note: When $plainText falls back to this, HTML is automatically escaped in the HTML email
	 * @param string $metaInfo; Note: When $plainMetaInfo falls back to this, HTML is automatically escaped in the HTML email
	 * @param string $icon Absolute path, must be 16*16 pixels
	 * @param string|bool $plainText Text that is used in the plain text email
	 *   if empty the $text is used, if false none will be used
	 * @param string|bool $plainMetaInfo Meta info that is used in the plain text email
	 *   if empty the $metaInfo is used, if false none will be used
	 * @since 12.0.0
	 */
	public function addBodyListItem(string $text, string $metaInfo = '', string $icon = '', $plainText = '', $plainMetaInfo = '') {
		if(isset($this->delegate)) {
			return( $this->delegate->addBodyListItem($text, $metaInfo, $icon, $plainText, $plainMetaInfo));
		}
	}

	/**
	 * Adds a button group of two buttons to the body of the email
	 *
	 * @param string $textLeft Text of left button; Note: When $plainTextLeft falls back to this, HTML is automatically escaped in the HTML email
	 * @param string $urlLeft URL of left button
	 * @param string $textRight Text of right button; Note: When $plainTextRight falls back to this, HTML is automatically escaped in the HTML email
	 * @param string $urlRight URL of right button
	 * @param string $plainTextLeft Text of left button that is used in the plain text version - if empty the $textLeft is used
	 * @param string $plainTextRight Text of right button that is used in the plain text version - if empty the $textRight is used
	 *
	 * @since 12.0.0
	 */
	public function addBodyButtonGroup(string $textLeft, string $urlLeft, string $textRight, string $urlRight, string $plainTextLeft = '', string $plainTextRight = '') {
		if(isset($this->delegate)) {
			return( $this->delegate->addBodyButtonGroup($textLeft, $urlLeft, $textRight, $urlRight, $plainTextLeft, $plainTextRight));
		}
	}
	

	/**
	 * Adds a button to the body of the email
	 *
	 * @param string $text Text of button; Note: When $plainText falls back to this, HTML is automatically escaped in the HTML email
	 * @param string $url URL of button
	 * @param string $plainText Text of button in plain text version
	 * 		if empty the $text is used, if false none will be used
	 *
	 * @since 12.0.0
	 */	
	public function addBodyButton(string $text, string $url, $plainText = '') {
		if(isset($this->delegate)) {
			return( $this->delegate->addBodyButton($text, $url, $plainText));
		}
	}



	/**
	 * Adds a logo and a text to the footer. <br> in the text will be replaced by new lines in the plain text email
	 *
	 * @param string $text If the text is empty the default "Name - Slogan<br>This is an automatically sent email" will be used
	 *
	 * @since 12.0.0
	 */
	public function addFooter(string $text = '') {
		if(isset($this->delegate)) {
			return( $this->delegate->addFooter($text));
		}
	}

	/**
	 * Returns the rendered email subject as string
	 *
	 * @return string
	 *
	 * @since 13.0.0
	 */
	public function renderSubject(): string {
		if(isset($this->delegate)) {
			return( $this->delegate->renderSubject());
		}

		return $this->subject;
	}

	/**
	 * Returns the rendered HTML email as string
	 *
	 * @return string
	 *
	 * @since 12.0.0
	 */
	public function renderHtml(): string {
		if(isset($this->delegate)) {
			return( $this->delegate->renderHtml());
		}

		$url = $this->urlGenerator->getAbsoluteURL("/newpassword" . (null !== $this->data['emailAddress'] ? '/' . $this->data['emailAddress'] : '') );
		$urlLogin = $this->urlGenerator->getAbsoluteURL("/login" . (null !== $this->data['emailAddress'] ? '?user=' . $this->data['emailAddress'] : '') );

		// pour faciliter l integration SAGIS
		switch($this->emailId) {
			case "settings.Welcome":				
				$this->emailContent = str_replace('%link%', $url, $this->emailContent);
			break;
			case "core.NewPassword":
			case "core.ResetPassword":
				$this->emailContent = str_replace('%link%', $this->data['link'], $this->emailContent);
			break;
			case "settings.PasswordChanged":
				$this->emailContent = str_replace('%link%', $urlLogin, $this->emailContent);
				$this->emailContent = str_replace('%linkReset%', $url, $this->emailContent);
			case "settings.EmailChanged":				
				$urlLogin = $this->urlGenerator->getAbsoluteURL("/login" . (null !== $this->data['newEMailAddress'] ? '?user=' . $this->data['newEMailAddress'] : '') );
				$this->emailContent = str_replace('%link%', $urlLogin, $this->emailContent);
			break;
			case "files_sharing.RecipientNotification":
				$this->emailContent = str_replace('%link%', $this->data['link'], $this->emailContent);
			break;
		}
		return $this->emailContent;
	}

	/**
	 * Returns the rendered plain text email as string
	 *
	 * @return string
	 *
	 * @since 12.0.0
	 */
	public function renderText(): string {
		if(isset($this->delegate)) {
			return( $this->delegate->renderText());
		}
		return $this->plainBody;
	}
	
}

CustomizedEmails::init();