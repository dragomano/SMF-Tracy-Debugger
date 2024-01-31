<?php

declare(strict_types = 1);

/**
 * app.php
 *
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2024 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 *
 * @version 0.5
 */

if (! defined('SMF'))
	die('No direct access...');

if (isset($_REQUEST['action']) && str_contains($_REQUEST['action'], 'showoperations'))
	return;

require_once __DIR__ . '/vendor/autoload.php';

use Bugo\Tracy\Integration;
use Tracy\Debugger;

global $db_show_debug, $modSettings;

// Debug Mode
$db_show_debug = ! empty($modSettings['tracy_debug_mode']);

// Configure debugger
Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::$maxLength = (int) ($modSettings['tracy_max_length'] ?? 150);
Debugger::$maxDepth = (int) ($modSettings['tracy_max_depth'] ?? 10);
Debugger::$keysToHide = ['passwd'];
Debugger::$dumpTheme = empty($modSettings['tracy_use_light_theme']) ? 'dark' : 'light';
Debugger::$showLocation = ! empty($modSettings['tracy_show_location']);
Debugger::$strictMode = true;
Debugger::enable();

// Make alias for dumpe function
if (! function_exists('dd')) {
	function dd(...$var) {
		dumpe(...$var);
	}
}

// Debugger should always load first
$line = '$sourcedir/Tracy/app.php';
if (empty($modSettings['integrate_pre_include'])) {
	updateSettings(['integrate_pre_include' => $line]);
} else if (! str_starts_with($modSettings['integrate_pre_include'], $line)) {
	$hooks = explode(',', $modSettings['integrate_pre_include']);
	array_unshift($hooks, $line);
	updateSettings(['integrate_pre_include' => implode(',', array_unique($hooks))]);
}

// Run
(new Integration())();
