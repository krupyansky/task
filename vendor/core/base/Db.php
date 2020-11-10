<?php 

namespace core\base;

use PDO;

/**
 * Базовый класс Db.
 */
class Db
{
	/**
     * @var Db $instance - объект базового класса Db.
     */
	protected static $instance = null;
	/**
     * @var array $config - массив настроек для подключения к БД.
     */
	protected array $config = [];
	/**
     * @var string $dsn - информация, необходимая для подключения к базе данных.
     */
	protected string $dsn;
	/**
     * @var string $username - имя пользователя для строки DSN.
     */
	protected string $username;
	/**
     * @var string $password - пароль для строки DSN.
     */
	protected string $password;
	/**
     * @var PDO $dbn - экземпляр PDO, предоставляющий соединение с базой данных.
     */
	protected PDO $dbn;

	/**
     * Паттерн Singleton. Возращает либо создает объект базового класса Db.
	 *
     * @return Db $instance - объект базового класса Db.
     */
	public static function instance()
	{
		if (null === self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
     * Инициализирует экземпляр PDO, предоставляющий соединение с базой данных.
	 *
     * @return PDO $dbn - экземпляр PDO; либо сообщение о неудачной попытке инициализации.
     */
	protected function __construct()
	{
		$this->config = require_once __DIR__ . '/../../../config/db.php';
		$this->dsn = $this->config['dsn'];
		$this->username = $this->config['username'];
		$this->password = $this->config['password'];
		try {
			$this->dbn = new PDO($this->dsn, $this->username, $this->password);
		} catch (PDOException $e) {
			echo "Ошибка БД: " . $e->getMessage();
		}
		
	}

	/**
     * Возвращает новый объект указанного класса.
     * Свойствам объекта будут присвоены значения столбцов, имена которых совпадут с именами свойств.
     *
     * @param string $sql - подготовленный SQL запрос.
     * @param string $class - название класса, объект которого будет возвращен методом.
     * @param string $column - название поля в таблице.
     * @param string $datum - содержимое поля.
     * @return $class object - объект указанного класса.
     */
	public function query($sql, $class, $column = '', $datum = ''): array
	{
		$sth = $this->dbn->prepare($sql);
		if ($column !== '' && $datum !== '') {
			$sth->bindValue(':'.$column, $datum);
		}
		$sth->execute();
		// $sth->fetchAll(PDO::FETCH_CLASS, $class);
		$data = $sth->fetchAll(PDO::FETCH_CLASS, $class);
		if (count($data) != 0) {
			return $data;
		} else {
			return [];
		}
	}

	/**
     * Выполняет SQL запрос и возвращает ответ.
     *
     * @param string $sql - подготовленный SQL запрос.
     * @param array $data - массив с данными для подстановки в запрос.
     * @return bool - ответ выполнения SQL запроса.
     */
	public function execute($sql, $data): bool
	{
		$sth = $this->dbn->prepare($sql);
		return $sth->execute($data);
	}
}
