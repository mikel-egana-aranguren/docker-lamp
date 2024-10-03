<form id="item_add_form" action="procesar_add_item.php" method="POST">
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" required>
    
    <label for="anio">Año:</label>
    <input type="number" id="anio" name="anio" required>
    
    <label for="director">Director:</label> <input type="text" id="director" name="director" required>  
    <label for="genero">Género:</label>
<input type="text" id="genero" name="genero" required>

<label for="descripcion">Descripción:</label>
<textarea id="descripcion" name="descripcion" required></textarea>

<input id="item_add_submit" type="submit" value="Añadir Ítem">

