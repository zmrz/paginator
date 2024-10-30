<?php

namespace Simplex\paginator\components;

class LinkPaginationItem extends PaginationItem
{
	public function make(): void
	{
		$this->type = self::LINK_TYPE;
		$this->text = $this->num;
	}
}