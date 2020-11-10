<?php 

namespace app\components;

use core\base\View;

/**
 * Виджет вывода пагинации на странице с задачами.
 */
class PaginationWidget
{
	/**
     * @var View $view - объект базового класса View.
     */
	protected static View $view;
	/**
     * @var string $template - расположение вида виджета.
     */
	protected static $template;
	/**
     * @var int $count_pages - количество страниц пагинаций.
     */
	protected static $count_pages;
	/**
     * @var int $active - номер текущей страницы пагинации.
     */
	protected static $active;
	/**
     * @var array $start_end - массив с номерами крайних от активного страниц пагинации.
     */
	protected static $start_end;
	/**
     * @var string $url - url кнопки пагинации для первой страницы с учетом критерия сортировки задач.
     */
	protected static $url;
	/**
     * @var string $url_page - url кнопки пагинации для остальных страницы с учетом критерия сортировки задач.
     */
	protected static $url_page;
	/**
     * @var array $sort_by - белый список для валидации критерия сортировки заданий.
     */
	protected static $sort_by = ['name', 'email', 'status'];

	/**
     * Инициализирует необходимые свойства для работы виджета.
     *
     * @param int $count_items - количество всех записей(задач).
     * @param int $count_show_pages - количество нумерованных кнопок пагинаций.
     * @return void.
     */
	protected static function init(int $count_items, int $count_show_pages = 5): void
	{
		self::$view = new View;
		self::$template = __DIR__ . '/templates/pagination/index.php';

		self::$count_pages = self::getCountPages($count_items);
		if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] <= self::$count_pages) {
			self::$active = $_GET['page'];
		} else {
			self::$active = 1;
		}
		self::$start_end = self::getStartEndPagination(self::$count_pages, self::$active, $count_show_pages);

		self::setUrls();
	} 

	/**
     * Запускает работу виджета.
     *
     * @param int $count_items - количество всех записей(задач).
     * @param int $count_show_pages - количество нумерованных кнопок пагинации.
     * @return void.
     */
	public static function run(int $count_items, int $count_show_pages = 5): string
	{
		self::init($count_items, $count_show_pages = 5);
	
		self::$view->count_pages = self::$count_pages;
		self::$view->active = self::$active;
		self::$view->start = self::$start_end[0];
		self::$view->end = self::$start_end[1];
		self::$view->url = self::$url;
		self::$view->url_page = self::$url_page;

		return self::$view->render(self::$template);	
	}

	/**
     * Получает количество пагинируемых страниц.
     *
     * @param int $count_items - количество всех записей(задач).
     * @return int $count_pages - количество пагинируемых страниц.
     */
	protected static function getCountPages(int $count_items): int
	{
		$count_pages = intdiv($count_items, 3);
		if ($count_items % 3 > 0) {
			$count_pages += 1;
		}
		return $count_pages;
	}

	/**
     * Получает массив с номерами крайних от активного страниц пагинации.
     *
     * @param int $count_pages - количество пагинируемых страниц.
     * @param int $active - номер текущей страницы пагинации.
     * @param int $count_show_pages - количество нумерованных кнопок пагинации.
     * @return array (массив) с номерами крайних от активного страниц пагинации.
     */
	protected static function getStartEndPagination(int $count_pages, int $active, int $count_show_pages): array
	{
		if ($count_pages > 1) {
	        $left = $active - 1;
	        if ($left < floor($count_show_pages / 2)) $start = 1;
	        else $start = $active - floor($count_show_pages / 2);
	        $end = $start + $count_show_pages - 1;
	        if ($end > $count_pages) {
	            $start -= ($end - $count_pages);
	            $end = $count_pages;
	            if ($start < 1) $start = 1;
	        }
	    }
	    return [$start, $end];
	}

	/**
     * Устанавливает url-ы кнопок пагинации с учетом критерия сортировки задач.
     *
     * @return void.
     */
	protected static function setUrls(): void
	{
		if (isset($_GET['sort_by']) && in_array($_GET['sort_by'], self::$sort_by, true)) {
			self::$url = "/?sort_by=". $_GET['sort_by'];
			self::$url_page = "/?sort_by=". $_GET['sort_by'] ."&page=";
		} else {
	    	self::$url = "/";
	    	self::$url_page = "/?page=";
		}
	}

	/**
     * Получает массив со свойствами, связанными с виджетом пагинации: 1) количество задач; 2) номер активной страницы пагинации.
     *
     * @param array $items - массив записей(задач).
     * @return array (массив) со свойствами, связанными с виджетом пагинации.
     */
	public static function getPaginationProps($items): array
	{
		$count_tasks = count($items);
		$count_pages = self::getCountPages($count_tasks);
		if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] <= $count_pages && $_GET['page'] > 0)
			$active = $_GET['page'];
		else 
			$active = 1;
		return $pagination_props = ['count_tasks' => $count_tasks, 'active_page' => $active];
	}
}
