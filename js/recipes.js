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
      const response = await fetch(url);
      const data = await response.json();

      if (!data.success) {
        messageDiv.textContent = data.message || 'Could not load recipes.';
        return;
      }

      if (!data.recipes || data.recipes.length === 0) {
        messageDiv.textContent = 'No recipes found.';
        return;
      }

      messageDiv.textContent = `Found ${data.recipes.length} recipe(s).`;

      data.recipes.forEach((recipe) => {
        const usedIngredients = recipe.usedIngredients || [];
        const missedIngredients = recipe.missedIngredients || [];

        const card = document.createElement('div');
        card.className = 'recipe-card';

        card.innerHTML = `
                    <img src="${recipe.image}" alt="${recipe.title}">
                    <h3>${recipe.title}</h3>
                    <p><strong>Used Ingredients:</strong> ${usedIngredients.length}</p>
                    <ul>
                        ${usedIngredients.map((item) => `<li>${item.name}</li>`).join('')}
                    </ul>
                    <p><strong>Missing Ingredients:</strong> ${missedIngredients.length}</p>
                    <ul>
                        ${missedIngredients.map((item) => `<li>${item.name}</li>`).join('')}
                    </ul>
                `;

        recipesContainer.appendChild(card);
      });
    } catch (error) {
      console.error(error);
      messageDiv.textContent = 'Something went wrong.';
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

    loadRecipes(
      `api/get_recipe_suggestions.php?ingredients=${encodeURIComponent(ingredients)}`,
    );
  });
});
