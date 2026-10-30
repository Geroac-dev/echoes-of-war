<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      overflow: hidden;
      touch-action: manipulation;
      user-select: none;
    }

    body {
      background: black;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: Arial, sans-serif;
			padding: 20px;
			box-sizing: border-box;
		}

		#game-container {
			display: flex;
			gap: 40px;
			align-items: flex-start;
			max-width: 1200px;
			width: 100%;
			justify-content: center;
		}

		#game-area {
			display: flex;
			flex-direction: column;
			align-items: center;
		}

		canvas {
			border: 1px solid white;
			max-width: 100%;
			max-height: 70vh;
		}

		#score-panel {
			color: white;
			background: rgba(255, 255, 255, 0.1);
			border: 1px solid white;
			padding: 20px;
			min-width: 250px;
			border-radius: 10px;
			max-height: 640px;
			overflow-y: auto;
		}

		#score-panel h3 {
			margin-top: 0;
			text-align: center;
			color: cyan;
			border-bottom: 1px solid rgba(255, 255, 255, 0.3);
			padding-bottom: 10px;
		}

		#current-score {
			text-align: center;
			margin: 20px 0;
		}

		#score-value {
			font-size: 36px;
			font-weight: bold;
			color: yellow;
			display: block;
		}

		#score-label {
			font-size: 14px;
			color: #ccc;
		}

		#game-stats {
			text-align: center;
			font-size: 14px;
			color: #aaa;
		}

		#game-stats span {
			margin: 0 10px;
		}

		#pb-score {
			color: lime;
			font-weight: bold;
		}

		#high-scores {
			margin-top: 20px;
		}

		#high-scores h4 {
			color: lime;
			margin-bottom: 10px;
			text-align: center;
		}

		#scores-table {
			width: 100%;
			border-collapse: collapse;
			font-size: 14px;
		}

		#scores-table th {
			text-align: left;
			padding: 8px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.2);
			color: #aaa;
		}

		#scores-table td {
			padding: 8px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.1);
		}

		#scores-table tr:last-child td {
			border-bottom: none;
		}

		.score-number {
			color: cyan;
			font-weight: bold;
		}

		.score-name {
			color: white;
		}

		.score-points {
			color: yellow;
			text-align: right;
			font-weight: bold;
		}

		#game-over-message {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.95);
			display: none;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			z-index: 1000;
			padding: 20px;
			box-sizing: border-box;
		}

		#game-over-message h2 {
			color: red;
			margin-top: 0;
			font-size: 32px;
		}

		#final-score-display {
			font-size: 48px;
			color: yellow;
			margin: 20px 0;
			font-weight: bold;
		}

		#save-score-btn,
		#play-again-btn,
		#menu-btn {
			background: green;
			color: white;
			border: none;
			padding: 15px 30px;
			border-radius: 10px;
			cursor: pointer;
			font-size: 18px;
			margin: 10px;
			width: 250px;
			max-width: 90%;
		}

		#play-again-btn {
			background: #007bff;
		}

		#menu-btn {
			background: #6c757d;
		}

		#save-score-btn:hover {
			background: darkgreen;
		}

		#play-again-btn:hover {
			background: #0056b3;
		}

		#menu-btn:hover {
			background: #545b62;
		}

		/* Controles para móvil */
		#mobile-controls {
			display: none;
			width: 100%;
			max-width: 320px;
			touch-action: manipulation;
		}

		.controls-row {
			display: flex;
			justify-content: center;
			margin: 10px 0;
		}

		.control-btn {
			background: rgba(255, 255, 255, 0.2);
			border: 2px solid white;
			color: white;
			font-size: 24px;
			width: 60px;
			height: 60px;
			margin: 0 10px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			touch-action: manipulation;
			-webkit-tap-highlight-color: transparent;
		}

		.control-btn:active {
			background: rgba(255, 255, 255, 0.4);
		}

		#rotate-btn {
			background: rgba(0, 255, 255, 0.3);
		}

		#restart-btn {
			background: rgba(255, 0, 0, 0.3);
			font-size: 18px;
			width: auto;
			padding: 10px 20px;
			border-radius: 10px;
		}

		/* En móvil, ocultar el panel de puntuaciones y mostrar solo el juego */
		@media (max-width: 768px) {
			#restart-btn {
				display: none;
			}

			body {
				padding: 10px;
				overflow: auto;
			}

			#game-container {
				flex-direction: column;
				align-items: center;
				gap: 20px;
				height: auto;
				overflow: visible;
			}

			#game-area {
				order: 1;
			}

			#score-panel {
				display: none;
				/* Ocultar panel en móvil por defecto */
				order: 3;
				width: 320px;
				max-height: none;
				margin-top: 20px;
			}

			#mobile-controls {
				display: block;
				order: 2;
			}

			.control-btn {
				width: 50px;
				height: 50px;
				font-size: 20px;
			}

			canvas {
				width: 320px;
				height: 640px;
			}

			/* Botón para mostrar/ocultar puntuaciones en móvil */
			#toggle-scores-btn {
				display: block;
				background: rgba(0, 255, 255, 0.3);
				color: white;
				border: 1px solid cyan;
				padding: 10px 15px;
				border-radius: 5px;
				margin: 10px auto;
				cursor: pointer;
				font-size: 14px;
			}

			/* Panel de puntuaciones móvil (alternativo) */
			#mobile-score-info {
				display: block;
				color: white;
				text-align: center;
				order: 2;
			}

			#mobile-score-info div {
				margin: 5px 0;
			}
		}

		@media (max-width: 375px) {
			canvas {
				width: 280px;
				height: 560px;
			}

			#score-panel {
				width: 280px;
			}

			.control-btn {
				width: 45px;
				height: 45px;
				font-size: 18px;
				margin: 0 5px;
			}
		}

		@media (max-height: 700px) and (orientation: portrait) {
			canvas {
				height: 500px;
				width: 250px;
			}

			#game-area {
				margin-top: 10px;
			}
		}

		@media (max-height: 600px) and (orientation: portrait) {
			canvas {
				height: 400px;
				width: 200px;
			}

			.control-btn {
				width: 40px;
				height: 40px;
				font-size: 16px;
				margin: 0 5px;
			}
		}
	</style>
