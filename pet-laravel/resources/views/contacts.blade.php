@extends('layouts.main')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h2 class="mb-0">Змейка</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>Счет:</strong> <span id="score">0</span>
                        </div>
                        <div>
                            <strong>Рекорд:</strong> <span id="highScore">0</span>
                        </div>
                        <div id="gameOver" class="text-danger" style="display: none;">
                            <strong>Игра окончена!</strong>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <canvas id="snakeCanvas" width="400" height="400" style="border: 2px solid #333; background: #f0f0f0;"></canvas>
                    </div>
                    <div class="text-center mb-3">
                        <button id="startBtn" class="btn btn-success me-2">Старт</button>
                        <button id="pauseBtn" class="btn btn-warning me-2" disabled>Пауза</button>
                        <button id="resetBtn" class="btn btn-danger">Сброс</button>
                    </div>
                    <div class="alert alert-info">
                        <strong>Управление:</strong><br>
                        ← → ↑ ↓ - Движение змейки<br>
                        Space - Пауза/Продолжить<br>
                        Цель: Собирайте еду, чтобы расти и набирать очки!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #snakeCanvas {
        display: block;
        margin: 0 auto;
    }
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
(function() {
    const canvas = document.getElementById('snakeCanvas');
    const ctx = canvas.getContext('2d');
    const scoreElement = document.getElementById('score');
    const highScoreElement = document.getElementById('highScore');
    const gameOverElement = document.getElementById('gameOver');
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const resetBtn = document.getElementById('resetBtn');

    const gridSize = 20;
    const tileCount = canvas.width / gridSize;

    let snake = [
        {x: 10, y: 10}
    ];
    let food = {};
    let dx = 0;
    let dy = 0;
    let score = 0;
    let gameRunning = false;
    let gamePaused = false;
    let gameLoop = null;

    // Загрузка рекорда из localStorage
    let highScore = localStorage.getItem('snakeHighScore') || 0;
    highScoreElement.textContent = highScore;

    function randomFood() {
        food = {
            x: Math.floor(Math.random() * tileCount),
            y: Math.floor(Math.random() * tileCount)
        };
        // Проверяем, чтобы еда не появилась на змейке
        for (let segment of snake) {
            if (segment.x === food.x && segment.y === food.y) {
                randomFood();
                return;
            }
        }
    }

    function drawGame() {
        clearCanvas();
        drawSnake();
        drawFood();
    }

    function clearCanvas() {
        ctx.fillStyle = '#f0f0f0';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }

    function drawSnake() {
        ctx.fillStyle = '#2ecc71';
        for (let segment of snake) {
            ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize - 2, gridSize - 2);
        }
        // Голова змейки другого цвета
        ctx.fillStyle = '#27ae60';
        ctx.fillRect(snake[0].x * gridSize, snake[0].y * gridSize, gridSize - 2, gridSize - 2);
    }

    function drawFood() {
        ctx.fillStyle = '#e74c3c';
        ctx.fillRect(food.x * gridSize, food.y * gridSize, gridSize - 2, gridSize - 2);
    }

    function moveSnake() {
        const head = {x: snake[0].x + dx, y: snake[0].y + dy};

        // Проверка столкновения со стенами
        if (head.x < 0 || head.x >= tileCount || head.y < 0 || head.y >= tileCount) {
            gameOver();
            return;
        }

        // Проверка столкновения с собой
        for (let segment of snake) {
            if (head.x === segment.x && head.y === segment.y) {
                gameOver();
                return;
            }
        }

        snake.unshift(head);

        // Проверка поедания еды
        if (head.x === food.x && head.y === food.y) {
            score += 10;
            scoreElement.textContent = score;
            randomFood();
            
            // Обновление рекорда
            if (score > highScore) {
                highScore = score;
                highScoreElement.textContent = highScore;
                localStorage.setItem('snakeHighScore', highScore);
            }
        } else {
            snake.pop();
        }
    }

    function gameOver() {
        gameRunning = false;
        gamePaused = false;
        startBtn.disabled = false;
        pauseBtn.disabled = true;
        gameOverElement.style.display = 'block';
        if (gameLoop) {
            clearInterval(gameLoop);
            gameLoop = null;
        }
    }

    function reset() {
        snake = [{x: 10, y: 10}];
        dx = 0;
        dy = 0;
        score = 0;
        scoreElement.textContent = score;
        gameOverElement.style.display = 'none';
        randomFood();
        drawGame();
    }

    function start() {
        if (gameRunning) return;
        
        reset();
        gameRunning = true;
        gamePaused = false;
        startBtn.disabled = true;
        pauseBtn.disabled = false;
        
        gameLoop = setInterval(() => {
            if (!gamePaused) {
                moveSnake();
                drawGame();
            }
        }, 150);
    }

    function pause() {
        if (!gameRunning) return;
        gamePaused = !gamePaused;
        pauseBtn.textContent = gamePaused ? 'Продолжить' : 'Пауза';
    }

    document.addEventListener('keydown', (e) => {
        if (!gameRunning) {
            if (e.key === 'Enter' || e.key === ' ') {
                start();
            }
            return;
        }

        if (e.key === ' ' || e.key === 'Space') {
            pause();
            e.preventDefault();
            return;
        }

        if (gamePaused) return;

        // Предотвращаем движение в противоположном направлении
        if (e.key === 'ArrowLeft' && dx !== 1) {
            dx = -1;
            dy = 0;
        } else if (e.key === 'ArrowRight' && dx !== -1) {
            dx = 1;
            dy = 0;
        } else if (e.key === 'ArrowUp' && dy !== 1) {
            dx = 0;
            dy = -1;
        } else if (e.key === 'ArrowDown' && dy !== -1) {
            dx = 0;
            dy = 1;
        }
        e.preventDefault();
    });

    startBtn.addEventListener('click', start);
    pauseBtn.addEventListener('click', pause);
    resetBtn.addEventListener('click', () => {
        if (gameLoop) {
            clearInterval(gameLoop);
            gameLoop = null;
        }
        gameRunning = false;
        gamePaused = false;
        startBtn.disabled = false;
        pauseBtn.disabled = true;
        pauseBtn.textContent = 'Пауза';
        reset();
    });

    // Инициализация
    randomFood();
    drawGame();
})();
</script>
@endsection
