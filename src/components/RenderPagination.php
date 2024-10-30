<?php

namespace Simplex\paginator\components;

use Simplex\paginator\Pagination;

class RenderPagination
{

	public function __construct()
	{

	}

	public function render(Pagination $pagination): string
	{
		$str = '<'.$pagination->settingsComponent->wrap_tag.' class="'.$pagination->settingsComponent->class_wrap.'">';
			foreach ($pagination->iterate() as $paginationItem) {
				$str .= '<'.$pagination->settingsComponent->wrap_item.' class="'.$pagination->settingsComponent->class_item.'">';

				switch ($paginationItem->getType()) {
					case $paginationItem::CURRENT_TYPE:
						$str .= '<'.$pagination->settingsComponent->wrap_current_tag.' class="'.$pagination->settingsComponent->class_current.'">';
						$str .= $paginationItem->getText();
						$str .= '</'.$pagination->settingsComponent->wrap_current_tag.'>';
						break;
					case $paginationItem::SEPARATOR_TYPE:
						$str .= '<'.$pagination->settingsComponent->wrap_separator_tag.' class="'.$pagination->settingsComponent->class_separator.'">';
						$str .= $paginationItem->getText();
						$str .= '</'.$pagination->settingsComponent->wrap_separator_tag.'>';
						break;
					default:
						if($pagination->settingsComponent->wrap_link_append_tag) {
							$str .= '<'.$pagination->settingsComponent->wrap_link_append_tag.' class="'.$pagination->settingsComponent->class_link_append.'">';
						}
						$str .= '<a href="'.$paginationItem->getLink().'" class="'.$pagination->settingsComponent->class_link.'">';
						$str .= $paginationItem->getText();
						$str .= '</a>';
						if($pagination->settingsComponent->wrap_link_append_tag) {
							$str .= '</'.$pagination->settingsComponent->wrap_link_append_tag.'>';
						}
						break;
				}

				$str .= '</'.$pagination->settingsComponent->wrap_item.'>';
			}

		$str .= '</'.$pagination->settingsComponent->wrap_tag.'>';

		return $str;
	}
}