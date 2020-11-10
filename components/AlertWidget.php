<?php 

namespace app\components;

use core\base\View;

/**
 * Виджет вывода сообщений об успешности/неуспешности выполненных действий.
 */
class AlertWidget
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
     * @var bool $status - успех/неуспех выполненного действия.
     */
	protected static $status;
	/**
     * @var string $response - сообщение, которое нужно вывести в виджете.
     */
	protected static $response;

	/**
     * Инициализирует необходимые свойства для работы виджета.
     *
     * @return bool успешность инициализации.
     */
	protected static function init(): bool
	{
		if (isset($_SESSION['responseStatus']) && isset($_SESSION['response'])){
			self::$view = new View;
			self::$template = __DIR__ . '/templates/alert/index.php';
			self::$status = $_SESSION['responseStatus'];
			self::$response = $_SESSION['response'];
			unset($_SESSION['responseStatus']);
			unset($_SESSION['response']);
			return true;
		} else {
			return false;
		}
	} 

	/**
     * Запускает работу виджета.
     *
     * @return void.
     */
	public static function run($format = '')
	{
		if (self::init()) {
			self::$view->status = self::$status;
			self::$view->response = self::$response;
			if ($format == 'array') {
				return ['status' => self::$status, 'html' => self::$view->render(self::$template)];
			}
				return self::$view->render(self::$template);
		}
	}
}
