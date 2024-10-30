<?php

namespace Simplex\paginator\components;

class CurrentPaginationItem extends PaginationItem
{
	public function make(): void
	{
		$this->type = self::CURRENT_TYPE;
		$this->text = $this->num;
	}
}