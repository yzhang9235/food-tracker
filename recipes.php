<?php require_once "backend/auth_check.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodTracker | Recipe Suggestions</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
  
  <style>
    .recipe-page {
      padding: 7rem 5% 4rem;
      min-height: 100vh;
      background: var(--cream);
    }

    .recipe-header {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
      margin-bottom: 2rem;
    }

    .recipe-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 4vw, 3rem);
      line-height: 1.12;
      color: var(--text);
    }

    .recipe-sub {
      color: var(--muted);
      font-size: 0.98rem;
      margin-top: 0.6rem;
      max-width: 680px;
      line-height: 1.7;
    }

    .recipe-controls {
      background: #fff;
      border-radius: 20px;
      padding: 1.6rem;
      box-shadow: 0 4px 24px rgba(42,34,25,0.07);
      margin-bottom: 1.5rem;
    }

    .control-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.15rem;
      margin-bottom: 1rem;
      color: var(--text);
    }

    .control-row {
      display: grid;
      grid-template-columns: 1fr auto auto;
      gap: 0.9rem;
      align-items: center;
    }

    .control-row input {
      padding: 0.85rem 1rem;
      border: 1.5px solid var(--warm);
      border-radius: 12px;
      background: var(--cream);
      font-family: 'DM Sans', sans-serif;
      font-size: 0.95rem;
      color: var(--text);
      outline: none;
    }

    .control-row input:focus {
      border-color: var(--sage);
    }

    #message {
      margin: 1rem 0 1.25rem;
      color: var(--sage-dk);
      font-size: 0.92rem;
      min-height: 1.2rem;
    }

    .recipes-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.4rem;
    }

    .recipe-card {
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(42,34,25,0.07);
      transition: transform .2s, box-shadow .2s;
      display: flex;
      flex-direction: column;
    }

    .recipe-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 36px rgba(42,34,25,0.12);
    }

    .recipe-card img {
      width: 100%;
      height: 210px;
      object-fit: cover;
      display: block;
      background: var(--warm);
    }

    .recipe-card-body {
      padding: 1.2rem 1.2rem 1.35rem;
    }

    .recipe-card h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.15rem;
      color: var(--text);
      margin-bottom: 0.8rem;
      line-height: 1.3;
    }

    .recipe-meta {
      display: flex;
      gap: 0.6rem;
      flex-wrap: wrap;
      margin-bottom: 1rem;
    }

    .recipe-pill {
      display: inline-block;
      padding: 0.26rem 0.7rem;
      border-radius: 999px;
      font-size: 0.76rem;
      font-weight: 500;
      background: var(--warm);
      color: var(--sage-dk);
    }

    .ingredient-block {
      margin-top: 0.9rem;
    }

    .ingredient-label {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--muted);
      margin-bottom: 0.45rem;
    }

    .ingredient-list {
      margin: 0;
      padding-left: 1.1rem;
      color: var(--text);
      font-size: 0.92rem;
      line-height: 1.65;
    }

    .ingredient-list li {
      margin-bottom: 0.18rem;
    }

    .empty-state {
      background: #fff;
      border-radius: 20px;
      padding: 2.5rem 1.5rem;
      text-align: center;
      color: var(--muted);
      box-shadow: 0 4px 24px rgba(42,34,25,0.07);
    }

    @media (max-width: 768px) {
      .recipe-page {
        padding-top: 6rem;
      }

      .control-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <nav>
    <a class="nav-logo" href="index.php">Food<span>Tracker</span></a>
    <div class="nav-links">
        <a href="inventory.php">My Inventory</a>
        <a href="recipes.php">Recipes</a>
        <a href="backend/logout.php" class="btn btn-nav">Sign out</a>
    </div>
  </nav>

  <main class="recipe-page">
    <div class="recipe-header">
      <div>
        <span class="section-tag">Recipe ideas</span>
        <h1 class="recipe-title">Cook smarter with what you already have.</h1>
        <p class="recipe-sub">
          Generate recipe suggestions from your inventory, or enter ingredients manually to explore meal ideas before shopping.
        </p>
      </div>
    </div>

    <section class="recipe-controls">
      <h2 class="control-title">Find recipes</h2>

      <div class="form-actions" style="margin-bottom: 1rem;">
        <button class="btn btn-primary" id="loadRecipesBtn">Use my inventory</button>
      </div>

      <div class="control-row">
        <input
          type="text"
          id="manualIngredients"
          placeholder="Try: egg, milk, spinach, bread"
        />
        <button class="btn btn-secondary" id="searchManualBtn">Search manually</button>
      </div>

      <div id="message"></div>
    </section>

    <section id="recipesContainer" class="recipes-grid">
      <div class="empty-state">
        Click <strong>Use my inventory</strong> or enter ingredients above to get recipe suggestions.
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const loadRecipesBtn = document.getElementById('loadRecipesBtn');
      const searchManualBtn = document.getElementById('searchManualBtn');
      const manualIngredients = document.getElementById('manualIngredients');
      const messageDiv = document.getElementById('message');
      const recipesContainer = document.getElementById('recipesContainer');

      async function loadRecipes(url) {
        messageDiv.textContent = 'Loading recipes...';
        recipesContainer.innerHTML = '';

        try {
          const response = await fetch(url, { credentials: 'include' });
          const text = await response.text();

          let data;
          try {
            data = JSON.parse(text);
          } catch (jsonError) {
            console.error('Server did not return JSON:', text);
            messageDiv.textContent = 'Server returned an invalid response.';
            return;
          }

          if (!data.success) {
            messageDiv.textContent = data.message || 'Could not load recipes.';
            recipesContainer.innerHTML = `
              <div class="empty-state">
                ${data.message || 'Could not load recipes.'}
              </div>
            `;
            return;
          }

          if (!data.recipes || data.recipes.length === 0) {
            messageDiv.textContent = 'No recipes found.';
            recipesContainer.innerHTML = `
              <div class="empty-state">No recipes found for those ingredients.</div>
            `;
            return;
          }

          messageDiv.textContent = `Found ${data.recipes.length} recipe(s).`;
          recipesContainer.innerHTML = '';

          data.recipes.forEach((recipe) => {
            const usedIngredients = recipe.usedIngredients || [];
            const missedIngredients = recipe.missedIngredients || [];

            const card = document.createElement('article');
            card.className = 'recipe-card';

            card.innerHTML = `
              <img src="${recipe.image}" alt="${recipe.title}">
              <div class="recipe-card-body">
                <h3>${recipe.title}</h3>

                <div class="recipe-meta">
                  <span class="recipe-pill">Used: ${usedIngredients.length}</span>
                  <span class="recipe-pill">Missing: ${missedIngredients.length}</span>
                </div>

                <div class="ingredient-block">
                  <div class="ingredient-label">Used Ingredients</div>
                  <ul class="ingredient-list">
                    ${usedIngredients.length
                      ? usedIngredients.map((item) => `<li>${item.name}</li>`).join('')
                      : '<li>None listed</li>'}
                  </ul>
                </div>

                <div class="ingredient-block">
                  <div class="ingredient-label">Missing Ingredients</div>
                  <ul class="ingredient-list">
                    ${missedIngredients.length
                      ? missedIngredients.map((item) => `<li>${item.name}</li>`).join('')
                      : '<li>None</li>'}
                  </ul>
                </div>
              </div>
            `;

            recipesContainer.appendChild(card);
          });
        } catch (error) {
          console.error(error);
          messageDiv.textContent = 'Something went wrong.';
          recipesContainer.innerHTML = `
            <div class="empty-state">Something went wrong while loading recipes.</div>
          `;
        }
      }

      loadRecipesBtn.addEventListener('click', () => {
        loadRecipes('api/get_recipe_suggestions.php');
      });

      searchManualBtn.addEventListener('click', () => {
        const ingredients = manualIngredients.value.trim();

        if (!ingredients) {
          messageDiv.textContent = 'Please enter ingredients first.';
          return;
        }

        loadRecipes(`api/get_recipe_suggestions.php?ingredients=${encodeURIComponent(ingredients)}`);
      });
    });
  </script>
</body>
</html>
