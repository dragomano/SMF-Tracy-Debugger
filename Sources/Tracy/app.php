<?php declare(strict_types=1);

/**
 * app.php
 *
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2025 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 */

use Bugo\Compat\Config;
use Bugo\Tracy\Integration;
use Tracy\Debugger;

if (! defined('SMF'))
	die('No direct access...');

if (SMF === 'BACKGROUND')
	return;

if (isset($_REQUEST['action']) && str_contains($_REQUEST['action'], 'showoperations'))
	return;

require_once __DIR__ . '/vendor/autoload.php';

// Configure debugger
Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::$maxLength = (int) (Config::$modSettings['tracy_max_length'] ?? 150);
Debugger::$maxDepth = (int) (Config::$modSettings['tracy_max_depth'] ?? 10);
Debugger::$keysToHide = ['passwd'];
Debugger::$dumpTheme = empty(Config::$modSettings['tracy_use_light_theme']) ? 'dark' : 'light';
Debugger::$showLocation = ! empty(Config::$modSettings['tracy_show_location']);
Debugger::$strictMode = true;
Debugger::enable();

// Make alias for dumpe function
if (! function_exists('dd')) {
	function dd(...$var): void
	{
		dumpe(...$var);
	}
}

// Debugger should always load first
$line = '$sourcedir/Tracy/app.php';
if (empty(Config::$modSettings['integrate_pre_include'])) {
	Config::updateModSettings(['integrate_pre_include' => $line]);
} else if (! str_starts_with(Config::$modSettings['integrate_pre_include'], $line)) {
	$hooks = explode(',', Config::$modSettings['integrate_pre_include']);
	array_unshift($hooks, $line);
	Config::updateModSettings(['integrate_pre_include' => implode(',', array_unique($hooks))]);
}

// Run
(new Integration())();
