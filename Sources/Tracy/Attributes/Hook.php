<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2025 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 */

namespace Bugo\Tracy\Attributes;

use Attribute;
use Bugo\Compat\IntegrationHook;

#[Attribute(Attribute::TARGET_METHOD)]
final class Hook
{
	public function __construct(string $name, string $method, string $file)
	{
		IntegrationHook::add($name, $method, false, $file);
	}
}
