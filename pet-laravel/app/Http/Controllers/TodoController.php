<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TodoController extends Controller
{
    /**
     * Получить все todos или отфильтрованные
     */
    public function index(Request $request): JsonResponse
    {
        $filter = $request->get('filter', 'all');
        
        $query = Todo::query();
        
        if ($filter === 'active') {
            $query->where('completed', false);
        } elseif ($filter === 'completed') {
            $query->where('completed', true);
        }
        
        $todos = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'todos' => $todos,
            'total' => Todo::count(),
            'active' => Todo::where('completed', false)->count(),
            'completed' => Todo::where('completed', true)->count()
        ]);
    }

    /**
     * Создать новый todo
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $todo = Todo::create([
            'title' => $request->title,
            'completed' => false
        ]);

        return response()->json([
            'todo' => $todo,
            'message' => 'Задача успешно добавлена'
        ], 201);
    }

    /**
     * Обновить todo (переключить статус completed)
     */
    public function update(Request $request, Todo $todo): JsonResponse
    {
        $request->validate([
            'completed' => 'sometimes|boolean'
        ]);

        if ($request->has('completed')) {
            $todo->completed = $request->completed;
        }

        if ($request->has('title')) {
            $todo->title = $request->title;
        }

        $todo->save();

        return response()->json([
            'todo' => $todo,
            'message' => 'Задача обновлена'
        ]);
    }

    /**
     * Удалить todo
     */
    public function destroy(Todo $todo): JsonResponse
    {
        $todo->delete();

        return response()->json([
            'message' => 'Задача удалена'
        ]);
    }

    /**
     * Удалить все завершенные todos
     */
    public function clearCompleted(): JsonResponse
    {
        Todo::where('completed', true)->delete();

        return response()->json([
            'message' => 'Все завершенные задачи удалены'
        ]);
    }
}
