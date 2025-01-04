<?php declare(strict_types=1);

/**
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2025 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 */

namespace Bugo\Tracy\Panels;

use Bugo\Compat\Lang;
use Tracy\IBarPanel;

abstract class AbstractPanel implements IBarPanel
{
	public function getSimpleTab(string $title = '', string $description = '', string $image = ''): string
	{
		$output = '<span class="tracy-label"' . (! empty($description) ? ' title="' . $description . '"' : '') . '>';

		if (! empty($image))
			$output .= $image . ' ';

		if (! empty($title))
			$output .= $title;

		$output .= '</span>';

		return $output;
	}

	public function getTablePanel(array $params, string $title = ''): string
	{
		$output = '';

		if (! empty($title))
			$output .= '<h1>' . $title . '</h1>';

		$output .= '<div class="tracy-inner">';
		$output .= '<div class="tracy-inner-container">';
		$output .= '<table>';
		$output .= '<thead>';
		$output .= '<tr>';
		$output .= '<th>' . Lang::$txt['tracy_parameter'] . '</th>';
		$output .= '<th>' . Lang::$txt['tracy_value'] . '</th>';
		$output .= '</tr>';
		$output .= '</thead>';
		$output .= '<tbody>';

		foreach ($params as $key => $value) {
			$output .= '<tr>';
			$output .= '<td><strong>' . $key . '</strong></td>';
			$output .= '<td>' . $value . '</td>';
			$output .= '</tr>';
		}

		$output .= '</tbody>';
		$output .= '</table>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
