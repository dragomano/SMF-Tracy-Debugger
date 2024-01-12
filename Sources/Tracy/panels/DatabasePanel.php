<?php

declare(strict_types = 1);

/**
 * DatabasePanel.php
 *
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2024 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 *
 * @version 0.3.1
 */

namespace Bugo\Tracy\Panels;

use Tracy\Debugger;

class DatabasePanel extends AbstractPanel
{
	public function getTab(): string
	{
		return $this->getSimpleTab('Database', '', '<svg viewBox="0 0 2048 2048"><path fill="#aaa" d="M1024 896q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0 768q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0-384q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0-1152q208 0 385 34.5t280 93.5 103 128v128q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-128q0-69 103-128t280-93.5 385-34.5z"></path>
		</svg>');
	}

	public function getPanel(): string
	{
		global $txt, $smcFunc, $db_server, $db_name, $db_user, $db_passwd, $db_count, $db_show_debug, $db_cache, $boarddir;

		db_extend();

		$params = [
			$txt['tracy_database_type']        => $smcFunc['db_title'],
			$txt['tracy_database_version']     => $smcFunc['db_get_version'](),
			$txt['tracy_database_server']      => $db_server,
			$txt['tracy_database_name']        => $db_name,
			$txt['tracy_database_user']        => $db_user,
			$txt['tracy_database_password']    => $db_passwd,
			$txt['tracy_database_num_queries'] => $db_count
		];

		if (!empty($db_show_debug) && !empty($db_cache)) {
			$queries = '';

			foreach ($db_cache as $q => $query_data) {
				if (isset($query_data['f']))
					$query_data['f'] = preg_replace('~^' . preg_quote($boarddir, '~') . '~', '...', $query_data['f']);

				$queries .= '<strong>' . nl2br(str_replace("\t", '', $smcFunc['htmlspecialchars'](ltrim($query_data['q'], "\n\r")))) . '</strong><br>';

				if (!empty($query_data['f']) && !empty($query_data['l']))
					$queries .= sprintf($txt['debug_query_in_line'], $query_data['f'], $query_data['l']);

				$queries .= '<br><br>';
			}

			$params[$txt['tracy_database_queries']] = $queries;
		}

		return $this->getTablePanel($params, $txt['tracy_database_panel']);
	}
}
