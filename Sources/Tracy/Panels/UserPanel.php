<?php declare(strict_types = 1);

/**
 * UserPanel.php
 *
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2024 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 *
 * @version 0.4.4
 */

namespace Bugo\Tracy\Panels;

use Tracy\Debugger;

class UserPanel extends AbstractPanel
{
	public function getTab(): string
	{
		global $user_info;

		return $this->getSimpleTab($user_info['name'], '', '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAB7UlEQVQ4jaWRz0tUURTHP/e+NzM+ndHBXk26yBKEQBcG/VgJuglrExVBLYQg8G+QoBBcu2rb0oJ0EVQEQiBEq3CRhYEIoUZo2pA2OuOb9+45bWox45RQZ3nv53z48j3wn2P+9DE3jq9r7Ves81qdsBurvhme+rZez/mNlufvHh+qfJXn4muLGrAJmlFvFHh4aILXo2EH2E9NOb+pspOgAqnAo7Thor1dOXV9tjbFgQTxvunv7Gt2Jy90Yv0MAJJELL36kqzNl/uBGoGtF6hzq2F3W+ClAvx0Fj+dxUsFFE7nAsFbrecPCLovtQ8F+bTFWIz1MNYDY8keC2zvrezQoQJp1hmIRZIqLq7g4gqSVDEmFpPVmXq+4Rl3XvbM+pncRes3AYokEUlUmm27vDxczzY8o/VkJIpk3a8WLUCsgaTRkYbsgZfxOb/j8cRg6cS9yuY2bG7Dbtf9ctfU2CA3pr2/ClrvTJ/PrWwsWfTJ++hcS1iYJCxMsrB3Jou66SNecSm8+eBsww7yt5/mne4vGyQ0KvR2tvKsrwTGcO1DhsWVLRCHUdlylHu+z4zt1HTgqAwYNDQqGHF8/FxkseBQVRZXfvxexqgcTZMaAF7UCFLGvBPnHhmVNAiocPWtYFGMCCD8klc9x0KjQv9pfgKyPNNlWLmCGQAAAABJRU5ErkJggg=="/>');
	}

	public function getPanel(): string
	{
		global $user_info, $txt;

		return $this->getTablePanel([
			'ID'                      => $user_info['id'],
			$txt['username']          => $user_info['username'],
			$txt['email']             => $user_info['email'],
			$txt['name']              => $user_info['name'],
			$txt['position']          => Debugger::dump($user_info['groups'], true),
			$txt['tracy_user_avatar'] => Debugger::dump($user_info['avatar'], true),
			'$user_info'              => Debugger::dump($user_info, true),
		], $txt['tracy_user_panel']);
	}
}
