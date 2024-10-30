<?php

namespace Simplex\paginator\components;

class SeparatorPaginationItem extends PaginationItem
{
	public function make(): void
	{
		$this->type = self::SEPARATOR_TYPE;
		$this->text = self::SEPARATOR_TEXT;
	}
}