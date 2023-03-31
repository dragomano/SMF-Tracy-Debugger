<?php

declare(strict_types = 1);

/**
 * Integration.php
 *
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2023 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 *
 * @version 0.4.1
 */

namespace Bugo\Tracy;

use Tracy\{Debugger, IBarPanel};

use function add_integration_function;
use function addInlineCss;
use function loadLanguage;
use function updateSettings;
use function checkSession;
use function saveDBSettings;
use function clean_cache;
use function redirectexit;
use function prepareDBSettingContext;

if (! defined('SMF'))
	die('No direct access...');

final class Integration
{
	public function hooks()
	{
		add_integration_function('integrate_pre_css_output', __CLASS__ . '::preCssOutput#', false, __FILE__);
		add_integration_function('integrate_load_theme', __CLASS__ . '::loadTheme#', false, __FILE__);
		add_integration_function('integrate_admin_areas', __CLASS__ . '::adminAreas#', false, __FILE__);
		add_integration_function('integrate_admin_search', __CLASS__ . '::adminSearch#', false, __FILE__);
		add_integration_function('integrate_modify_modifications', __CLASS__ . '::modifyModifications#', false, __FILE__);
	}

	public function preCssOutput()
	{
		if (SMF === 'BACKGROUND')
			return;

		Debugger::renderLoader();
	}

	public function loadTheme()
	{
		global $user_info;

		loadLanguage('Tracy/');

		if ($user_info['is_guest'])
			return;

		addInlineCss('
		pre.tracy-dump {
			max-height: 300px;
			overflow: auto;
		}');

		$panels = [
			Panels\BasePanel::class,
			Panels\RoutePanel::class,
			Panels\RequestPanel::class,
			Panels\DatabasePanel::class,
			Panels\UserPanel::class,
		];

		require_once __DIR__ . '/panels/AbstractPanel.php';

		foreach ($panels as $className) {
			require_once __DIR__ . '/panels/' . substr(strrchr($className, "\\"), 1) . '.php';

			$panel = new $className;
			if ($panel instanceof IBarPanel) {
				Debugger::getBar()->addPanel(new $className);
			}
		}
	}

	public function adminAreas(array &$admin_areas)
	{
		global $txt;

		$admin_areas['config']['areas']['modsettings']['subsections']['tracy_debugger'] = [$txt['tracy_title']];
	}

	public function adminSearch(array &$language_files, array &$include_files, array &$settings_search)
	{
		$settings_search[] = [[$this, 'settings'], 'area=modsettings;sa=tracy_debugger'];
	}

	public function modifyModifications(array &$subActions)
	{
		$subActions['tracy_debugger'] = [$this, 'settings'];
	}

	/**
	* @return void|array
	*/
	public function settings(bool $return_config = false)
	{
		global $context, $txt, $scripturl, $modSettings;

		$context['page_title']     = $txt['tracy_title'];
		$context['settings_title'] = $txt['settings'];

		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=tracy_debugger';

		$addSettings = [];
		if (! isset($modSettings['tracy_max_length']))
			$addSettings['tracy_max_length'] = 150;
		if (! isset($modSettings['tracy_max_depth']))
			$addSettings['tracy_max_depth'] = 10;
		if ($addSettings)
			updateSettings($addSettings);

		$config_vars = [
			['int', 'tracy_max_length'],
			['int', 'tracy_max_depth'],
			['check', 'tracy_use_light_theme'],
			['check', 'tracy_show_location'],
			['check', 'tracy_debug_mode', 'help' => $txt['tracy_debug_mode_help']]
		];

		if ($return_config)
			return $config_vars;

		$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['tracy_description'];

		// Saving?
		if (isset($_GET['save'])) {
			checkSession();

			$save_vars = $config_vars;
			saveDBSettings($save_vars);

			clean_cache();
			redirectexit('action=admin;area=modsettings;sa=tracy_debugger');
		}

		prepareDBSettingContext($config_vars);
	}
}
