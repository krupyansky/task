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