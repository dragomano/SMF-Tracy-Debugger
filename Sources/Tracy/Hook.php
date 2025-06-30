<?php declare(strict_types=1);

/**
 * @package SMF Tracy Debugger
 * @link https://github.com/dragomano/SMF-Tracy-Debugger
 * @author Bugo <bugo@dragomano.ru>
 * @copyright 2022-2025 Bugo
 * @license https://opensource.org/licenses/BSD-3-Clause BSD
 */

namespace Bugo\Tracy;

use Attribute;
use Bugo\Compat\IntegrationHook;

#[Attribute(Attribute::TARGET_METHOD)]
final class Hook
{
	public function __construct(private string $name) {}

	public function resolve(object $class, string $methodName): void
	{
		$method ??= get_class($class->newInstance()) . '::' . $methodName;

		IntegrationHook::add($this->name, $method, false, $class->getFileName(), true);
	}
}
