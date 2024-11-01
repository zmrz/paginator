# Постраничник / Paginator

![Static Badge](https://img.shields.io/badge/VER-0.1-%2300BB22)
![Static Badge](https://img.shields.io/badge/PHP-8.1-%2300AACC)

#### Помогает в работе со страницами. Настроил теги оберток и их css-классы, нужное количество к выводу и вуаля.

<hr>

Быстрый пример вывода постраничника.

```php
use Simplex\paginator\Pagination;

$pagination = new Pagination(30);

echo $pagination->getRenderString();
```

```html
<ul class="pagination">
    <li class="item">
        <span class="current">1</span>
    </li>
    <li class="item">
        <a href="/articles/?page=2" class="link">2</a>
    </li>
    <li class="item">
        <a href="/articles/?page=3" class="link">3</a>
    </li>
    <li class="item">
        <a href="/articles/?page=4" class="link">4</a>
    </li>
    <li class="item">
        <span class="separator">...</span>
    </li>
    <li class="item">
        <a href="/articles/?sort=1&page=30" class="link">30</a>
    </li>
</ul>
```
<hr>

`Постраничник` можно настроить и использовать, как с получением готового html кода, так и просто как итератор с элементами `постраничника`.

Для использования нужно знать сколько всего страниц используется. Может принимать `url` параметр, либо берет по-умолчанию из глобального массива `$_SERVER['REQUEST_URI']`.

Настраиваем под себя все настройки из класса `SettingPagination`

К примеру, `param_name` строковое значение, которое будет считаться ключем текущей страницы из глобального массива `$_GET`. По умолчанию указан как `page`, соответственно ищет `$_GET['page']`

<hr>

## Инициализация
<hr>

Инициализируем, тремя способами.


1. Просто указав кол-во страниц. В таком случае, постраничник будет использовать текущий url и настройки по умолчанию.
```php
$pagination = new Pagination(30);
```
2. Инициализируем, добавляя пользовательский url
```php
$url = "/articles/?sort=1&page=14"
$pagination = new Pagination(30, $url);
```
3. Инициализируем, еще и накидывая настройки
```php
$url = "/articles/?sort=1&current=14"

$settings = new \Simplex\paginator\components\SettingsPagination();
$settings->param_name = 'current'

$pagination = new Pagination(30, $url, $settings);
```
<hr>

## Выход
<hr>
На выход мы можем получить два варианта<br/><br/>
1. Либо сразу получить готовый html

```php
echo $pagination->getRenderString();
```

```html
<ul class="pagination">
    <li class="item">
        <a href="/articles/?sort=1" class="link">1</a>
    </li>
    <li class="item">
        <span class="separator">...</span>
    </li>
    <li class="item">
        <a href="/articles/?sort=1&page=13" class="link">13</a>
    </li>
    <li class="item">
        <span class="current">14</span>
    </li>
    <li class="item">
        <a href="/articles/?sort=1&page=15" class="link">15</a>
    </li>
    <li class="item">
        <span class="separator">...</span>
    </li>
    <li class="item">
        <a href="/articles/?sort=1&page=30" class="link">30</a>
    </li>
</ul>
```

Или обработать как итератор

```php
foreach ($pagination->iterate() as $k => $paginationItem) {
    $paginationItem->getText() . PHP_EOL;
    // ...
}
```

```html
1
...
13
14
15
...
30
```

каждый элемент итератора `$paginationItem` преставляет собой объект класса `PaginationItem`, с помощью которого мы можем получить некоторые данные по элементу. Например:

1. Получить текст элемента
```php
$paginationItem->getText();
```
2. Получить тип элемента. Если `0`, то это ссылка, если `1` то это текущая страница, и если `2` - то это сепаратор(разделитель).
```php
$paginationItem->getType();
```
3. Получить url элемента, который, к примеру, можно вставлять в атрибут `href`, тега `<a>`
```php
$paginationItem->getLink();
```
или
```php
echo '<a href="'.$paginationItem->getLink().'">...</a>';
```






<hr>

## Настройки
<hr>

Настройки позволяют адаптировать постраничник под себя
```php
$settings = new \Simplex\paginator\components\SettingsPagination();
```

Указать какой параметр из массива `$_GET` будет учитываться как указатель страницы.<br/>
`По умолчанию - 'page'`
```php
$settings = new \Simplex\paginator\components\SettingsPagination();
$settings->param_name = 'page';
```

Использовать ли сепараторы, которые разделяют не близкие элементы.<br/>
`По умолчанию - true`
```php
$settings = new \Simplex\paginator\components\SettingsPagination();
$settings->show_separator = true;
```

Указать какое кол-во элементов выводить.<br/>
`По умолчанию - 5`
```php
$settings = new \Simplex\paginator\components\SettingsPagination();
$settings->show_count_items = 5;
```

Т.е. если у нас 30 страниц, и в настройках указано, что выводить 5 элементов с сепаратором. При этом мы находимся на 14 странице, то в массиве элементов будут первый (1) и последний элемент (30), текущая страница (14) и рядом с ней стоящие (13 и 15), а также сепараторы разделяющие множественное разделение (...).

Представление по типу: `[1] [...] [13] [14] [15] [...] [30]`
<br>
<br>

Так же в настройках, мы можем настроить во что оборачивать каждый элемент и какие классы им проставить. Если, к примеру, нас не устраивает, что все обернуто в `<ul>` и элементы обернуты в `<li>`, мо можем исправить это. Либо исправить под себя значения в самом классе, либо перед передачей его в класс `Pagination`.

Например, если я хочу чтобы все оборачивалось в `<div>` и обертка и элементы

```php
$url = "/articles/?sort=1&page=14"

$settings = new \Simplex\paginator\components\SettingsPagination();
$settings->wrap_tag = 'div';
$settings->wrap_item = 'div';

$pagination = new Pagination(30, $url, $settings);

echo $pagination->getRenderString();
```

```html
<div class="pagination">
    <div class="item">
        <a href="/articles/?sort=1" class="link">1</a>
    </div>
    <div class="item">
        <span class="separator">...</span>
    </div>
    <div class="item">
        <a href="/articles/?sort=1&page=13" class="link">13</a>
    </div>
    <div class="item">
        <span class="current">14</span>
    </div>
    <div class="item">
        <a href="/articles/?sort=1&page=15" class="link">15</a>
    </div>
    <div class="item">
        <span class="separator">...</span>
    </div>
    <div class="item">
        <a href="/articles/?sort=1&page=30" class="link">30</a>
    </div>
</div>
```