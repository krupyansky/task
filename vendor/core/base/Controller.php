<?php 

namespace core\base;

use core\base\View;

/**
 * Базовый класс Controller.
 */
abstract class Controller
{
	/**
     * @var View $view - объект базового класса View.
     */
	protected View $view;
	/**
     * @var array $sort_by - белый список для валидации критерия сортировки заданий.
     */
	protected static $sort_by = ['name', 'email', 'status'];
	
	/**
     * Инициализирует объект класса View.
	 *
     * @return void.
     */
	public function __construct()
	{
		$this->view = new View;
	}

	/**
     * Валидирует критерий сортировки заданий.
     *
     * @return string критерий сортировки заданий.
     */
	protected static function validateSortBy(): string
	{
		if (isset($_GET['sort_by']) && in_array($_GET['sort_by'], static::$sort_by, true)) {
			$sort_by = $_GET['sort_by'];
		} else {
	    	$sort_by = 'id';
		}
		return $sort_by;
	}

	/**
     * Записывает в сессию статус и сообщение об успешности/неуспешности совершенного действия.
     *
     * @param bool $status - успех/неуспех выполненного действия.
     * @param string $response - сообщение, которое нужно вывести.
     * @return void.
     */
	protected static function setFlash(bool $status, string $response): void
	{
		$_SESSION['responseStatus'] = $status;
		$_SESSION['response'] = $response;
	}
}
