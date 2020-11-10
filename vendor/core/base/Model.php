<?php 

namespace core\base;

use core\base\Db;

/**
 * Базовый класс Model.
 */
abstract class Model
{
	/**
     * @var string TABLE - название таблицы в БД.
     */
	protected const TABLE = '';
	/**
     * @var array $allowed_insert - белый список для валидации полей таблицы с задачами при добавлении новой задачи.
     */
	protected static $allowed_insert = ['name', 'email', 'task'];
	/**
     * @var array $allowed_update - белый список для валидации полей таблицы с задачами при обновлении задачи.
     */
	protected static $allowed_update = ['name', 'email', 'task', 'status', 'id'];

	/**
     * Инициализирует свойства со значениями при инициализации объекта классов-наследников Model.
	 *
     * @return void.
     */
	public function __construct(array $data = [])
	{
		foreach ($data as $key => $datum) {
			$this->$key = $datum;
		}
	}

	/**
     * Возвращает массив, содержащий все строки результирующего набора.
     *
     * @param string $sort_by - критерий сортировки задач.
     * @return array (массив).
     */
	public static function findAll(string $sort_by = 'id'): array
	{
		$db = Db::instance();
		if ($sort_by == 'id') {
			$sql = 'SELECT * FROM ' . static::TABLE . ' ORDER BY ' . $sort_by . ' DESC';
		} else {
			$sql = 'SELECT * FROM ' . static::TABLE . ' ORDER BY ' . $sort_by . ' ASC';
		}
		return $db->query($sql, static::class);
	}

	/**
     * Возвращает массив, содержащий одну строку результирующего набора.
     *
     * @param string $column - название поля таблицы.
     * @param string $datum - содержимое поля таблицы.
     * @return array (массив).
     */
	public static function findOne(string $column, string $datum): array
	{
		$db = Db::instance();
		$sql = 'SELECT * FROM ' . static::TABLE . ' WHERE ' . $column . ' = :' . $column;
		return $db->query($sql, static::class, $column, $datum);
	}

	/**
     * Реализует добавление задачи в таблицу.
     *
     * @return void.
     */
	public function insert()
	{
		$props = get_object_vars($this);

		$columns = [];
		$binds = [];
		$data = [];
		foreach ($props as $name => $value) {
			if(!in_array($name, self::$allowed_insert, true)){
				die('Некорректное поле: ' . $name);
			}
			$columns[] = $name;
			$binds[] = ':' . $name;
			$data[':' . $name] = $value;
		}

		$sql = 'INSERT INTO ' . static::TABLE . ' (' . implode(',', $columns) . ') VALUES (' . implode(',', $binds) . ')';

		$db = Db::instance();
		$db->execute($sql, $data);
	}

	/**
     * Реализует обновление задачи в таблице.
     *
     * @return void.
     */
	public function update($id)
	{
		$this->id = $id;
		$props = get_object_vars($this);

		$columns_binds = [];
		$data = [];
		foreach ($props as $name => $value) {
			if (!in_array($name, self::$allowed_update, true)){
				die('Некорректное поле: ' . $name);
			}
			if ($name != 'id') {
				$columns_binds[] = $name . '=:' . $name;
			}
			$data[':' . $name] = $value;
		}

		$sql = 'UPDATE ' . static::TABLE . ' SET ' . implode(', ', $columns_binds) . ' WHERE id=:id';

		$db = Db::instance();
		$db->execute($sql, $data);
	}
}
