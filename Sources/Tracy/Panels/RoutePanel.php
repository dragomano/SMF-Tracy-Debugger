<?php declare(strict_types=1);

/**
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2024 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 *
 * @version 0.6.4
 */

namespace Bugo\Tracy\Panels;

use Bugo\Compat\Lang;
use Bugo\Compat\Utils;
use Tracy\Debugger;

class RoutePanel extends AbstractPanel
{
	public function getTab(): string
	{
		return $this->getSimpleTab(
			'Route: ' . ($_SERVER['QUERY_STRING'] ?: '/'),
			'',
			'
				<svg viewBox="0 0 2048 2048">
					<path fill="#d86b01" d="m1559.7 1024c0 17-6 32-19 45l-670 694.48c-13 13-28 19-45 19s-32-6-45-19-19-28-19-45v-306.48h-438.52c-17 0-32-6-45-19s-19-28-19-45v-642c0-17 6-32 19-45s28-19 45-19h438.52v-309.41c0-17 6-32 19-45s28-19 45-19 32 6 45 19l670 691.41c13 13 19 28 19 45z"></path>	<path d="m1914.7 1505c0 79-31 147-87 204-56 56-124 85-203 85h-320c-9 0-16-3-22-9-14-23-21-90 3-110 5-4 12-6 21-6h320c44 0 82-16 113-47s47-69 47-113v-962c0-44-16-82-47-113s-69-47-113-47h-312c-11 0-21-3-30-9-15-25-21-90 3-110 5-4 12-6 21-6h320c79 0 147 28 204 85 56 56 82 124 82 204-9 272 9 649 0 954z" fill-opacity=".5" fill="#d86b01"></path>
				</svg>'
		);
	}

	public function getPanel(): string
	{
		$params = [
			Lang::$txt['tracy_route_title']           => Utils::$context['page_title'] ?? '',
			Lang::$txt['tracy_route_current_url']     => $_SERVER['REQUEST_URL'] ?? '',
			Lang::$txt['tracy_route_canonical_url']   => Utils::$context['canonical_url'] ?? '',
			Lang::$txt['tracy_route_linktree']        => Debugger::dump(Utils::$context['linktree'], true),
			Lang::$txt['tracy_route_sub_template']    => 'template_' . (Utils::$context['sub_template'] ?? 'main'),
			Lang::$txt['tracy_route_template_layers'] => Debugger::dump(Utils::$context['template_layers'], true)
		];

		if (isset(Utils::$context['debug']))
			$params['templates'] = Debugger::dump(Utils::$context['debug']['templates'], true);

		return $this->getTablePanel($params, Lang::$txt['tracy_route_panel']);
	}
}
