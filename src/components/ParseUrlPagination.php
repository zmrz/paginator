<?php

namespace Simplex\paginator\components;

use Simplex\paginator\Pagination;

class ParseUrlPagination
{
	public string $path;
	public array $params = [];
	public int $current = 1;

	public function __construct(Pagination $pagination)
	{
		$parse = parse_url($pagination->getInitLink());

		$this->path = $parse['path'];

		if(!empty($parse['query'])) {
			parse_str($parse['query'], $this->params);

			if(isset($this->params[$pagination->getParamName()])) {
				$this->current = (int) $this->params[$pagination->getParamName()];
				unset($this->params[$pagination->getParamName()]);
			}
		}
	}
}