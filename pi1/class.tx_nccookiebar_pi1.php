<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Tomita Militaru <tmilitaru@arxia.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;


/**
 * Plugin 'Cookie bar' for the 'nc_cookiebar' extension.
 *
 * @author	Tomita Militaru <tmilitaru@arxia.com>
 * @package	TYPO3
 * @subpackage	tx_nccookiebar
 */
class tx_nccookiebar_pi1 extends AbstractPlugin {

	public $prefixId = 'tx_nccookiebar_pi1';  // Same as class name
	public $scriptRelPath = 'pi1/class.tx_nccookiebar_pi1.php'; // Path to this script relative to the extension dir.
	public $extKey = 'nc_cookiebar'; // The extension key.
	public $pi_checkCHash = TRUE;

	/**
	 * The main method of the Plugin.
	 *
	 * @param string $content The Plugin content
	 * @param array $conf The Plugin configuration
	 * @return string The content that is displayed on the website
	 */
	public function main($content, array $conf) {
		$this->pi_setPiVarDefaults();
		$this->conf = $conf;
		$this->pi_loadLL();  // Loading the LOCAL_LANG values

        $templateFile = $conf['template'] == ''
            ? 'typo3conf/ext/nc_cookiebar/Resources/template.html'
            : $conf['template'];
		$template = $this->cObj->fileResource($templateFile);

        $markers = explode(
            ',',
            'cookie_url,cookie_error_message,cookie_accept,cookie_decline,cookie_reset,cookie_what,'
            . 'cookie_policy_page,cookie_discreet,cookie_message_before,cookie_message_after,cookie_message_link_text,'
            . 'cookie_analytics_message'
        );

        $markerArray = [];
		foreach ($markers as $marker) {
			$markerArray['###' . strtoupper($marker) . '###'] = $this->pi_getLL($marker);
		}
		
		$googleAnalytics = $this->pi_getLL('ga') == '{$google-analytics}'
            ? ''
            : $this->pi_getLL('ga');
		$content = $this->cObj->substituteMarkerArray($template, $markerArray);

		$cookieCutterLocalizedScriptPath = PATH_site . 'typo3temp/jquery.cookiecuttr.js';

		// Only re-write localized jquery.cookiecuttr.js script if it has changed from the current on-disc version.
        // This avoids uselessly cluttering and overfilling typo3temp/compressor/ when config.concatenateJs
        // and config.compressJs are enabled.
        if (file_get_contents($cookieCutterLocalizedScriptPath) !== $content) {
            file_put_contents($cookieCutterLocalizedScriptPath, $content);
        }

		return '

			<script type="text/javascript">
				var Netcreators = Netcreators || {};
				Netcreators.CookieBar = Netcreators.CookieBar || {};
				Netcreators.CookieBar.analyticsAccount = \'' . $googleAnalytics .'\';
			</script>
			
			';
	}

}
