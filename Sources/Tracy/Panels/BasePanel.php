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

namespace Bugo\Tracy\Panels;

use Bugo\Compat\Config;
use Bugo\Compat\Lang;
use Bugo\Compat\Theme;
use Bugo\Compat\Utils;
use Tracy\Debugger;

class BasePanel extends AbstractPanel
{
	public function getTab(): string
	{
		return $this->getSimpleTab(
			SMF_FULL_VERSION,
			'',
			'<img alt="" src="data:image/png;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAQAQAAAAAAAAAAAAAAAAAAAAAAABBNy4MPjUrIj41KyI+NSsiPjQrIj00KiI9NCoiPTQqIj00KiI9MyoiPTMqIj0zKiI9MyoiPTMqIj0zKiI/NSwOPTQqFnVmWfB/aln/fmhX/3xmVf97ZVP/eWNR/3dgTv92X0z/dF1K/3NbSP9xWkb/cVlG/3FZRv9uXU72PDIoIAAAAABhVkt0iHBd/4JoVP+AZlH/fmNO/3xhS/96Xkn/eFxG/3ZZQ/90Vj//cVQ8/29ROv9zVj//YFJGhAAAAAAAAAAAQTguCHlpW9yGbVn/hGpW/4JoU/+AZVD/fmJO/3xgS/95Xkj/d1tF/3VYQv9zVj//blpK5j40Kg4AAAAAAAAAAAAAAABXTURUi3Vk/4hvXP+GbVn/hGpW/4FnU/9/ZVD/fmJN/3tgSv95XUf/e2FN/1lNQ2IAAAAAAAAAAAAAAAAAAAAAVUtCAndpXcCMdGH/inFe/4hvW/+FbFj/g2lV/4FnUv9/ZE//fWFM/3BgUc5FOzIEAAAAAAAAAABxZlyEQTguMlhORgJKQTc0i3hp/412Y/+Lc2D/iXBd/4duWv+Fa1f/g2lU/4FrWv9PRTxCAAAAAAAAAAAAAAAAkoJ0/5qJe/+Cc2fedGlejmhcUsySfGr/j3hm/411Y/+LcmD/iXBd/4duW/9wYlawAAAAAAAAAAAAAAAAAAAAAD41Kx5mXFJwf3JmwI18b/ybiXv/moZ2/5R9bP+Dc2XogG9h5I52ZP+FcmP4QjgvJgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/NiwYX1ZMZntuY8KVhHb/PzUsLkA2LR6KeWv/aFxStEQ6MAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABBOC4Khnlu4nBlW55tYliUkoFz/5iGd/+BcGPwdmldpFFIPlJCOC8KAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAF1TSlycinv/nIl6/19VS3ZVTEJYeGtfqoJxY/SWg3T/gW9h9HZoXKwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABPRjwCgXRoyIN2atZFPDIGAAAAAAAAAABDOTAKUUc+UHZpXaJ/bmDwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAE9GPTxWTURKAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAARTsyCAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAAQAAgAEAAMADAADAAwAAAAcAAAAPAAAADwAA4A8AAPgBAAD8AAAA/DAAAP5+AAD//wAA//8AAA=="/>'
		);
	}

	public function getPanel(): string
	{
		return $this->getTablePanel([
			'$mbname'      => Config::$mbname,
			'$language'    => Config::$language,
			'$boarddir'    => Config::$boarddir,
			'$boardurl'    => Config::$boardurl,
			'$scripturl'   => Config::$scripturl,
			'$cachedir'    => Config::$cachedir,
			'$sourcedir'   => Config::$sourcedir,
			'$smcFunc'     => Debugger::dump(Utils::$smcFunc, true),
			'$context'     => Debugger::dump(Utils::$context, true),
			'$modSettings' => Debugger::dump(Config::$modSettings, true),
			'$txt'         => Debugger::dump(Lang::$txt, true),
			'$settings'    => Debugger::dump(Theme::$current->settings, true),
		], Lang::$txt['tracy_base_panel']);
	}
}
