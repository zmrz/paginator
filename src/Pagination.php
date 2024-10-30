<?php

namespace Simplex\paginator;

use Simplex\paginator\components\CurrentPaginationItem;
use Simplex\paginator\components\LinkPaginationItem;
use Simplex\paginator\components\PaginationItem;
use Simplex\paginator\components\ParseUrlPagination;
use Simplex\paginator\components\RenderPagination;
use Simplex\paginator\components\SeparatorPaginationItem;
use Simplex\paginator\components\SettingsPagination;

class Pagination
{
	/**
	 * Массив элементов страниц
	 *
	 * @var PaginationItem[]
	 */
	private array $items = [];

	/**
	 * Указатель текущей страницы
	 */
	public int $current = 1;

	/*
	 * Указатель общего кол-ва страниц
	 */
	public int $count = 1;

	/**
	 * Ссылка изначально переданная в класс при создании экземпляра
	 */
	private string $init_link;

	/**
	 * Кол-во элементов, которые были собранны, включая сепараторы
	 */
	private int $element_count = 0;

	/**
	 * Класс настроек
	 */
	public SettingsPagination $settingsComponent;

	/**
	 * Класс парсинга ссылки и разбивки параметров
	 */
	public ParseUrlPagination $urlComponent;

	/**
	 * Класс отрисовки для фронта
	 */
	public RenderPagination $renderComponent;

	/**
	 * Принимает обязательный атрибут $count в виде общего числа страниц.
	 * Необязательный атрибут $url, если не будет передан, то считает с глобальной переменной $_SERVER['REQUEST_URI'].
	 * Необязательный атрибут $settings принимает готовые и настроенный объект класса SettingsPagination.
	 * Если не передан, то будет создан экземпляр класса по-умолчанию.
	 *
	 *
	 * @param int $count
	 * @param string|false $url
	 * @param SettingsPagination|false $settings
	 */
	public function __construct(int $count, string|false $url = false, SettingsPagination|false $settings = false)
	{
		if($count > 0) {
			$this->count = $count;
		}
		$this->init_link = false !== $url ? $url : $_SERVER['REQUEST_URI'];
		$this->settingsComponent = $settings === false ? new SettingsPagination() : $settings;
		$this->urlComponent = $this->makeUrlComponent();

		$this->current = $this->urlComponent->current;

		$this->renderComponent = $this->makeRenderComponent();

		if($this->current > $this->count) {
			$this->current = $this->count;
		}

		$this->element_count = $this->build();
	}

	/**
	 * Генерирует ссылку для элемента на основе настроек
	 *
	 * @param int $num
	 *
	 * @return string
	 */
	public function generateUrlForNum(int $num): string
	{
		$param_copy = $this->urlComponent->params;
		if($num > 1) {
			$param_copy[ $this->settingsComponent->param_name ] = $num;
		}

		return $this->urlComponent->path . (empty($param_copy) ? '' : ('?' . http_build_query($param_copy)));
	}

	/**
	 * Выдает готовую строку html кода
	 *
	 * @return string
	 */
	public function getRenderString(): string
	{
		return $this->renderComponent->render($this);
	}

	/**
	 * Выдает итератор элементов по порядку
	 *
	 * @return \Iterator
	 */
	public function iterate(): \Iterator
	{
		foreach ($this->items as $k => $paginationItem) {
			yield $k => $paginationItem;
		}
	}

	public function getInitLink(): string
	{
		return $this->init_link;
	}

	public function getParamName(): string
	{
		return $this->settingsComponent->param_name;
	}

	public function getElementCount(): int
	{
		return $this->element_count;
	}



	/**
	 * Производит сборку элементов, на основе настроек, в свойство $item
	 *
	 * @return int
	 */
	private function build(): int
	{
		$this->addActiveItem(1);

		$counter = 1;
		$show_middle_count = $this->settingsComponent->show_count_items - 2;

		if($this->count < 2)
			return $counter;

		if($this->settingsComponent->show_separator) {
			if($this->current > ($show_middle_count)) {
				$this->addSeparatorItem();
				$counter++;
			}
		}

		$start = $this->current - (floor($show_middle_count / 2));
		if($start < 2) {
			$start = 2;
		}

		$do_minus = $this->current - ($this->count - $show_middle_count);
		if($do_minus > 0) {
			$start = $this->current - $do_minus;
		}

		for (
			$pos = 1;
			$pos <= $show_middle_count;
			$pos++, $start++, $counter++
		) {
			if($counter > ($show_middle_count + 1)) {
				break;
			}
			if($start > ($this->count - 1)) {
				break;
			}
			$this->addActiveItem($start);
		}

		if($this->settingsComponent->show_separator) {
			if($this->current <= ($this->count - ($show_middle_count))) {
				$this->addSeparatorItem();
				$counter++;
			}
		}

		$this->addActiveItem($this->count);
		$counter++;

		return $counter;
	}

	private function makeUrlComponent(): ParseUrlPagination
	{
		return new ParseUrlPagination($this);
	}

	private function makeRenderComponent(): RenderPagination
	{
		return new RenderPagination();
	}

	private function addActiveItem($num): void
	{
		if($this->current == $num) {
			$this->addCurrentItem($num);
		} else {
			$this->addLinkItem($num);
		}
	}

	private function addLinkItem($num): void
	{
		$this->items[] = $this->makeLinkItem($num);
	}

	private function addCurrentItem($num): void
	{
		$this->items[] = $this->makeCurrentItem($num);
	}

	private function addSeparatorItem(): void
	{
		$this->items[] = $this->makeSeparatorItem();
	}

	private function makeLinkItem($num): LinkPaginationItem
	{
		return new LinkPaginationItem($this, $num);
	}

	private function makeCurrentItem($num): CurrentPaginationItem
	{
		return new CurrentPaginationItem($this, $num);
	}

	private function makeSeparatorItem(): SeparatorPaginationItem
	{
		return new SeparatorPaginationItem($this);
	}
}