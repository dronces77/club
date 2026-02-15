<form method='POST'>
    @csrf
    <div class='form-group'>
        <label>Nombre</label>
        <input type='text' name='name' class='form-control' required>
    </div>
    <button type='submit' class='btn btn-primary'>Guardar</button>
</form>