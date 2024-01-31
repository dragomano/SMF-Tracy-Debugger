<?php declare(strict_types = 1);

/**
 * PortalPanel.php
 *
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2024 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 *
 * @version 0.5
 */

namespace Bugo\Tracy\Panels;

class PortalPanel extends AbstractPanel
{
	public function getTab(): string
	{
		if (! defined('LP_NAME'))
			return '';

		return $this->getSimpleTab(LP_NAME, '', '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAJ2SURBVDhPrZJfSFNRHMd/5/7dvZtrW/NfWWv+o8KCIJwFg1TQGT0nIeFDD0EpBfaSQfUSPUVUghT4UEGP0UMIMzEicItCwyAcpsxYummmuXbv7t39U+dw/DN6rM/L+f35ni+/3+HAv4LoucXDD/w1VyJ0tmS2dUio6RjnfYqqQ9dER8ciVRTxl0HFQPx+kM+ddvGGv1r6xbqlAszLYiIFzpbxtrYFKtukyGD3vXiVR9RGvVyhFiFgaRlYZIPBwpd0znHjpyWMLfeE0rQFDD0JDqHQ4uH0mu2XMaaNABmotlzIPy5jtdcVA7G7eFXc2zRoHB6uKpfyNxmEOFoiBDxOGDx1FK6faADc8/J6XZBXzlyWZ5pwnxgcHxnZtYfRx3gTgjjfIBwohf7wQXj2aR72+91kXzydzBn+8+65MNYQA8SYTwOqVmdtexKOYeBIpRdeTKegs2Ev9EUnwaa9ainHPnIEIzgmBmHrB5dVyUpQIvJQ6hTBsCx48jEJvaF6uP32MyxmVdLHSJIB71jfOo6JwTltLjqnOk0cHyh1w63WwyCyDDSU7YA3yaU/Y29NhqN52TFVsNlunJPXNiO99ldNPskgW17IqoxfFuFCYz1UuBxwJ5aAFUXDMkKBs6c1CZrjkch3nBOD1ebuTMZ2VzOIcYqM4ZnKrLG5gglDE7OgmxaWECwb9LQqXZnqao3TUvFHKh+M7XNYVnOlrFzlLVRHywTbss1VU0isaWL7t0tNKVouNtjgWDRathP00YCSP6SqHCTzsqHo3HLSdD1PXwz1UBlh8yNtJ9bevpQV7JbJEtdLr1959cD3vr9GWO90GSt9VPK/APgNg6vVv8QSuBAAAAAASUVORK5CYII="/>');
	}

	public function getPanel(): string
	{
		global $txt;

		if (! defined('LP_NAME'))
			return '';

		return $this->getTablePanel([
			'LP_NAME'          => LP_NAME,
			'LP_VERSION'       => LP_VERSION,
			'LP_PLUGIN_LIST'   => LP_PLUGIN_LIST,
			'LP_ADDON_URL'     => LP_ADDON_URL,
			'LP_ADDON_DIR'     => LP_ADDON_DIR,
			'LP_CACHE_TIME'    => LP_CACHE_TIME,
			'LP_ACTION'        => LP_ACTION,
			'LP_PAGE_PARAM'    => LP_PAGE_PARAM,
			'LP_BASE_URL'      => LP_BASE_URL,
			'LP_PAGE_URL'      => LP_PAGE_URL,
			'LP_ALIAS_PATTERN' => LP_ALIAS_PATTERN,
			'LP_AREAS_PATTERN' => LP_AREAS_PATTERN,
			'LP_ADDON_PATTERN' => LP_ADDON_PATTERN,
		], $txt['tracy_portal_panel']);
	}
}
