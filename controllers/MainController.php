<?php 

namespace app\controllers;

use core\base\Controller;
use app\models\Task;
use app\models\User;
use app\components\PaginationWidget;
use app\components\AlertWidget;

/**
 * Основной Controller в web-приложении 
 */
class MainController extends Controller
{
	/**
     * Отдает клиенту главную страницу.
     *
     * @return void.
     */
	public function actionIndex()
	{
		session_start();
		$sort_by = self::validateSortBy();
		$tasks = Task::findAll($sort_by);

		if (count($tasks) > 0) {
			if (isset($_GET['is_ajax']) && $_GET['is_ajax'] == 'true') {
				$this->view->tasks = array_chunk($tasks, 3)[0];
				$html_tasks = $this->view->render(__DIR__ . '/../views/main/task.php');
				$html_pagination = PaginationWidget::run(count($tasks), 5);
				echo json_encode(array(
			        'htmlTasks' => $html_tasks,
			        'htmlPagination' => $html_pagination,
			    ));
			} else {
				$pagination_props = PaginationWidget::getPaginationProps($tasks);
				$count_tasks = $pagination_props['count_tasks'];
				$active_page = $pagination_props['active_page'];
				
				$this->view->has_tasks = true;
				$this->view->tasks = array_chunk($tasks, 3)[$active_page-1];
				$this->view->count_tasks = $count_tasks;

				$this->view->display(__DIR__ . '/../views/main/index.php');
			}
			
		} elseif (isset($_GET['is_ajax']) && $_GET['is_ajax'] == 'true') {
			echo 'error';
		} else {
			$this->view->has_tasks = false;
		}
	}

	/**
     * Обрабатывает функцию добавления задачи.
     *
     * @return void.
     */
	public function actionAddTask()
	{
		session_start();
		$data = ['name' => $_GET['name'], 'email' => $_GET['email'], 'task' => $_GET['task']];
		$res = Task::validateForm($data);
		if ($res === '') {
			$task = new Task($data);
			$task->insert();
			self::setFlash(true, 'Задача успешно добавлена!');
		} else {
			self::setFlash(false, 'Ошибка! Задача не добавлена!' . $res);
		}
		if (isset($_GET['is_ajax']) && $_GET['is_ajax'] == 'true') {
			$tasks = Task::findAll();
			$this->view->tasks = array_chunk($tasks, 3)[0];
			$html_tasks = $this->view->render(__DIR__ . '/../views/main/task.php');
			$alert_arr = AlertWidget::run('array');
			$html_alert_status = $alert_arr['status'];
			$html_alert = $alert_arr['html'];
			$html_pagination = PaginationWidget::run(count($tasks), 5);
			echo json_encode(array(
		        'htmlTasks' => $html_tasks,
		        'htmlAlert' => $html_alert,
		        'htmlAlertStatus' => $html_alert_status,
		        'htmlPagination' => $html_pagination,
		    ));
		} else {
			header("Location: /");
		}
	}

	/**
     * Обрабатывает функцию редактирования задачи.
     *
     * @return void.
     */
	public function actionUpdate()
	{
		session_start();
		$id = (int) $_GET['id'];
		$data = ['name' => $_GET['name'], 'email' => $_GET['email'], 'task' => $_GET['task']];
		if (isset($_SESSION['admin']) && is_numeric($id)) {
			$res = Task::validateForm($data);
			if ($res === '') {
				$task = new Task($data);
				$task->update($id);
				self::setFlash(true, 'Задача №' . $id . ' успешно обновлена!');
			} else {
				self::setFlash(false, 'Ошибка! Задача №' . $id . ' не обновлена!' . $res);
			}
		}
		if (isset($_GET['is_ajax']) && $_GET['is_ajax'] == 'true') {
			$sort_by = self::validateSortBy();
			$tasks = Task::findAll($sort_by);
			$pagination_props = PaginationWidget::getPaginationProps($tasks);
			$active_page = $pagination_props['active_page'];
			$this->view->tasks = array_chunk($tasks, 3)[$active_page-1];
			$html_tasks = $this->view->render(__DIR__ . '/../views/main/task.php');
			$alert_arr = AlertWidget::run('array');
			$html_alert_status = $alert_arr['status'];
			$html_alert = $alert_arr['html'];
			echo json_encode(array(
		        'htmlTasks' => $html_tasks,
		        'htmlAlert' => $html_alert,
		        'htmlAlertStatus' => $html_alert_status,
		    ));
		} else {
			header("Location: /");
		}
	}

	/**
     * Обрабатывает функцию обновления статуса задачи.
     *
     * @return void.
     */
	public function actionUpdateStatus()
	{
		session_start();
		$id = (int) $_GET['id'];
		if (isset($_SESSION['admin']) && is_numeric($id)) {
			$status = ['status' => 1];
			$task = new Task($status);
			$task->update($id);
			self::setFlash(true, 'Задача №' . $id . ' успешно выполнена!');
		}
		if (isset($_GET['is_ajax']) && $_GET['is_ajax'] == 'true') {
			$sort_by = self::validateSortBy();
			$tasks = Task::findAll($sort_by);
			$pagination_props = PaginationWidget::getPaginationProps($tasks);
			$active_page = $pagination_props['active_page'];
			$this->view->tasks = array_chunk($tasks, 3)[$active_page-1];
			$html_tasks = $this->view->render(__DIR__ . '/../views/main/task.php');
			$html_alert = AlertWidget::run();
			echo json_encode(array(
		        'htmlTask' => $html_tasks,
		        'htmlAlert' => $html_alert,
		    ));
		} else {
			header("Location: /");
		}
	}

	/**
     * Обрабатывает функцию авторизации в админку.
     *
     * @return void.
     */
	public function actionCheckout()
	{
		$data = ['login' => $_GET['login'], 'pass' => $_GET['pass']];
		if (!empty($data['login']) and !empty($data['pass'])) {
			session_start();
			$res = User::validateForm($data);
			if ($res === '') {
				$user = User::findOne('login', $data['login'])[0];
				if ($data['login'] === $user->login and $data['pass'] === $user->pass) {
					$_SESSION['admin'] = $user->login;
					self::setFlash(true, 'Авторизация прошла успешно!');
				} else {
					self::setFlash(false, 'Неправильно введен логин и/или пароль!');
				}
			} else {
				self::setFlash(false, 'Ошибка! Некорректные данные!' . $res);
			}
		}
		if (isset($_GET['is_ajax']) && $_GET['is_ajax'] == 'true') {
			$alert_arr = AlertWidget::run('array');
			$html_alert_status = $alert_arr['status'];
			$html_alert = $alert_arr['html'];
			echo json_encode(array(
		        'htmlAlert' => $html_alert,
		        'htmlAlertStatus' => $html_alert_status,
		    ));
		} else {
			header("Location: /");
		}

	}

	/**
     * Обрабатывает функцию выхода из админки.
     *
     * @return void.
     */
	public function actionLogout()
	{
		session_start();
		unset($_SESSION['admin']);
		header("Location: /");
	}
}
