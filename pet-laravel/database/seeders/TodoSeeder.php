<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $todos = [
            [
                'title' => 'Изучить Laravel основы',
                'completed' => false,
            ],
            [
                'title' => 'Создать todo приложение',
                'completed' => false,
            ],
            [
                'title' => 'Добавить фильтрацию задач',
                'completed' => true,
            ],
            [
                'title' => 'Протестировать функционал',
                'completed' => false,
            ],
            [
                'title' => 'Оптимизировать код',
                'completed' => true,
            ],
        ];

        foreach ($todos as $todo) {
            Todo::create($todo);
        }
    }
}
