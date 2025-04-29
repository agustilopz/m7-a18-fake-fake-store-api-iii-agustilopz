<?php
include("includes/head.html");
include("includes/menu.php");
include("includes/errorHandler.php");
?>

<div class="container">
<h3>Modificar producte</h3>
<form id="form-producte">

<label>Nom: </label><br>
<input type="text" name="title" id="title" required><br><br>

<label>Preu: </label><br>
<input type="number" step="0.01" min="0" name="price" id="price" required><br><br>

<label>Descripció: </label><br>
<textarea type="text" name="description" id="description" required></textarea><br><br>

<label>Categoria: </label><br>
<input list="categories" name="category" id="category" required>
<datalist id="categories"></datalist><br><br>

<label>URL de la imatge: </label><br>
<input type="url" min="0" name="image" id="image" required><br><br>

<label>Rating rate: </label><br>
<input type="number" min="0" step="0.01" name="rating-rate" id="rating-rate" required><br><br>

<label>Rating count: </label><br>
<input type="number" min="0" name="rating-count" id="rating-count" required><br><br>

<button type="submit">Modificar producte</button>
</form>

<div id="resposta" style="margin-top:20px;"></div>
</div>

<script>

// 1. Obtenir l'id de la URL
const params = new URLSearchParams(window.location.search);
const id = params.get('id');

let titleAct;
let priceAct;
let descriptionAct;
let categoryAct;
let imageAct;
let rateAct;
let countAct;

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

        // Guardem els valors en variables per utilitzar-los després
        titleAct = product.title;
        priceAct = product.price;
        descriptionAct = product.description;
        categoryAct = product.category;
        imageAct  = product.image;
        rateAct  = producte.rating.rate;
        countAct = product.rating.count;
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
    
    // Guardem els valors passats pel formulari en variables
    let valueTitle = document.getElementById("title").value;
    let valuePrice = parseFloat(document.getElementById("price").value);
    let valueDesc = document.getElementById("description").value;
    let valueCat = document.getElementById("category").value;
    let valueImg = document.getElementById("image").value;
    let valueRate = parseFloat(document.getElementById("rating-rate").value);
    let valueCount = parseInt(document.getElementById("rating-count").value);

        const dades = {
        id: id, // Enviem l'id per saber quin producte actualitzar
        };
        
        // Afegim només els camps modificats
        if (titleAct !== valueTitle && valueTitle !== "") {
        dades.title = valueTitle;
        }
        if (priceAct !== valuePrice && !isNaN(valuePrice)) {
        dades.price = valuePrice;
        }
        if (descriptionAct !== valueDesc && valueDesc !== "") {
        dades.description = valueDesc;
        }
        if (categoryAct !== valueCat && valueCat !== "") {
        dades.category = valueCat;
        }
        if (imageAct !== valueImg && valueImg !== "") {
        dades.image = valueImg;
        }
        if (rateAct !== valueRate && !isNaN(valueRate)) {
        dades.rating = dades.rating || {};  // Inicialitzar si no existeix
        dades.rating.rate = valueRate;
        }
        if (countAct !== valueCount && !isNaN(valueCount)) {
        dades.rating = dades.rating || {};  // Inicialitzar si no existeix
        dades.rating.count = valueCount;
        }
        
    const categoria = document.getElementById("category").value;

    // Segons les dades modificades farem PUT O PATCH
    let updateComplet = Object.keys(dades).length === 8;  // 8 és el nombre total de camps a actualitzar (inclou rating)

    fetch("api/productes.php", {
        method: updateComplet ? "PUT" : "PATCH", 
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
