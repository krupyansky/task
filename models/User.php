<?php 

namespace app\models;

use core\base\Model;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Translation\MessageSelector;

/**
 * Модель по работе с таблицей администраторов.
 */
class User extends Model
{
	/**
     * @var string TABLE - название таблицы в БД.
     */
	protected const TABLE = 'users';

	/**
     * Возвращает текст ошибок при валидации данных из формы авторизации.
     *
     * @param array $data - данные, полученные из формы.
     * @return string $text_error - текст ошибок.
     */
	public static function validateForm($data)
	{
		foreach ($data as $key => $datum) {
			$data[$key] = htmlentities(trim($datum), ENT_QUOTES);
		}

		$validator = Validation::createValidator();

		$constraint = new Assert\Collection([    
		    'login' => [
		    	new Assert\NotBlank(),
		    	new Assert\Type(['type' => 'string']),
		    	new Assert\Regex('/^[A-Za-z]+$/u'),
		    ],
		    'pass' => [
		    	new Assert\NotBlank(),
		    	new Assert\Type(['type' => 'string']),
		    ],
		]);

		$violations = $validator->validate($data, $constraint);

		$errors = [];
		if (0 !== count($violations)) {
		    foreach ($violations as $violation) {
		        $errors[$violation->getPropertyPath()] = $violation->getPropertyPath() . ' : ' . $violation->getMessage();
		    }
		} 

		$text_error = '';
		if (isset($errors['[login]'])) {
			$text_error .= "<br>Некорректно введен Логин!";
		}
		if (isset($errors['[pass]'])) {
			$text_error .= "<br>Некорректно введен Пароль!";
		}

		return $text_error;
	}
}
