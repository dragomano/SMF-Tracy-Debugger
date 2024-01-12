<?php

declare(strict_types = 1);

/**
 * RequestPanel.php
 *
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2024 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 *
 * @version 0.1
 */

namespace Bugo\Tracy\Panels;

use Tracy\Debugger;

class RequestPanel extends AbstractPanel
{
	public function getTab(): string
	{
		return $this->getSimpleTab('Request', '', '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAABRElEQVQ4jc2RTSuEYRiFr/udmSglZUGvkSkzGxsplP8g2dhYKJk/YTdrP0AmJIspsZLlkJX8BfmcIiSSr6Zp5n2OxXiZyVCz4iyeTqfn/jjnhn+Fwb37nqH840wzNV5IRvbvulWxvHNuvJkGBjC6+9xZqpQPBSlJIJCgMReYJo4mu3cAogDFUsXHU+LXQr44UmvdBgDJjaupt4eXhUgsctze1bES6sHnUyVBAF5Z+8fT/v03P372YtJfLiz+5Deeu0yRkVer2U+fGyGRu95Eeiuc+nNkzNU1GNi+GwoCzUsfggMnB1Rt44TEmERc0lrhxE+TMRetGZA0z6YIQ/OEycLQkFl1mglks/3J69UzOKhtcGrGlmFg4ABz1UvYh1B8eh0OypXeWFvL+sV532HTGfjZwqZDr7c3iXSYQVOIL32/wt/jHcKpqSxAVSzYAAAAAElFTkSuQmCC"/>');
	}

	public function getPanel(): string
	{
		global $txt;

		return $this->getTablePanel([
			'$_GET'     => Debugger::dump($_GET, true),
			'$_POST'    => Debugger::dump($_POST, true),
			'$_REQUEST' => Debugger::dump($_REQUEST, true),
			'$_COOKIE'  => Debugger::dump($_COOKIE, true),
			'$_FILES'   => Debugger::dump($_FILES, true),
			'$_SERVER'  => Debugger::dump($_SERVER, true),
			'$_SESSION' => Debugger::dump($_SESSION, true)
		], $txt['tracy_request_panel']);
	}
}
