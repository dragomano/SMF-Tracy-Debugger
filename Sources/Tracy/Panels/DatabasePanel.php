<?php declare(strict_types=1);

/**
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2025 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 */

namespace Bugo\Tracy\Panels;

use Bugo\Compat\Config;
use Bugo\Compat\Db;
use Bugo\Compat\IntegrationHook;
use Bugo\Compat\Lang;
use Bugo\Compat\Utils;
use Tracy\Debugger;

class DatabasePanel extends AbstractPanel
{
	public function getTab(): string
	{
		Lang::load('Admin');

		return $this->getSimpleTab(
			Lang::$txt['maintain_sub_database'],
			'',
			'<svg viewBox="0 0 2048 2048">' . implode(' ', [
				'<path fill="#aaa" d="M1024 896q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385',
				'34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0 768q237 0 443-43t325-127v170q0',
				'69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0-384q237',
				'0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325',
				'127t443 43zm0-1152q208 0 385 34.5t280 93.5 103 128v128q0 69-103 128t-280 93.5-385',
				'34.5-385-34.5-280-93.5-103-128v-128q0-69 103-128t280-93.5 385-34.5z"></path>',
			]) . '</svg>'
		);
	}

	public function getPanel(): string
	{
		$tasks = $this->getBackgroundTasks();

		$extends = [
			Lang::$txt['tracy_database_background_tasks'] => $tasks ? Debugger::dump($tasks, true) : Lang::$txt['no'],
			Lang::$txt['tracy_database_num_queries']      => Db::$count,
		];

		IntegrationHook::call('integrate_tracy_database_panel', [&$extends]);

		$params = [
			Lang::$txt['tracy_database_type']     => Utils::$smcFunc['db_title'],
			Lang::$txt['tracy_database_version']  => Db::$db->get_version(),
			Lang::$txt['tracy_database_server']   => Config::$db_server,
			Lang::$txt['tracy_database_name']     => Config::$db_name,
			Lang::$txt['tracy_database_user']     => Config::$db_user,
			Lang::$txt['tracy_database_password'] => Config::$db_passwd,
			...$extends,
		];

		if (! empty(Config::$db_show_debug) && ! empty(Db::$cache)) {
			$queries = '';

			foreach (Db::$cache as $data) {
				if (isset($data['f']))
					$data['f'] = preg_replace(
						'~^' . preg_quote(Config::$boarddir, '~') . '~', '...', $data['f']
					);

				$queries .= '<strong>' . nl2br(
					str_replace(
						"\t",
						'',
						Utils::htmlspecialchars(ltrim($data['q'], "\n\r"))
					)
				) . '</strong><br>';

				if (! empty($data['f']) && ! empty($data['l']))
					$queries .= sprintf(Lang::$txt['debug_query_in_line'], $data['f'], $data['l']);

				$queries .= '<br><br>';
			}

			$params[Lang::$txt['tracy_database_queries']] = $queries;
		}

		return $this->getTablePanel($params, Lang::$txt['tracy_database_panel']);
	}

	private function getBackgroundTasks(): array
	{
		$result = Db::$db->query('', /** @lang text */ 'SELECT * FROM {db_prefix}background_tasks');
		$tasks  = Db::$db->fetch_all($result);

		Db::$db->free_result($result);

		return $tasks;
	}
}
