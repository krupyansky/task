<?php 

namespace core\base;

/**
 * Базовый класс View.
 */
class View
{
	/**
     * @var array $data - массив свойств вида.
     */
	protected array $data = [];

	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	public function __get($name)
	{
		return $this->data[$name] ?? null;
	}

	public function __isset($name)
	{
		return isset($this->data[$name]);
	}

	/**
     * Отображает вид.
     *
     * @param string $view - расположение файла вида.
     * @return void.
     */
	public function display(string $view)
	{
		include $view;
	}

	/**
     * Возращает содержимое вида без вывода в браузер.
     *
     * @param string $view - расположение файла вида.
     * @return string $contents - содержимое вида без вывода в браузер.
     */
	public function render(string $view)
	{
		ob_start();
		include $view;
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}
