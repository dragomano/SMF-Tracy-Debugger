<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Bugo\Tracy\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Hook
{
	public function __construct(string $name, string $method, string $file)
	{
		add_integration_function($name, $method, false, $file);
	}
}
