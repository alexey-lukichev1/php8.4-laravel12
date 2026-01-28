@extends('layouts.main')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="mb-0">Тетрис</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>Счет:</strong> <span id="score">0</span>
                        </div>
                        <div>
                            <strong>Уровень:</strong> <span id="level">1</span>
                        </div>
                        <div>
                            <strong>Линии:</strong> <span id="lines">0</span>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <canvas id="tetris" width="300" height="600" style="border: 2px solid #333; background: #000;"></canvas>
                    </div>
                    <div class="text-center mb-3">
                        <button id="startBtn" class="btn btn-success me-2">Старт</button>
                        <button id="pauseBtn" class="btn btn-warning me-2" disabled>Пауза</button>
                        <button id="resetBtn" class="btn btn-danger">Сброс</button>
                    </div>
                    <div class="alert alert-info">
                        <strong>Управление:</strong><br>
                        ← → - Движение влево/вправо<br>
                        ↓ - Ускорение падения<br>
                        ↑ или Space - Поворот фигуры<br>
                        P - Пауза
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #tetris {
        display: block;
        margin: 0 auto;
    }
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
(function() {
    const canvas = document.getElementById('tetris');
    const ctx = canvas.getContext('2d');
    const scoreElement = document.getElementById('score');
    const levelElement = document.getElementById('level');
    const linesElement = document.getElementById('lines');
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const resetBtn = document.getElementById('resetBtn');

    const COLS = 10;
    const ROWS = 20;
    const BLOCK_SIZE = 30;
    let score = 0;
    let level = 1;
    let lines = 0;
    let gameRunning = false;
    let gamePaused = false;
    let dropTime = 0;
    let lastTime = 0;
    let dropInterval = 1000;

    const board = Array(ROWS).fill(null).map(() => Array(COLS).fill(0));

    const colors = [
        null,
        '#FF0D72', // I
        '#0DC2FF', // O
        '#0DFF72', // T
        '#F538FF', // S
        '#FF8E0D', // Z
        '#FFE138', // J
        '#3877FF'  // L
    ];

    const pieces = [
        null,
        [
            [0, 0, 0, 0],
            [1, 1, 1, 1],
            [0, 0, 0, 0],
            [0, 0, 0, 0]
        ],
        [
            [2, 2],
            [2, 2]
        ],
        [
            [0, 3, 0],
            [3, 3, 3],
            [0, 0, 0]
        ],
        [
            [0, 4, 4],
            [4, 4, 0],
            [0, 0, 0]
        ],
        [
            [5, 5, 0],
            [0, 5, 5],
            [0, 0, 0]
        ],
        [
            [6, 0, 0],
            [6, 6, 6],
            [0, 0, 0]
        ],
        [
            [0, 0, 7],
            [7, 7, 7],
            [0, 0, 0]
        ]
    ];

    let player = {
        pos: {x: 0, y: 0},
        matrix: null,
        score: 0
    };

    function drawMatrix(matrix, offset) {
        matrix.forEach((row, y) => {
            row.forEach((value, x) => {
                if (value !== 0) {
                    ctx.fillStyle = colors[value];
                    ctx.fillRect(x + offset.x, y + offset.y, 1, 1);
                }
            });
        });
    }

    function draw() {
        ctx.fillStyle = '#000';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        drawMatrix(board, {x: 0, y: 0});
        if (player.matrix) {
            drawMatrix(player.matrix, player.pos);
        }
    }

    function merge(board, player) {
        player.matrix.forEach((row, y) => {
            row.forEach((value, x) => {
                if (value !== 0) {
                    board[y + player.pos.y][x + player.pos.x] = value;
                }
            });
        });
    }

    function collide(board, player) {
        const [m, o] = [player.matrix, player.pos];
        for (let y = 0; y < m.length; ++y) {
            for (let x = 0; x < m[y].length; ++x) {
                if (m[y][x] !== 0 &&
                    (board[y + o.y] &&
                     board[y + o.y][x + o.x]) !== 0) {
                    return true;
                }
            }
        }
        return false;
    }

    function rotate(matrix, dir) {
        for (let y = 0; y < matrix.length; ++y) {
            for (let x = 0; x < y; ++x) {
                [matrix[x][y], matrix[y][x]] = [matrix[y][x], matrix[x][y]];
            }
        }
        if (dir > 0) {
            matrix.forEach(row => row.reverse());
        } else {
            matrix.reverse();
        }
    }

    function playerRotate(dir) {
        const pos = player.pos.x;
        let offset = 1;
        rotate(player.matrix, dir);
        while (collide(board, player)) {
            player.pos.x += offset;
            offset = -(offset + (offset > 0 ? 1 : -1));
            if (offset > player.matrix[0].length) {
                rotate(player.matrix, -dir);
                player.pos.x = pos;
                return;
            }
        }
    }

    function playerDrop() {
        player.pos.y++;
        if (collide(board, player)) {
            player.pos.y--;
            merge(board, player);
            playerReset();
            sweep();
            updateScore();
        }
        dropTime = 0;
    }

    function playerMove(dir) {
        player.pos.x += dir;
        if (collide(board, player)) {
            player.pos.x -= dir;
        }
    }

    function playerReset() {
        const pieces = 'ILJOTSZ';
        player.matrix = createPiece(pieces[pieces.length * Math.random() | 0]);
        player.pos.y = 0;
        player.pos.x = (board[0].length / 2 | 0) - (player.matrix[0].length / 2 | 0);
        if (collide(board, player)) {
            gameOver();
        }
    }

    function createPiece(type) {
        if (type === 'I') return pieces[1].map(row => [...row]);
        if (type === 'O') return pieces[2].map(row => [...row]);
        if (type === 'T') return pieces[3].map(row => [...row]);
        if (type === 'S') return pieces[4].map(row => [...row]);
        if (type === 'Z') return pieces[5].map(row => [...row]);
        if (type === 'J') return pieces[6].map(row => [...row]);
        if (type === 'L') return pieces[7].map(row => [...row]);
    }

    function sweep() {
        let rowCount = 0;
        outer: for (let y = board.length - 1; y > 0; --y) {
            for (let x = 0; x < board[y].length; ++x) {
                if (board[y][x] === 0) {
                    continue outer;
                }
            }
            const row = board.splice(y, 1)[0].fill(0);
            board.unshift(row);
            ++y;
            rowCount++;
        }
        if (rowCount > 0) {
            lines += rowCount;
            score += rowCount * 100 * level;
            level = Math.floor(lines / 10) + 1;
            dropInterval = Math.max(100, 1000 - (level - 1) * 50);
        }
    }

    function updateScore() {
        scoreElement.textContent = score;
        levelElement.textContent = level;
        linesElement.textContent = lines;
    }

    function gameOver() {
        gameRunning = false;
        gamePaused = false;
        startBtn.disabled = false;
        pauseBtn.disabled = true;
        alert('Игра окончена! Ваш счет: ' + score);
    }

    function reset() {
        board.forEach(row => row.fill(0));
        score = 0;
        level = 1;
        lines = 0;
        dropInterval = 1000;
        updateScore();
        playerReset();
        draw();
    }

    let dropCounter = 0;
    function update(time = 0) {
        if (!gameRunning || gamePaused) {
            requestAnimationFrame(update);
            return;
        }

        const deltaTime = time - lastTime;
        lastTime = time;
        dropCounter += deltaTime;

        if (dropCounter > dropInterval) {
            playerDrop();
            dropCounter = 0;
        }

        draw();
        requestAnimationFrame(update);
    }

    document.addEventListener('keydown', event => {
        if (!gameRunning || gamePaused) {
            if (event.key === 'p' || event.key === 'P') {
                if (gameRunning) {
                    gamePaused = !gamePaused;
                    pauseBtn.textContent = gamePaused ? 'Продолжить' : 'Пауза';
                }
            }
            return;
        }

        if (event.key === 'ArrowLeft') {
            playerMove(-1);
        } else if (event.key === 'ArrowRight') {
            playerMove(1);
        } else if (event.key === 'ArrowDown') {
            playerDrop();
        } else if (event.key === 'ArrowUp' || event.key === ' ') {
            playerRotate(1);
            event.preventDefault();
        } else if (event.key === 'p' || event.key === 'P') {
            gamePaused = !gamePaused;
            pauseBtn.textContent = gamePaused ? 'Продолжить' : 'Пауза';
        }
    });

    startBtn.addEventListener('click', () => {
        if (!gameRunning) {
            reset();
            gameRunning = true;
            gamePaused = false;
            startBtn.disabled = true;
            pauseBtn.disabled = false;
            lastTime = 0;
            update();
        }
    });

    pauseBtn.addEventListener('click', () => {
        if (gameRunning) {
            gamePaused = !gamePaused;
            pauseBtn.textContent = gamePaused ? 'Продолжить' : 'Пауза';
            if (!gamePaused) {
                lastTime = performance.now();
                update();
            }
        }
    });

    resetBtn.addEventListener('click', () => {
        gameRunning = false;
        gamePaused = false;
        startBtn.disabled = false;
        pauseBtn.disabled = true;
        pauseBtn.textContent = 'Пауза';
        reset();
    });

    // Инициализация
    ctx.scale(BLOCK_SIZE, BLOCK_SIZE);
    playerReset();
    draw();
})();
</script>
@endsection
