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

protected static $staticTest;
protected static $_enums;

/** @var array */
protected static $bodyConfigs;

/** @var EmailTemplate */
protected $delegate = null;

/** @var string */
protected $subject = '';

// email de 1ere connexion  
/** @var string */
protected static $bodyNewPassword = <<<EOF
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8" />		
		<link href="%s" rel="stylesheet" type="text/css" />
	</head>
	<body id="x190513_COFFRE-FORT_Email" lang="fr-FR">
		<div class="_idGenObjectLayout-1">
			<div id="_idContainer020">
				<div id="_idContainer004">
					<div id="_idContainer002">
						<div id="_idContainer000" class="Bloc-graphique-standard _idGenObjectStyleOverride-1">
						</div>
						<div id="_idContainer001">
							<img class="_idGenObjectAttribute-1" src="%s" alt="" />
						</div>
					</div>
					<div id="_idContainer003" class="_idGenObjectStyleOverride-2">
						<p><span class="CharOverride-1">première</span></p>
						<p><span class="CharOverride-2">connexion</span></p>
					</div>
				</div>
				<div id="_idContainer008">
					<div id="_idContainer005" class="_idGenObjectStyleOverride-2">
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">Société en nom collectif au capital de 3 781 501 € </span><span class="CharOverride-3">•</span><span class="CharOverride-3"> RCS Paris B 508 305 927 </span><span class="CharOverride-3">•</span><span class="CharOverride-3"> </span><span class="CharOverride-3">TVA intracommunautaire FR96508305927 </span><span class="CharOverride-3">•</span><span class="CharOverride-3"> Code NAF 7022 Z.</span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">Société de Gestion </span><span class="CharOverride-3">de Portefeuille agréée par l’AMF sous le n° GP 13000024 du 4 juillet 2013 - www.amf-france.org.</span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">Société de courtage d’assurance enregistrée à l’ORIAS sous le n° 13007902  - www.orias.fr.</span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">Assurance et Garantie Financière RCP n° 114 231 684 auprès de Covea Risk (MMA-19-21 allée de l’Europe 92110 Clichy).</span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">Dans le cadre du Règlement Général sur la Protection des Données (RGPD), si vous ne souhaitez plus posséder de coffre-fort </span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">ou recevoir d’emails de notification, vous pouvez vous désabonner par l’écriture d’un email à</span><span class="CharOverride-4"> </span><span class="Hyperlien CharOverride-3">rgpd</span><a href=""><span class="Hyperlien CharOverride-3">@sagis-am.com</span></a><span class="CharOverride-4">. </span></p>
					</div>
					<div id="_idContainer006" class="Bloc-de-texte-standard">
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">72 avenue Victor Hugo</span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">75116 Paris </span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">T 01 76 62 26 20</span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-3">F 01 40 67 10 55</span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="Hyperlien CharOverride-3">contact</span><a href="mailto:contact%40sagis-am.com?subject="><span class="Hyperlien CharOverride-3">@sagis-am.com</span></a></p>
						<p class="Paragraphe-standard ParaOverride-1"><a href="https://www.sagis-am.com/"><span class="Hyperlien CharOverride-3">www.sagis-am.com</span></a></p>
					</div>
					<div id="_idContainer007">
						<img class="_idGenObjectAttribute-1" src="%s" alt="" />
					</div>
				</div>
				<div id="_idContainer013">
					<div id="_idContainer009" class="_idGenObjectStyleOverride-2">
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-5">Bonjour, </span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-5"> Vous pouvez activer </span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-5">votre mot de passe coffre, et </span><a href=""><span class="Hyperlien CharOverride-5">cliquez ici</span></a><span class="CharOverride-6">.  </span></p>
					</div>
					<div id="_idContainer012">
						<div id="_idContainer010" class="Bloc-de-texte-standard _idGenObjectStyleOverride-3">
							<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-7">Créez votre </span></p>
							<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-7">coffre </span><span class="CharOverride-8">ici</span></p>
						</div>
						<div id="_idContainer011">
							<img class="_idGenObjectAttribute-1" src="%s" alt="" />
						</div>
					</div>
				</div>
				<div id="_idContainer019">
					<a href="mailto:support-coffre%40sagis-am.com?subject=SAGIS_COFFRE-FORT_SUPPORT%20TECHNIQUE">
						<div id="_idContainer014">
							<img class="_idGenObjectAttribute-1" src="%s" alt="" />
						</div>
					</a>
					<div id="_idContainer017">
						<div id="_idContainer015" class="Bloc-graphique-standard _idGenObjectStyleOverride-4">
						</div>
						<div id="_idContainer016" class="Bloc-graphique-standard _idGenObjectStyleOverride-5">
						</div>
					</div>
					<div id="_idContainer018" class="Bloc-de-texte-standard">
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-9">En cas de besoin, contactez votre </span><a href="mailto:contact%40sagis-am.com%0D?subject=SAGIS_COFFRE"><span class="Hyperlien CharOverride-9">gérant privé</span></a><span class="CharOverride-9"> ou notre</span></p>
						<p class="Paragraphe-standard ParaOverride-1"><span class="CharOverride-9">équipe de</span><span class="CharOverride-10"> </span><span class="Hyperlien CharOverride-9">support</span><a href="mailto:coffre%40sagis-am.com%0D?subject=SAGIS_COFFRE"><span class="Hyperlien CharOverride-9"> technique</span></a><span class="CharOverride-9">. </span></p>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
