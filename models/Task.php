<?php 

namespace app\models;

use core\base\Model;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Translation\MessageSelector;

/**
 * Модель по работе с таблицей задач.
 */
class Task extends Model
{
	/**
     * @var string TABLE - название таблицы в БД.
     */
	protected const TABLE = 'tasks';

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
		    'name' => [
		    	new Assert\NotBlank(),
		    	new Assert\Length(['min' => 2]),
		    	new Assert\Type(['type' => 'string']),
		    	new Assert\Regex('/^[a-zA-Zа-яА-Я]+$/u'),
		    ],
		    'email' => [
		    	new Assert\Email(),
		    	new Assert\NotBlank(),
		    ],
		    'task' => [
		        new Assert\NotBlank(),
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
		if (isset($errors['[name]'])) {
			$text_error .= "<br>Неправильно введено имя!";
		}
		if (isset($errors['[email]'])) {
			$text_error .= "<br>Неправильно введен email!";
		}
		if (isset($errors['[task]'])) {
			$text_error .= "<br>Неправильно введена задача!";
		}

		return $text_error;
	}
}
