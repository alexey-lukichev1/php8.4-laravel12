// Todo Application
(function() {
    let currentFilter = 'all';
    let todos = [];
    let stats = { total: 0, active: 0, completed: 0 };

    // Получение элементов DOM
    const todoForm = document.getElementById('todoForm');
    const todoInput = document.getElementById('todoInput');
    const todoList = document.getElementById('todoList');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const clearCompletedBtn = document.getElementById('clearCompleted');
    const totalCount = document.getElementById('totalCount');
    const activeCount = document.getElementById('activeCount');
    const completedCount = document.getElementById('completedCount');

    // Получение конфигурации из data-атрибутов
    const todoContainer = document.getElementById('todoContainer');
    if (!todoContainer) {
        console.error('Todo container not found');
        return;
    }

    const config = {
        routes: {
            index: todoContainer.dataset.routeIndex,
            store: todoContainer.dataset.routeStore,
            update: todoContainer.dataset.routeUpdate,
            destroy: todoContainer.dataset.routeDestroy,
            clearCompleted: todoContainer.dataset.routeClearCompleted
        },
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    };

    // Настройка заголовков для AJAX запросов
    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': config.csrfToken,
        'Accept': 'application/json'
    };

    // Загрузка todos с сервера
    async function loadTodos() {
        try {
            const response = await fetch(`${config.routes.index}?filter=${currentFilter}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            todos = data.todos;
            stats = {
                total: data.total,
                active: data.active,
                completed: data.completed
            };
            
            renderTodos();
            updateCounters();
            updateClearButton();
        } catch (error) {
            console.error('Ошибка загрузки задач:', error);
            if (todoList) {
                todoList.innerHTML = '<div class="empty-state text-danger">Ошибка загрузки данных</div>';
            }
        }
    }

    // Рендеринг списка
    function renderTodos() {
        if (!todoList) return;

        if (todos.length === 0) {
            todoList.innerHTML = '<div class="empty-state">Нет задач для отображения</div>';
        } else {
            todoList.innerHTML = todos.map(todo => `
                <div class="todo-item ${todo.completed ? 'completed' : ''}" data-id="${todo.id}">
                    <input 
                        type="checkbox" 
                        class="todo-checkbox" 
                        ${todo.completed ? 'checked' : ''}
                        data-todo-id="${todo.id}"
                    >
                    <p class="todo-title" data-todo-id="${todo.id}">${escapeHtml(todo.title)}</p>
                    <button class="todo-delete" data-todo-id="${todo.id}" title="Удалить">
                        ×
                    </button>
                </div>
            `).join('');

            // Добавляем обработчики событий для динамически созданных элементов
            attachEventListeners();
        }
    }

    // Привязка обработчиков событий
    function attachEventListeners() {
        // Обработчики для чекбоксов и заголовков
        document.querySelectorAll('.todo-checkbox, .todo-title').forEach(element => {
            element.addEventListener('click', function() {
                const todoId = parseInt(this.dataset.todoId);
                toggleTodo(todoId);
            });
        });

        // Обработчики для кнопок удаления
        document.querySelectorAll('.todo-delete').forEach(button => {
            button.addEventListener('click', function() {
                const todoId = parseInt(this.dataset.todoId);
                deleteTodo(todoId);
            });
        });
    }

    // Обновление счетчиков
    function updateCounters() {
        if (totalCount) totalCount.textContent = stats.total;
        if (activeCount) activeCount.textContent = stats.active;
        if (completedCount) completedCount.textContent = stats.completed;
    }

    // Обновление кнопки очистки
    function updateClearButton() {
        if (clearCompletedBtn) {
            clearCompletedBtn.style.display = stats.completed > 0 ? 'inline-block' : 'none';
        }
    }

    // Добавление новой задачи
    async function addTodo(title) {
        if (title.trim() === '') return;
        
        try {
            const response = await fetch(config.routes.store, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ title: title.trim() })
            });

            if (response.ok) {
                await loadTodos();
                if (todoInput) todoInput.value = '';
            } else {
                const error = await response.json();
                alert('Ошибка: ' + (error.message || 'Не удалось добавить задачу'));
            }
        } catch (error) {
            console.error('Ошибка добавления задачи:', error);
            alert('Ошибка при добавлении задачи');
        }
    }

    // Переключение статуса задачи
    async function toggleTodo(id) {
        const todo = todos.find(t => t.id === id);
        if (!todo) return;

        try {
            const url = config.routes.update.replace(':id', id);
            const response = await fetch(url, {
                method: 'PATCH',
                headers: headers,
                body: JSON.stringify({ completed: !todo.completed })
            });

            if (response.ok) {
                await loadTodos();
            } else {
                alert('Ошибка при обновлении задачи');
            }
        } catch (error) {
            console.error('Ошибка обновления задачи:', error);
            alert('Ошибка при обновлении задачи');
        }
    }

    // Удаление задачи
    async function deleteTodo(id) {
        if (!confirm('Вы уверены, что хотите удалить эту задачу?')) return;

        try {
            const url = config.routes.destroy.replace(':id', id);
            const response = await fetch(url, {
                method: 'DELETE',
                headers: headers
            });

            if (response.ok) {
                await loadTodos();
            } else {
                alert('Ошибка при удалении задачи');
            }
        } catch (error) {
            console.error('Ошибка удаления задачи:', error);
            alert('Ошибка при удалении задачи');
        }
    }

    // Очистка завершенных задач
    async function clearCompleted() {
        if (!confirm('Удалить все завершенные задачи?')) return;

        try {
            const response = await fetch(config.routes.clearCompleted, {
                method: 'DELETE',
                headers: headers
            });

            if (response.ok) {
                await loadTodos();
            } else {
                alert('Ошибка при очистке задач');
            }
        } catch (error) {
            console.error('Ошибка очистки задач:', error);
            alert('Ошибка при очистке задач');
        }
    }

    // Установка фильтра
    function setFilter(filter) {
        currentFilter = filter;
        filterButtons.forEach(btn => {
            if (btn.dataset.filter === filter) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        loadTodos();
    }

    // Экранирование HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Инициализация обработчиков событий
    function init() {
        if (!todoForm || !todoInput) return;

        todoForm.addEventListener('submit', (e) => {
            e.preventDefault();
            addTodo(todoInput.value);
        });

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                setFilter(btn.dataset.filter);
            });
        });

        if (clearCompletedBtn) {
            clearCompletedBtn.addEventListener('click', clearCompleted);
        }

        // Инициализация при загрузке страницы
        loadTodos();
    }

    // Запуск при загрузке DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
