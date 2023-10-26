const addCategoryButton = document.getElementById('add-tag')
const categoriesContainer = document.getElementById('tags-container')

function createRemoveButton() {
	const removeButton = document.createElement('button')
	removeButton.type = 'button'
	removeButton.className = 'remove-btn remove-tag-row'

	// Ajoute un SVG en tant que contenu HTML du bouton (une icône de poubelle ici)
	removeButton.innerHTML = '<svg class="remove-btn" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="remove-btn" d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>'

	return removeButton
}

addCategoryButton.addEventListener('click', function() {
	const prototype = categoriesContainer.getAttribute('data-prototype')
	const index = categoriesContainer.children.length

	const newForm = prototype.replace(/__name__/g, index)
	const div = document.createElement('div')
	div.className = 'tag-row' // Ajoute la classe à la nouvelle div
	div.innerHTML = newForm

	const hr = document.createElement('hr')

	// Ajoute le nouveau formulaire et le bouton Supprimer à la div
	const removeButton = createRemoveButton()
	div.appendChild(removeButton)
	categoriesContainer.appendChild(div)
})

// Ajoute un écouteur d'événement pour gérer la suppression des lignes
categoriesContainer.addEventListener('click', function(event) {
	if (event.target.classList.contains('remove-btn')) {
		event.target.closest('.tag-row').remove()
	}
})