</head>

<body>
	<div id="game-container">
		<div id="game-area">
			<canvas width="320" height="640" id="game"></canvas>

			<!-- Información de puntuación móvil (solo visible en móvil) -->
			<div id="mobile-score-info" style="display: none;">
				<div>Puntuación: <span id="mobile-score-value">0</span> | Líneas: <span id="mobile-lines-count">0</span>
					|
					Nivel: <span id="mobile-level-count">1</span> | PB: <span
						id="mobile-pb-score">{{ Auth::user()->score ?? 0 }}</span></div>
				<button id="toggle-scores-btn">Ver Puntuaciones</button>
			</div>

			<div id="mobile-controls">
				<div class="controls-row">
					<button class="control-btn" id="left-btn">←</button>
					<button class="control-btn" id="rotate-btn">↻</button>
					<button class="control-btn" id="right-btn">→</button>
					<button class="control-btn" id="restart-btn">Reiniciar</button>
				</div>
			</div>
		</div>

		<div id="score-panel">
			<h3>TETRIS SCORES</h3>

			<div id="current-score">
				<span id="score-label">PUNTUACIÓN ACTUAL</span>
				<span id="score-value">0</span>
			</div>

			<div id="game-stats">
				<span>Líneas: <span id="lines-count">0</span></span>
				<span>Nivel: <span id="level-count">1</span></span>
				<span>PB: <span id="pb-score">{{ Auth::user()->score ?? 0 }}</span></span>
			</div>

			<div id="high-scores">
				<h4>MEJORES PUNTUACIONES</h4>
				<table id="scores-table">
					<thead>
						<tr>
							<th>#</th>
							<th>JUGADOR</th>
							<th>PUNTOS</th>
						</tr>
					</thead>
					<tbody id="scores-body">
						<!-- Las puntuaciones se cargarán dinámicamente -->
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div id="game-over-message">
		<h2>GAME OVER!</h2>
		<p>Tu puntuación final:</p>
		<div id="final-score-display">0</div>
		<button id="save-score-btn">GUARDAR PUNTUACIÓN</button>
		<button id="play-again-btn">JUGAR OTRA VEZ</button>
		<button id="menu-btn">MENÚ PRINCIPAL</button>
	</div>

	<script>
		// Variables del juego
		let score = 0;
		let linesCleared = 0;
		let level = 1;
		let gameSpeed = 35;

		// Elementos del DOM
		const scoreElement = document.getElementById('score-value');
		const linesElement = document.getElementById('lines-count');
		const levelElement = document.getElementById('level-count');
		const pbElement = document.getElementById('pb-score');

		// Elementos móviles
		const mobileScoreElement = document.getElementById('mobile-score-value');
		const mobileLinesElement = document.getElementById('mobile-lines-count');
		const mobileLevelElement = document.getElementById('mobile-level-count');
		const mobilePbElement = document.getElementById('mobile-pb-score');
		const mobileScoreInfo = document.getElementById('mobile-score-info');
		const scorePanel = document.getElementById('score-panel');
		const toggleScoresBtn = document.getElementById('toggle-scores-btn');

		const scoresBody = document.getElementById('scores-body');
		const gameOverMessage = document.getElementById('game-over-message');
		const finalScoreDisplay = document.getElementById('final-score-display');
		const saveScoreBtn = document.getElementById('save-score-btn');
		const playAgainBtn = document.getElementById('play-again-btn');
		const menuBtn = document.getElementById('menu-btn');

		// Variables del juego original
		let count = 0;
		let tetromino = null;
		let rAF = null;
		let gameOver = false;

		// Detectar si es móvil
		function isMobileDevice() {
			return window.innerWidth <= 768 || 'ontouchstart' in window;
		}

		// Configurar visibilidad para móvil
		function setupMobileLayout() {
			if (isMobileDevice()) {
				mobileScoreInfo.style.display = 'block';
				scorePanel.style.display = 'none';

				// Actualizar información móvil
				updateMobileScore();
			} else {
				mobileScoreInfo.style.display = 'none';
				scorePanel.style.display = 'block';
			}
		}

		// Actualizar puntuación en pantalla
		function updateScore() {
			scoreElement.textContent = score;
			linesElement.textContent = linesCleared;
			levelElement.textContent = level;

			if (isMobileDevice()) {
				updateMobileScore();
			}
		}

		// Actualizar puntuación móvil
		function updateMobileScore() {
			mobileScoreElement.textContent = score;
			mobileLinesElement.textContent = linesCleared;
			mobileLevelElement.textContent = level;
		}

		// Actualizar el PB visualmente
		function updatePB(newScore) {
			pbElement.textContent = newScore;
			if (isMobileDevice()) {
				mobilePbElement.textContent = newScore;
			}
		}

		// Cargar puntuaciones desde el servidor
		function loadScores() {
			const scoresData = {!! json_encode($scores) !!};

			// Limpiar tabla
			scoresBody.innerHTML = '';

			// Agregar puntuaciones
			scoresData.forEach((scoreItem, index) => {
				const row = document.createElement('tr');
				row.innerHTML = `
          <td class="score-number">${index + 1}</td>
          <td class="score-name">${scoreItem.name}</td>
          <td class="score-points">${scoreItem.score}</td>
        `;
				scoresBody.appendChild(row);
			});
		}

		// Calcular puntuación cuando se completan líneas
		function addScore(lines) {
			const pointsPerLine = [0, 100, 300, 500, 800];
			const linePoints = pointsPerLine[lines] || 0;
			const totalPoints = linePoints * level;
			score += totalPoints;

			linesCleared += lines;
			level = Math.floor(linesCleared / 10) + 1;
			gameSpeed = Math.max(10, 35 - Math.floor(linesCleared / 10) * 3);

			updateScore();
		}

		// Mostrar mensaje de Game Over
		function showGameOver() {
			cancelAnimationFrame(rAF);
			gameOver = true;
			rAF = null;

			finalScoreDisplay.textContent = score;
			gameOverMessage.style.display = 'flex';

			// Mostrar botón de reinicio en móvil
			if (isMobileDevice()) {
				document.getElementById('restart-btn').textContent = 'Jugar otra vez';
			}
		}

		// Guardar puntuación en la base de datos
		function saveScoreToDatabase() {
			if (score <= 0) {
				alert('No hay puntuación para guardar');
				return;
			}

			const scoreData = {
				score: score,
				_token: '{{ csrf_token() }}'
			};

			fetch('/update-score', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
				body: JSON.stringify(scoreData)
			})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						alert('¡Puntuación guardada con éxito!');
						updatePB(data.newScore);
						loadScores();
					} else {
						alert('Error al guardar la puntuación: ' + data.message);
					}
				})
				.catch(error => {
					console.error('Error:', error);
					alert('Error al conectar con el servidor');
				});
		}

		// Reiniciar juego
		function restartGame() {
			// Resetear variables
			score = 0;
			linesCleared = 0;
			level = 1;
			gameSpeed = 35;
			count = 0;
			gameOver = false;

			// Resetear playfield
			for (let row = -2; row < 20; row++) {
				playfield[row] = [];
				for (let col = 0; col < 10; col++) {
					playfield[row][col] = 0;
				}
			}

			// Resetear secuencia de tetrominos
			tetrominoSequence.length = 0;
			tetromino = getNextTetromino();

			// Ocultar mensaje de Game Over
			gameOverMessage.style.display = 'none';

			// Actualizar puntuación
			updateScore();

			// Cambiar texto del botón
			document.getElementById('restart-btn').textContent = 'Reiniciar';

			// Reiniciar loop del juego
			if (!rAF) {
				rAF = requestAnimationFrame(loop);
			}
		}

		// Volver al menú principal
		function goToMenu() {
			window.location.href = '/';
		}

		// Alternar visibilidad de puntuaciones en móvil
		function toggleScores() {
			if (scorePanel.style.display === 'none') {
				scorePanel.style.display = 'block';
				toggleScoresBtn.textContent = 'Ocultar Puntuaciones';
			} else {
				scorePanel.style.display = 'none';
				toggleScoresBtn.textContent = 'Ver Puntuaciones';
			}
		}

		// Función original del juego
		function getRandomInt(min, max) {
			min = Math.ceil(min);
			max = Math.floor(max);
			return Math.floor(Math.random() * (max - min + 1)) + min;
		}

		function generateSequence() {
			const sequence = ['I', 'J', 'L', 'O', 'S', 'T', 'Z'];
			while (sequence.length) {
				const rand = getRandomInt(0, sequence.length - 1);
				const name = sequence.splice(rand, 1)[0];
				tetrominoSequence.push(name);
			}
		}

		function getNextTetromino() {
			if (tetrominoSequence.length === 0) {
				generateSequence();
			}
			const name = tetrominoSequence.pop();
			const matrix = tetrominos[name];
			const col = playfield[0].length / 2 - Math.ceil(matrix[0].length / 2);
			const row = name === 'I' ? -1 : -2;
			return {
				name: name,
				matrix: matrix,
				row: row,
				col: col
			};
		}

		function rotate(matrix) {
			const N = matrix.length - 1;
			const result = matrix.map((row, i) =>
				row.map((val, j) => matrix[N - j][i])
			);
			return result;
		}

		function isValidMove(matrix, cellRow, cellCol) {
			for (let row = 0; row < matrix.length; row++) {
				for (let col = 0; col < matrix[row].length; col++) {
					if (matrix[row][col] && (
						cellCol + col < 0 ||
						cellCol + col >= playfield[0].length ||
						cellRow + row >= playfield.length ||
						playfield[cellRow + row][cellCol + col])
					) {
						return false;
					}
				}
			}
			return true;
		}

		function placeTetromino() {
			for (let row = 0; row < tetromino.matrix.length; row++) {
				for (let col = 0; col < tetromino.matrix[row].length; col++) {
					if (tetromino.matrix[row][col]) {
						if (tetromino.row + row < 0) {
							showGameOver();
							return;
						}
						playfield[tetromino.row + row][tetromino.col + col] = tetromino.name;
					}
				}
			}

			let linesCompleted = 0;
			for (let row = playfield.length - 1; row >= 0;) {
				if (playfield[row].every(cell => !!cell)) {
					linesCompleted++;
					for (let r = row; r >= 0; r--) {
						for (let c = 0; c < playfield[r].length; c++) {
							playfield[r][c] = playfield[r - 1] ? playfield[r - 1][c] : 0;
						}
					}
				} else {
					row--;
				}
			}

			if (linesCompleted > 0) {
				addScore(linesCompleted);
			}

			tetromino = getNextTetromino();
		}

		let touchStartX = 0;
		let touchStartY = 0;
		const minSwipeDistance = 30;

		const canvas = document.getElementById('game');
		const context = canvas.getContext('2d');
		const grid = 32;
		const tetrominoSequence = [];
		const playfield = [];

		for (let row = -2; row < 20; row++) {
			playfield[row] = [];
			for (let col = 0; col < 10; col++) {
				playfield[row][col] = 0;
			}
		}

		const tetrominos = {
			'I': [[0, 0, 0, 0], [1, 1, 1, 1], [0, 0, 0, 0], [0, 0, 0, 0]],
			'J': [[1, 0, 0], [1, 1, 1], [0, 0, 0]],
			'L': [[0, 0, 1], [1, 1, 1], [0, 0, 0]],
			'O': [[1, 1], [1, 1]],
			'S': [[0, 1, 1], [1, 1, 0], [0, 0, 0]],
			'Z': [[1, 1, 0], [0, 1, 1], [0, 0, 0]],
			'T': [[0, 1, 0], [1, 1, 1], [0, 0, 0]]
		};

		const colors = {
			'I': 'cyan',
			'O': 'yellow',
			'T': 'purple',
			'S': 'green',
			'Z': 'red',
			'J': 'blue',
			'L': 'orange'
		};

		// Inicializar el juego
		function initGame() {
			tetromino = getNextTetromino();
			updateScore();
			setupMobileLayout();

			// Configurar controles móviles
			if (isMobileDevice()) {
				document.getElementById('mobile-controls').style.display = 'block';
			}

			loadScores();
			rAF = requestAnimationFrame(loop);
		}

		function loop() {
			if (gameOver) return;

			rAF = requestAnimationFrame(loop);
			context.clearRect(0, 0, canvas.width, canvas.height);

			for (let row = 0; row < 20; row++) {
				for (let col = 0; col < 10; col++) {
					if (playfield[row][col]) {
						const name = playfield[row][col];
						context.fillStyle = colors[name];
						context.fillRect(col * grid, row * grid, grid - 1, grid - 1);
					}
				}
			}

			if (tetromino) {
				if (++count > gameSpeed) {
					tetromino.row++;
					count = 0;
					if (!isValidMove(tetromino.matrix, tetromino.row, tetromino.col)) {
						tetromino.row--;
						placeTetromino();
					}
				}

				context.fillStyle = colors[tetromino.name];
				for (let row = 0; row < tetromino.matrix.length; row++) {
					for (let col = 0; col < tetromino.matrix[row].length; col++) {
						if (tetromino.matrix[row][col]) {
							context.fillRect((tetromino.col + col) * grid, (tetromino.row + row) * grid, grid - 1, grid - 1);
						}
					}
				}
			}
		}

		// Event Listeners
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				goToMenu();
			}

			if (gameOver) {
				if (e.key === ' ' || e.key === 'Spacebar' || e.key === 'Enter') {
					restartGame();
				}
				return;
			}

			if (e.key === 'ArrowLeft' || e.key === 'ArrowRight' || e.key === 'a' || e.key === 'd') {
				const col = (e.key === 'ArrowLeft' || e.key === 'a') ? tetromino.col - 1 : tetromino.col + 1;
				if (isValidMove(tetromino.matrix, tetromino.row, col)) {
					tetromino.col = col;
				}
			}

			if (e.key === 'ArrowUp' || e.key === 'w') {
				const matrix = rotate(tetromino.matrix);
				if (isValidMove(matrix, tetromino.row, tetromino.col)) {
					tetromino.matrix = matrix;
				}
			}

			if (e.key === 'ArrowDown' || e.key === 's') {
				const row = tetromino.row + 1;
				if (!isValidMove(tetromino.matrix, row, tetromino.col)) {
					tetromino.row = row - 1;
					placeTetromino();
					return;
				}
				tetromino.row = row;
			}

			if (e.key === ' ' || e.key === 'Spacebar') {
				while (isValidMove(tetromino.matrix, tetromino.row + 1, tetromino.col)) {
					tetromino.row++;
				}
				placeTetromino();
			}
		});

		// Controles móviles
		const setupMobileControl = (btnId, action) => {
			const btn = document.getElementById(btnId);
			btn.addEventListener('touchstart', (e) => {
				e.preventDefault();
				action();
			});
			btn.addEventListener('click', (e) => {
				action();
			});
		};

		setupMobileControl('left-btn', () => {
			if (gameOver) return;
			const col = tetromino.col - 1;
			if (isValidMove(tetromino.matrix, tetromino.row, col)) {
				tetromino.col = col;
			}
		});

		setupMobileControl('right-btn', () => {
			if (gameOver) return;
			const col = tetromino.col + 1;
			if (isValidMove(tetromino.matrix, tetromino.row, col)) {
				tetromino.col = col;
			}
		});

		setupMobileControl('rotate-btn', () => {
			if (gameOver) return;
			const matrix = rotate(tetromino.matrix);
			if (isValidMove(matrix, tetromino.row, tetromino.col)) {
				tetromino.matrix = matrix;
			}
		});

		document.getElementById('restart-btn').addEventListener('click', restartGame);
		document.getElementById('restart-btn').addEventListener('touchstart', (e) => {
			e.preventDefault();
			restartGame();
		});

		// Botón para mostrar/ocultar puntuaciones en móvil
		if (toggleScoresBtn) {
			toggleScoresBtn.addEventListener('click', toggleScores);
		}

		saveScoreBtn.addEventListener('click', saveScoreToDatabase);
		playAgainBtn.addEventListener('click', restartGame);
		menuBtn.addEventListener('click', goToMenu);

		// Control por gestos
		canvas.addEventListener('touchstart', function (e) {
			e.preventDefault();
			const touch = e.touches[0];
			touchStartX = touch.clientX;
			touchStartY = touch.clientY;
		});

		canvas.addEventListener('touchmove', function (e) {
			e.preventDefault();
		});

		canvas.addEventListener('touchend', function (e) {
			e.preventDefault();
			if (gameOver) return;

			const touch = e.changedTouches[0];
			const deltaX = touch.clientX - touchStartX;
			const deltaY = touch.clientY - touchStartY;

			if (Math.abs(deltaX) > minSwipeDistance || Math.abs(deltaY) > minSwipeDistance) {
				if (Math.abs(deltaX) > Math.abs(deltaY)) {
					if (deltaX > 0) {
						const col = tetromino.col + 1;
						if (isValidMove(tetromino.matrix, tetromino.row, col)) {
							tetromino.col = col;
						}
					} else {
						const col = tetromino.col - 1;
						if (isValidMove(tetromino.matrix, tetromino.row, col)) {
							tetromino.col = col;
						}
					}
				} else {
					if (deltaY > 0) {
						const row = tetromino.row + 1;
						if (!isValidMove(tetromino.matrix, row, tetromino.col)) {
							tetromino.row = row - 1;
							placeTetromino();
							return;
						}
						tetromino.row = row;
					} else {
						const matrix = rotate(tetromino.matrix);
						if (isValidMove(matrix, tetromino.row, tetromino.col)) {
							tetromino.matrix = matrix;
						}
					}
				}
			}
		});

		// Doble toque para caída instantánea
		let lastTap = 0;
		canvas.addEventListener('touchstart', function (e) {
			e.preventDefault();
			const currentTime = new Date().getTime();
			const tapLength = currentTime - lastTap;

			if (tapLength < 300 && tapLength > 0) {
				while (isValidMove(tetromino.matrix, tetromino.row + 1, tetromino.col)) {
					tetromino.row++;
				}
				placeTetromino();
			}
			lastTap = currentTime;
		});

		// Ajustar layout cuando cambia el tamaño de la ventana
		window.addEventListener('resize', setupMobileLayout);

		// Iniciar el juego cuando se carga la página
		window.addEventListener('load', initGame);
	</script>
</body>

</html>