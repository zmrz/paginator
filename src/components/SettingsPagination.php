<?php

namespace Simplex\paginator\components;

class SettingsPagination
{
	public string $param_name = 'page';
	public bool $show_separator = false;
	public int $show_count_items = 5;

	public string $separator_text = '...';

	public string $wrap_tag = 'ul';
	public string $wrap_item = 'li';
	public string $wrap_current_tag = 'span';
	public string $wrap_link_append_tag = '';
	public string $wrap_separator_tag = 'span';

	public string $class_wrap = 'pagination';
	public string $class_item = 'item';
	public string $class_current = 'current';
	public string $class_link_append = 'wrap_link';
	public string $class_link = 'link';
	public string $class_separator = 'separator';
}