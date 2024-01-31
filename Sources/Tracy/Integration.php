<?php declare(strict_types = 1);

/**
 * Integration.php
 *
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2024 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 *
 * @version 0.5
 */

namespace Bugo\Tracy;

use Bugo\Tracy\Attributes\Hook;
use Bugo\Tracy\Panels\{BasePanel, DatabasePanel, PortalPanel};
use Bugo\Tracy\Panels\{RequestPanel, RoutePanel, UserPanel};
use ReflectionClass;
use Tracy\{Debugger, IBarPanel};

if (! defined('SMF'))
	die('No direct access...');

final class Integration
{
	private array $panels = [
		BasePanel::class,
		PortalPanel::class,
		RoutePanel::class,
		RequestPanel::class,
		DatabasePanel::class,
		UserPanel::class,
	];

	public function __invoke(): void
	{
		$reflectionClass = new ReflectionClass(self::class);
		foreach ($reflectionClass->getMethods() as $method) {
			$attributes = $method->getAttributes(Hook::class);

			foreach ($attributes as $attribute) {
				$attribute->newInstance();
			}
		}
	}

	#[Hook('integrate_pre_css_output', self::class . '::preCssOutput#', __FILE__)]
	public function preCssOutput(): void
	{
		if (SMF === 'BACKGROUND')
			return;

		Debugger::renderLoader();
	}

	#[Hook('integrate_load_theme', self::class . '::loadTheme#', __FILE__)]
	public function loadTheme(): void
	{
		global $user_info;

		if ($user_info['is_guest'])
			return;

		loadLanguage('Tracy/');

		addInlineCss('
		pre.tracy-dump {
			max-height: 300px;
			overflow: auto;
		}');

		call_integration_hook('integrate_tracy_panels', [&$this->panels]);

		foreach ($this->panels as $className) {
			$panel = new $className;
			if ($panel instanceof IBarPanel) {
				Debugger::getBar()->addPanel(new $className);
			}
		}
	}

	#[Hook('integrate_admin_areas', self::class . '::adminAreas#', __FILE__)]
	public function adminAreas(array &$admin_areas): void
	{
		global $txt;

		$admin_areas['config']['areas']['modsettings']['subsections']['tracy_debugger'] = [$txt['tracy_title']];
	}

	#[Hook('integrate_modify_modifications', self::class . '::modifyModifications#', __FILE__)]
	public function modifyModifications(array &$subActions): void
	{
		$subActions['tracy_debugger'] = __CLASS__ . '::settings#';
	}

	/**
	* @return void|array
	*/
	public function settings(bool $return_config = false)
	{
		global $context, $txt, $scripturl;

		$context['page_title']     = $txt['tracy_title'];
		$context['settings_title'] = $txt['settings'];

		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=tracy_debugger';

		$this->addDefaultValues();

		$config_vars = [
			['int', 'tracy_max_length'],
			['int', 'tracy_max_depth'],
			['check', 'tracy_use_light_theme'],
			['check', 'tracy_show_location'],
			['check', 'tracy_debug_mode', 'help' => 'tracy_debug_mode_help']
		];

		if ($return_config)
			return $config_vars;

		$menu = $context['admin_menu_name'];
		$tabs = $context[$menu]['tab_data'];
		$tabs['description'] = $txt['tracy_description'];
		$context[$menu]['tab_data'] = $tabs;

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

	private function addDefaultValues(): void
	{
		global $modSettings;

		$values = [
			'tracy_max_length' => 150,
			'tracy_max_depth'  => 10,
		];

		$settings = [];
		foreach ($values as $key => $value) {
			if (! isset($modSettings[$key])) {
				$settings[$key] = $value;
			}
		}

		updateSettings($settings);
	}
}
