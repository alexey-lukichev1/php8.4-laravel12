@extends('layouts.main')
@section('content')
<div class="container mt-4" id="todoContainer"
     data-route-index="{{ route('todos.index') }}"
     data-route-store="{{ route('todos.store') }}"
     data-route-update="{{ route('todos.update', ':id') }}"
     data-route-destroy="{{ route('todos.destroy', ':id') }}"
     data-route-clear-completed="{{ route('todos.clearCompleted') }}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0 text-center">Todo Приложение</h2>
                </div>
                <div class="card-body">
                    <!-- Форма добавления нового todo -->
                    <div class="mb-4">
                        <form id="todoForm" class="d-flex gap-2">
                            <input 
                                type="text" 
                                id="todoInput" 
                                class="form-control" 
                                placeholder="Добавить новую задачу..." 
                                required
                            >
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        </form>
                    </div>

                    <!-- Фильтры -->
                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">
                                Все
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-btn" data-filter="active">
                                Активные
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-btn" data-filter="completed">
                                Завершенные
                            </button>
                        </div>
                    </div>

                    <!-- Счетчик задач -->
                    <div class="mb-3">
                        <small class="text-muted">
                            Всего: <span id="totalCount">0</span> | 
                            Активных: <span id="activeCount">0</span> | 
                            Завершенных: <span id="completedCount">0</span>
                        </small>
                    </div>

                    <!-- Список todo -->
                    <div id="todoList" class="list-group">
                        <!-- Todo элементы будут добавлены через JavaScript -->
                    </div>

                    <!-- Кнопка очистки завершенных -->
                    <div class="mt-3 text-end">
                        <button id="clearCompleted" class="btn btn-sm btn-outline-danger" style="display: none;">
                            Очистить завершенные
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
