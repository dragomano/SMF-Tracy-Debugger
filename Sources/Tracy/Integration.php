<?php declare(strict_types = 1);

/**
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2024 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 *
 * @version 0.6.4
 */

namespace Bugo\Tracy;

use Bugo\Compat\{Actions\ACP, CacheApi, Config, IntegrationHook};
use Bugo\Compat\{Lang, Menu, Theme, User, Utils};
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
		if (User::$info['is_guest'])
			return;

		Lang::load('Tracy/');

		Theme::addInlineCss('
		pre.tracy-dump {
			max-height: 300px;
			overflow: auto;
		}');

		IntegrationHook::call('integrate_tracy_panels', [&$this->panels]);

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
		$admin_areas['config']['areas']['modsettings']['subsections']['tracy_debugger'] = [Lang::$txt['tracy_title']];
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
		Utils::$context['page_title'] = Lang::$txt['tracy_title'];
		Utils::$context['settings_title'] = Lang::$txt['settings'];
		Utils::$context['post_url'] = Config::$scripturl . '?action=admin;area=modsettings;save;sa=tracy_debugger';

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

		if (empty(Menu::$loaded))
			new Menu();

		Menu::$loaded['admin']->tab_data['description'] = Lang::$txt['tracy_description'];

		// Saving?
		if (isset($_GET['save'])) {
			User::$me->checkSession();

			$save_vars = $config_vars;
			ACP::saveDBSettings($save_vars);

			Config::updateSettingsFile(['db_show_debug' => isset($_POST['tracy_debug_mode'])]);

			CacheApi::clean();
			Utils::redirectexit('action=admin;area=modsettings;sa=tracy_debugger');
		}

		ACP::prepareDBSettingContext($config_vars);
	}

	private function addDefaultValues(): void
	{
		$values = [
			'tracy_max_length' => 150,
			'tracy_max_depth'  => 10,
		];

		$settings = [];
		foreach ($values as $key => $value) {
			if (! isset(Config::$modSettings[$key])) {
				$settings[$key] = $value;
			}
		}

		Config::updateModSettings($settings);
	}
}
