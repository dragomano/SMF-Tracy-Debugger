<?php declare(strict_types=1);

/**
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2025 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 */

namespace Bugo\Tracy\Panels;

use Bugo\Compat\IntegrationHook;
use Bugo\Compat\Lang;
use Bugo\Compat\User;
use Tracy\Debugger;

class UserPanel extends AbstractPanel
{
	public function getTab(): string
	{
		return $this->getSimpleTab(
			User::$me->name,
			'',
			implode('', [
				'<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA',
				'/wD/AP+gvaeTAAAB7UlEQVQ4jaWRz0tUURTHP/e+NzM+ndHBXk26yBKEQBcG/VgJuglrExVBLYQg8G+QoBBcu2rb0oJ0EVQ',
				'EQiBEq3CRhYEIoUZo2pA2OuOb9+45bWox45RQZ3nv53z48j3wn2P+9DE3jq9r7Ves81qdsBurvhme+rZez/mNlufvHh+qfJ',
				'Xn4muLGrAJmlFvFHh4aILXo2EH2E9NOb+pspOgAqnAo7Thor1dOXV9tjbFgQTxvunv7Gt2Jy90Yv0MAJJELL36kqzNl/uBG',
				'oGtF6hzq2F3W+ClAvx0Fj+dxUsFFE7nAsFbrecPCLovtQ8F+bTFWIz1MNYDY8keC2zvrezQoQJp1hmIRZIqLq7g4gqSVDEmF',
				'pPVmXq+4Rl3XvbM+pncRes3AYokEUlUmm27vDxczzY8o/VkJIpk3a8WLUCsgaTRkYbsgZfxOb/j8cRg6cS9yuY2bG7Dbtf9c',
				'tfU2CA3pr2/ClrvTJ/PrWwsWfTJ++hcS1iYJCxMsrB3Jou66SNecSm8+eBsww7yt5/mne4vGyQ0KvR2tvKsrwTGcO1DhsWVLR',
				'CHUdlylHu+z4zt1HTgqAwYNDQqGHF8/FxkseBQVRZXfvxexqgcTZMaAF7UCFLGvBPnHhmVNAiocPWtYFGMCCD8klc9x0KjQv9',
				'pfgKyPNNlWLmCGQAAAABJRU5ErkJggg=="/>'
			])
		);
	}

	public function getPanel(): string
	{
		if (empty(User::$loaded[User::$me->id])) {
			User::load(User::$me->id);
		}

		$extends = [
			'$user_info' => Debugger::dump(User::$loaded[User::$me->id]->format(), true),
		];

		IntegrationHook::call('integrate_tracy_user_panel', [&$extends]);

		return $this->getTablePanel([
			'ID'                              => User::$me->id,
			Lang::getTxt('username')          => User::$me->username,
			Lang::getTxt('email')             => User::$me->email,
			Lang::getTxt('name')              => User::$me->name,
			Lang::getTxt('position')          => Debugger::dump(User::$me->groups, true),
			Lang::getTxt('tracy_user_avatar') => Debugger::dump(User::$me->avatar, true),
			...$extends,
		], Lang::getTxt('tracy_user_panel'));
	}
}
