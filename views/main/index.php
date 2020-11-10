<?php 

session_start();

use app\components\PaginationWidget;
use app\components\AlertWidget;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">    
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="https://getbootstrap.com/docs/3.3/favicon.ico">
	<link rel="canonical" href="https://getbootstrap.com/docs/3.3/examples/justified-nav/">
	<title>Task manager</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Custom styles for this template -->
	<link href="../../web/css/justified-nav.css" rel="stylesheet">
	<link href="../../web/css/style.css" rel="stylesheet">

	<!-- jQuery CDN -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<!-- Custom js-files for this template -->
	<script src="../../web/js/validate.js"></script>
	<script src="../../web/js/main.js"></script>
</head>

<body>
<div class="container" id="containerTop">

  	<?= AlertWidget::run() ?>

    <!-- Меню -->
	<div class="masthead">
		<h3 class="text-muted">Приложение-задачник</h3>
		<nav>
		<ul class="nav nav-justified">
			<li><a href="/">Главная</a></li>
			<li data-toggle="modal" data-target="#ModalAddTask"><a style="cursor: pointer;">Добавить задачу</a></li>
			<?php if (!isset($_SESSION['admin'])){ ?>
			<li data-toggle="modal" data-target="#ModalAdmin"><a style="cursor: pointer;">Админка</a></li>
			<?php } else{ ?>
			<li><a href="/?action=logout">Выйти</a></li>
			<?php } ?>
		</ul>
		</nav>
	</div>

    <!-- Модальное окно для добавления задачи -->
	<div class="modal fade" id="ModalAddTask" tabindex="-1" role="dialog" aria-labelledby="ModalLabelAdd">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="ModalLabelAdd">Добавить задачу</h4>
				</div>
				<div class="modal-body">
				<!-- <form action="index.php?action=add-task" method="post" id="formAddTask"> -->
					<form id="formAddTask">
					<!-- <form name="addTaskForm"> -->
						<div class="form-group">
							<label for="name">Имя:</label>
							<input type="text" class="form-control" name='name' id="name" placeholder="Винсент">
						</div>
						<div class="form-group">
							<label for="email">Email:</label>
							<input type="email" class="form-control" name='email' id="email" placeholder="example@mail.ru">
						</div>
						<div class="form-group">
							<label for="task">Задача:</label>
							<textarea class="form-control" name="task" id="task" rows="6"></textarea>
						</div>
						<button type="submit" class="btn btn-lg btn-primary" id="addTask">Добавить задачу</button>
					</form>
				</div>
			</div>
		</div>
	</div>

    <!-- Модальное окно для входа в админку -->
	<div class="modal fade" id="ModalAdmin" tabindex="-1" role="dialog" aria-labelledby="ModalLabelAdmin">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="ModalLabelAdmin">Вход в админку</h4>
				</div>
			<div class="modal-body">
				<!-- <form action="index.php?action=checkout" method="post" id="formAdmin"> -->
				<form id="formAdmin">
					<div class="form-group">
						<label for="login">Логин</label>
						<input type="text" class="form-control" name="login" id="login">
					</div>
					<div class="form-group">
						<label for="pass">Пароль</label>
						<input type="password" class="form-control" name="pass" id="pass">
					</div>
					<button type="submit" class="btn btn-lg btn-primary" id="buttonAdmin">Войти</button>
				</form>
			</div>
			</div>
		</div>
	</div>

    <!-- Сортировщик -->
	<div class="row" style="margin: 100px 0"> 
		<div class="col-md-8 col-md-offset-2">
			<h2>Сортировать по:</h2>
			<div class="row">
				<div class="col-xs-9">
					<select name="sort_by" class="form-control" id="selectSortBy">
						<option value="name">имени</option>
						<option value="email">email</option>
						<option value="status">статусу</option>
					</select>
				</div>
				<div class="col-xs-3">
					<button class="btn btn-bg btn-success" data-sort="name" role="button" id="buttonSortBy">СОРТИРОВАТЬ</button>
				</div>
			</div>
		</div>
	</div>

    <!-- Список задач -->
	<div class="row" id="listTasks">
	<?php if ($this->has_tasks): ?>
		<?php foreach ($this->tasks as $task): ?>
			<div class="col-lg-4">
				<h2>Задача №<?= htmlspecialchars($task->id, ENT_QUOTES, 'UTF-8') ?></h2>
				<p class="task-name"><b>Имя:</b> <?= htmlspecialchars($task->name, ENT_QUOTES, 'UTF-8') ?></p>
				<p class="task-email"><b>Email:</b> <?= htmlspecialchars($task->email, ENT_QUOTES, 'UTF-8') ?></p>
				<p class="task-task"><b>Задача:</b><br> <?= nl2br(htmlspecialchars($task->task, ENT_QUOTES, 'UTF-8')) ?></p>
				<p>
				<?php if (isset($_SESSION['admin']) && !$task->status): ?>
				<input class="task-status" type="checkbox" data-id="<?= $task->id ?>" style="transform:scale(2.0); margin-left: 5px; margin-right: 5px;">
				<?php endif; ?>
				<b>Статус:</b> <?= $task->status ? 'Выполнено' : 'Не выполнено' ?>
				</p>
				<?php if (isset($_SESSION['admin'])): ?>
				<button data-id="<?= $task->id ?>" data-toggle="modal" data-target="#ModalAdminEdit" type="button" class="btn btn-primary buttonUpdate" id="buttonUpdate"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="col-lg-4 col-lg-offset-4">
			<h2 style="text-align: center">ДОБАВЬТЕ ЗАДАЧУ...</h2>
		</div>
	<?php endif; ?>
	</div>

    <!-- Модальное окно для редактирования задачи -->
	<?php if (isset($_SESSION['admin'])): ?>
		<div class="modal fade" id="ModalAdminEdit" tabindex="-1" role="dialog" aria-labelledby="ModalLabelEdit">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="ModalLabelEdit">Редактировать задачу</h4>
					</div>
					<div class="modal-body">
						<form id="formUpdate">
							<div class="form-group">
								<label for="name">Имя:</label>
								<input type="text" class="form-control" name='name' id="nameUpdate" placeholder="Винсент">
							</div>
							<div class="form-group">
								<label for="email">Email:</label>
								<input type="email" class="form-control" name='email' id="emailUpdate" placeholder="example@mail.ru">
							</div>
							<div class="form-group">
								<label for="task">Задача:</label>
								<textarea class="form-control" name="task" id="taskUpdate" rows="6"></textarea>
							</div>
							<button type="submit" class="btn btn-lg btn-primary buttonEdit" id="buttonEdit">Редактировать</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

    <?php if ($this->count_tasks > 3 && $this->has_tasks): ?>
    	<?= PaginationWidget::run($this->count_tasks, 5); ?>
    <?php endif; ?>

    <!-- Футер -->
    <footer class="footer">
      <p>© 2020 Приложение-задачник</p>
    </footer>

</div>
</body>
</html>