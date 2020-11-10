$(document).ready(function(){
    // Validate
    function validateFormTask(form){
        $(form).validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                },
                email: {
                    required: true,
                    email: true,
                },
                task: "required",
            },
            messages: {
                name: {
                    required: "Пожалуйста, введите свое имя",
                    minlength: jQuery.validator.format("Введите {0} символа")
                  },
                email: "Пожалуйста, введите e-mail",
                task: "Пожалуйста, введите задачу",
            }
        });
    };

    function validateFormAdmin(form){
        $(form).validate({
            rules: {
                login: {
                    required: true,
                },
                pass: {
                    required: true,
                },
            },
            messages: {
                login: "Пожалуйста, введите логин",
                pass: "Пожалуйста, введите пароль",
            }
        });
    };

    function validateBlock(){
        formUpdate = $('#formUpdate');
        formAddTask = $('#formAddTask');
        validateFormTask('#formAddTask');
        validateFormTask('#formUpdate');
        validateFormAdmin('#formAdmin');
    }

    validateBlock();
    // Validate

    // Считывает GET переменные из URL страницы и возвращает их как ассоциативный массив.
    let getUrlParameter = function getUrlParameter(sParam) {
        let sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };

    // Формирует содержимое формы для редактирования задания
    function buttonUpdate(){
        $('.buttonUpdate').on('click', function(){
            let id = $(this).data("id");
            let name = $(this).parent().find('.task-name').text().slice(5);
            let email = $(this).parent().find('.task-email').text().slice(7);
            let task = $(this).parent().find('.task-task').text().slice(8).replaceAll('<br>', '');
            $('#nameUpdate').val(name);
            $('#emailUpdate').val(email);
            $('#taskUpdate').val(task);
            $('#buttonEdit').data('id', id)
        });
    }
    buttonUpdate();

    // Формирует в кнопке по выбранному критерию сортировки ссылку, в котором указан GET параметр сортировки задач
    $('#selectSortBy').on('change', function(){
        let sortBy = $('#selectSortBy option:selected').val();
        let btn = $('#buttonSortBy');
        btn.data('sort', sortBy);
    });

    // Вход в админку
    $('#buttonAdmin').on('click', function(){
        let login = $('#login').val();
        let pass = $('#pass').val();
        let alertWidget = $('#alertWidget');
        let ModalAdmin = $('#ModalAdmin .modal-header');
        $.ajax({
            url: '/index.php',
            data: {
                'action': 'checkout',
                'is_ajax': 'true',
                'login': login,
                'pass': pass,
            },
            type: 'GET',
            success: function(res){ 
                const result = JSON.parse(res);
                if (typeof alertWidget.html() != "undefined") {
                    alertWidget.remove()
                }
                ModalAdmin.after(result.htmlAlert);
                if (result.htmlAlertStatus) {
                    setTimeout(() => {window.location.href = "/";}, 1000);
                }
            },
            error: function(){
                alert('Попробуйте позже!');
            }
        });
        return false;
    });

    // Асинхронно добавляем задачу
    $('#addTask').on('click', function(){
        if (!formAddTask.valid()) { 
            return false;
        } else {
            let name = $('#name').val();
            let email = $('#email').val();
            let task = $('#task').val();
            let listTasks = $('#listTasks');
            let containerTop = $('#containerTop');
            let alertWidget = $('#alertWidget');
            let paginationElem = $('#pagination');
            let ModalAddTask = $('#ModalAddTask .modal-header');
            $.ajax({
                url: '/index.php',
                data: {
                    'action': 'add-task',
                    'is_ajax': 'true',
                    'name': name,
                    'email': email,
                    'task': task,
                },
                type: 'GET',
                success: function(res){ 
                    const result = JSON.parse(res);
                    if (typeof alertWidget.html() != "undefined") {
                        alertWidget.remove()
                    }
                    if (result.htmlAlertStatus) {
                        $('#ModalAddTask').modal('hide');

                        listTasks.empty();
                        listTasks.html(result.htmlTasks);

                        containerTop.prepend(result.htmlAlert);
                        paginationElem.remove();
                        listTasks.after(result.htmlPagination);

                        $('#name').val('');
                        $('#email').val('')
                        $('#task').val('');

                        buttonUpdate();
                        updateTaskStatus();

                        history.pushState(null, null, '/');
                    } else {
                        if (typeof alertWidget.html() != "undefined") {
                            alertWidget.remove()
                        }
                        ModalAddTask.after(result.htmlAlert);
                    }        
                },
                error: function(){
                    alert('Попробуйте позже!');
                }
            });
            return false;
        }
    });

    // Асинхронно сортируем задачи
    $('#buttonSortBy').on('click', function(){
        let sort_by = $(this).data('sort');
        let listTasks = $('#listTasks');
        let paginationElem = $('#pagination');
        $.ajax({
            url: '/index.php',
            data: {
                'action': 'index',
                'is_ajax': 'true',
                'sort_by': sort_by,
            },
            type: 'GET',
            success: function(res){
                if (res != 'error') {
                    const result = JSON.parse(res);
                    listTasks.empty();
                    listTasks.html(result.htmlTasks);
                    paginationElem.remove();
                    listTasks.after(result.htmlPagination);
                    buttonUpdate();
                    updateTaskStatus();
                    history.pushState(null, null, '?sort_by=' + sort_by);
                } else {
                    alert('Задач нет! Сортировка невозможна!')
                }
            },
            error: function(){
                alert('Попробуйте позже!');
            }
        });
        return false;
    });

    // Асинхронно обновляем задачу
    $('.buttonEdit').on('click', function(){
        if (!formUpdate.valid()) { 
            return false;
        } else {
            let name = $('#nameUpdate').val();
            let email = $('#emailUpdate').val();
            let task = $('#taskUpdate').val();
            let id = $('#buttonEdit').data("id");
            let page = getUrlParameter('page');
            let sort_by = getUrlParameter('sort_by');
            let listTasks = $('#listTasks');
            let containerTop = $('#containerTop');
            let alertWidget = $('#alertWidget');
            let ModalAdminEdit = $('#ModalAdminEdit .modal-header');
            $.ajax({
                url: '/index.php',
                data: {
                    'action': 'update',
                    'is_ajax': 'true',
                    'name': name,
                    'email': email,
                    'task': task,
                    'id': id,
                    'page': page,
                    'sort_by': sort_by,
                },
                type: 'GET',
                success: function(res){
                    if (typeof alertWidget.html() != "undefined") {
                        alertWidget.remove()
                    }
                    const result = JSON.parse(res);
                    if (result.htmlAlertStatus) {
                        $('#ModalAdminEdit').modal('hide');
                        listTasks.empty();
                        listTasks.html(result.htmlTasks);                       
                        containerTop.prepend(result.htmlAlert);
                        buttonUpdate();
                        updateTaskStatus();
                    } else {
                        ModalAdminEdit.after(result.htmlAlert);
                    }
                    
                },
                error: function(){
                    alert('Попробуйте позже!');
                }
            });
            return false;
        }
    });

    // Асинхронно меняем статус задачи
    function updateTaskStatus(){
        $('.task-status').on('change', function(){
            let id = $(this).data("id");
            let page = getUrlParameter('page');
            let sort_by = getUrlParameter('sort_by');
            let listTasks = $('#listTasks');
            let containerTop = $('#containerTop');
            let alertWidget = $('#alertWidget');
            $.ajax({
                url: '/index.php',
                data: {
                    'action': 'update-status',
                    'id': id,
                    'is_ajax': 'true',
                    'page': page,
                    'sort_by': sort_by,
                },
                type: 'GET',
                success: function(res){
                    const result = JSON.parse(res);
                    listTasks.empty();
                    listTasks.html(result.htmlTask);
                    if (typeof alertWidget.html() != "undefined") {
                        alertWidget.remove()
                    }
                    containerTop.prepend(result.htmlAlert);
                    buttonUpdate();
                    updateTaskStatus(); 
                },
                error: function(){
                    alert('Попробуйте позже!');
                }
            });
            return false;
        });
    }
    updateTaskStatus();
});
