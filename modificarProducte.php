<?php
include("includes/head.html");
include("includes/menu.php");
?>

<h3>Modificar producte</h3>
<form id="form-producte">

<label>Nom: </label><br>
<input type="text" name="title" id="title" required><br><br>

<label>Preu: </label><br>
<input type="number" name="price" id="price" required><br><br>

<label>Descripció: </label><br>
<textarea type="text" name="description" id="description" required></textarea><br><br>

<label>Categoria: </label><br>
<input list="categories" name="category" id="category" required>
<datalist id="categories"></datalist><br><br>

<label>URL de la imatge: </label><br>
<input type="url" name="image" id="image" required><br><br>

<label>Rating rate: </label><br>
<input type="number" name="rating-rate" id="rating-rate" required><br><br>

<label>Rating count: </label><br>
<input type="number" name="rating-count" id="rating-count" required><br><br>

<button type="submit">Modificar producte</button>
</form>

<script>

// 1. Obtenir l'id de la URL
const params = new URLSearchParams(window.location.search);
const id = params.get('id');

if (id) {
    // 2. Carregar dades del producte
    fetch(`api/productes.php?id=${id}`)
    .then(response => response.json())
    .then(producte => {
        // 3. Omplir els camps del formulari amb les dades existents
        document.getElementById('title').value = producte.title;
        document.getElementById('price').value = producte.price;
        document.getElementById('description').value = producte.description;
        document.getElementById('category').value = producte.category;
        document.getElementById('image').value = producte.image;
        document.getElementById('rating-rate').value = producte.rating.rate;
        document.getElementById('rating-count').value = producte.rating.count;
    })
    .catch(error => {
        console.error("Error carregant el producte:", error);
    });
} else {
    console.error("No s'ha especificat cap id de producte");
}

// Carregar les categories 
fetch("api/productes.php?categories=all")
.then(response => response.json())
.then(data => {
    const datalist = document.getElementById("categories");
    data.forEach(cat => {
        const opt = document.createElement("option");
        opt.value = cat;
        datalist.appendChild(opt);
    });
})
.catch(error => {
    console.error("Error carregant categories:", error);
});

document.getElementById("form-producte").addEventListener("submit", function(e) {
    e.preventDefault();

    const categoria = document.getElementById("category").value;

    const dades = {
        id: id, // Enviem l'id per saber quin producte actualitzar
        title: document.getElementById("title").value,
        price: parseFloat(document.getElementById("price").value),
        description: document.getElementById("description").value,
        category: document.getElementById("category").value,
        image: document.getElementById("image").value,
        rating: {
            rate: parseFloat(document.getElementById("rating-rate").value),
            count: parseInt(document.getElementById("rating-count").value)
        }
    };

    fetch("api/productes.php", {
        method: "PUT", 
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(dades)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.href = `veureProductesCategoria.php?categoria=${encodeURIComponent(categoria)}`;
        } else {
            document.getElementById("resposta").innerHTML = `<p style="color: red;">${data.error}</p>`;
        }
    })
    .catch(error => {
        console.error("Error enviant dades:", error);
        document.getElementById("resposta").innerHTML = `<p style="color: red;">Error inesperat</p>`;
    });

});
</script>

<?php
include("includes/foot.html");
?>