EOF;
	/**
	 * Static initializer
	*/
	protected static function __init(IURLGenerator $urlGenerator) {
		// Read the selected theme from the config file
		$theme = \OC_Util::getTheme();

		$appPath = \OC_App::getAppPath('customizedemails');


		// for the HTML file path : if a theme is enabled
		$filePath = 'custom_apps/customizedemails/html/Welcome.html';
		
		
		// For the image paths	, build the URI	
		$imagePath = $urlGenerator->imagePath('customizedemails',$image);

		// or 
		$themingEnabled = $urlGenerator->config->getSystemValue('installed', false) && \OCP\App::isEnabled('theming') && \OC_App::isAppLoaded('theming');
		$themingImagePath = false;
		if($themingEnabled) {
			$themingDefaults = \OC::$server->getThemingDefaults();
			if ($themingDefaults instanceof ThemingDefaults) {
				$themingImagePath = $themingDefaults->replaceImagePath('customizedemails', $imagePath);
			}
		}


		$file = file_get_contents('themes/sagis/core/people.txt', true);
		$file = file_get_contents('custom_apps/sagis_email_template/html/Welcome.html', true);
		$file = file_get_contents('themes/sagis/apps/sagis_email_template/html/Welcome.html', true);
		CustomizedEmails::$bodyConfigs = [
			"core.NewPassword" => CustomizedEmails::$bodyNewPassword,
			"core.ResetPassword" => CustomizedEmails::$bodyNewPassword,
			"core.test" => "exemple",
		];
		CustomizedEmails::$_enums = [
			1 => "Apple",
			2 => "Orange",
			3 => "Banana",
		];
		CustomizedEmails::$staticTest = "TEST";
	}
	
	
	/**
	 * @param Defaults $themingDefaults
	 * @param IURLGenerator $urlGenerator
	 * @param IL10N $l10n
	 * @param string $emailId
	 * @param array $data
	 */
	public function __construct(Defaults $themingDefaults,
								IURLGenerator $urlGenerator,
								IL10N $l10n,
								$emailId,
								array $data) {
		CustomizedEmails::__init($urlGenerator);
		$this->themingDefaults = $themingDefaults;
		$this->urlGenerator = $urlGenerator;
		$this->l10n = $l10n;
		$this->emailId = $emailId;
		$this->data = $data;

		$bodyConfigs2 = [
			"core.NewPassword" => CustomizedEmails::$bodyNewPassword,
			"core.ResetPassword" => CustomizedEmails::$bodyNewPassword,
			"core.test" => "exemple",
		];

		$body =  CustomizedEmails::$bodyConfigs;

		$this->emailId = CustomizedEmails::$_enums;

// debug: $bodyConfigs null : not initialized
		if(!array_key_exists($emailId,$body)) {
			$this->delegate = new EMailTemplate(
				$themingDefaults,
				$urlGenerator,
				$l10n,
				$emailId,
				$data
			);	
		}
	}

	private function buildHtmlBody(): string {

	}

	public function setSubject(string $subject) {
		if(isset($delegate)) {
			return( $delegate.setSubject($subject));
		}
		$this->subject = $subject;
	}

	/**
	 * Adds a header to the email
	 *
	 * @since 12.0.0
	 */
	public function addHeader() {
		if(isset($delegate)) {
			return( $delegate.addHeader());
		}
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
		if(isset($delegate)) {
			return( $delegate.addHeading($title, $plainTitle));
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
		if(isset($delegate)) {
			return( $delegate.addBodyText($text, $plainText));
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
		if(isset($delegate)) {
			return( $delegate.addBodyListItem($text, $metaInfo, $icon, $plainText, $plainMetaInfo));
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
		if(isset($delegate)) {
			return( $delegate.addBodyButtonGroup($textLeft, $urlLeft, $textRight, $urlRight, $plainTextLeft, $plainTextRight));
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
		if(isset($delegate)) {
			return( $delegate.addBodyButton($text, $url, $plainText));
		}

		$urlCSS = $this->urlGenerator->getAbsoluteURL("/sagis/css/idGeneratedStyles.css");
		$urlLogoSagisNoir = $this->urlGenerator->getAbsoluteURL("/sagis/image/RVB_RES_ROUGE_SAGIS_SIGNATURE.png");
		$urlSymbolePhy = $this->urlGenerator->getAbsoluteURL("/sagis/image/190429_SYMBOLE_PHI.png");
		$urlLogoCoffreFort = $this->urlGenerator->getAbsoluteURL("/sagis/image/190304_COFFRE_FORT_LOGO-09.png");
		$urlCarteVisiteSupport =  $this->urlGenerator->getAbsoluteURL("/sagis/image/181212_Carte_de_visite_Support_technique-_Interactif_jpeg5.png");

		if($this->emailId === "core.NewPassword") {
			$this->htmlBody = vsprintf($this->bodyNewPassword, [$urlCSS,$urlLogoSagisNoir,$urlSymbolePhy,$urlLogoCoffreFort,$urlCarteVisiteSupport]);
		} else if($this->emailId === "core.ResetPassword") {
			$this->htmlBody = vsprintf($this->bodyResetPassword, [$urlCSS,$urlLogoSagisNoir,$urlSymbolePhy,$urlLogoCoffreFort,$urlCarteVisiteSupport]);
		}

		if ($plainText === '') {
			$plainText = $text;
			$text = htmlspecialchars($text);
		}



		if ($plainText !== false) {
			$this->plainBody .= $plainText . ': ';
		}

		$this->plainBody .=  $url . PHP_EOL;
		

	}



	/**
	 * Adds a logo and a text to the footer. <br> in the text will be replaced by new lines in the plain text email
	 *
	 * @param string $text If the text is empty the default "Name - Slogan<br>This is an automatically sent email" will be used
	 *
	 * @since 12.0.0
	 */
	public function addFooter(string $text = '') {
		if(isset($delegate)) {
			return( $delegate.addFooter($text));
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
		if(isset($delegate)) {
			return( $delegate.renderSubject());
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
		if(isset($delegate)) {
			return( $delegate.renderHtml());
		}
		return $this->htmlBody;
	}

	/**
	 * Returns the rendered plain text email as string
	 *
	 * @return string
	 *
	 * @since 12.0.0
	 */
	public function renderText(): string {
		if(isset($delegate)) {
			return( $delegate.renderText());
		}
		return $this->plainBody;
	}
}
