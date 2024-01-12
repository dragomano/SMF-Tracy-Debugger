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
 * @version 0.4.3
 */

if (! defined('SMF'))
	die('No direct access...');

if (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'showoperations') !== false)
	return;

require_once __DIR__ . '/Integration.php';
require_once 'phar://' . __DIR__ . '/tracy.phar';

use Bugo\Tracy\Integration;
use Tracy\{Debugger, NativeSession};

global $db_show_debug, $modSettings;

// Debug Mode
$db_show_debug = !empty($modSettings['tracy_debug_mode']);

session_start();
Debugger::setSessionStorage(new NativeSession);

// Configure debugger
Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::$maxLength = (int) ($modSettings['tracy_max_length'] ?? 150);
Debugger::$maxDepth = (int) ($modSettings['tracy_max_depth'] ?? 10);
Debugger::$keysToHide = ['passwd'];
Debugger::$dumpTheme = empty($modSettings['tracy_use_light_theme']) ? 'dark' : 'light';
Debugger::$showLocation = ! empty($modSettings['tracy_show_location']);
Debugger::$strictMode = true;
Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/logs');

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
} else if (strpos($modSettings['integrate_pre_include'], $line) !== 0) {
	$hooks = explode(',', $modSettings['integrate_pre_include']);
	array_unshift($hooks, $line);
	updateSettings(['integrate_pre_include' => implode(',', array_unique($hooks))]);
}

// Run
$app = new Integration();
$app->hooks();
