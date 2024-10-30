<?php

namespace Simplex\paginator\components;

use Simplex\paginator\Pagination;

abstract class PaginationItem
{
	const LINK_TYPE = 0, CURRENT_TYPE = 1, SEPARATOR_TYPE = 2;
	const SEPARATOR_TEXT = '...';

	protected int $type;
	protected int $num;

	public string $link = '/';
	public string $text = '';

	public function __construct(Pagination $pagination, int|false $num = false)
	{
		$this->num = $num;
		if(false !== $num) {
			$this->generateLink($pagination);
		}
		$this->make();
	}

	private function generateLink(Pagination $pagination): void
	{
		$this->link = $pagination->generateUrlForNum($this->num);
	}

	abstract public function make();

	public function getType(): int
	{
		return $this->type;
	}

	public function getLink(): string
	{
		return $this->link;
	}

	public function getText(): string
	{
		return $this->text;
	}
}