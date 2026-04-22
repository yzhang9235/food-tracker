<?php
session_start();

if (!isset($_SESSION['user_id'])) {
	echo "not logged in";
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Recipe Suggestions</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 20px;
			background-color: #f5f5f5;
		}

		h1 {
			text-align: center;
		}

		.top-bar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
		}

		.btn {
			padding: 10px 16px;
			border: none;
			border-radius: 8px;
			cursor: pointer;
			background-color: #28a745;
			color: white;
			font-size: 14px;
		}

		.btn:hover {
			opacity: 0.9;
		}

		#message {
			text-align: center;
			margin: 15px 0;
			font-weight: bold;
		}

		#recipesContainer {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
			gap: 20px;
		}

		.recipe-card {
			background: white;
			border-radius: 12px;
			padding: 15px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		}

		.recipe-card img {
			width: 100%;
			border-radius: 10px;
		}

		.recipe-card h3 {
			margin: 12px 0 8px;
		}

		.recipe-card ul {
			padding-left: 18px;
		}

		.manual-search {
			text-align: center;
			margin-bottom: 20px;
		}

		.manual-search input {
			width: 60%;
			max-width: 400px;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 8px;
		}
	</style>
</head>

<body>

	<div class="top-bar">
		<h1>Recipe Suggestions</h1>
		<button class="btn" id="loadRecipesBtn">Use My Inventory</button>
	</div>

	<div class="manual-search">
		<input type="text" id="manualIngredients" placeholder="Enter ingredients like eggs,tomato,bread">
		<button class="btn" id="searchManualBtn">Search Recipes</button>
	</div>

	<div id="message"></div>
	<div id="recipesContainer"></div>

	<script src="js/recipes.js"></script>
</body>

</html>