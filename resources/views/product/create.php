<form action="{{ route('product.store') }}" method="POST">
    @csrf

    <label>Nom du produit:</label>
    <input type="text" name="name" required>

    <label>Description:</label>
    <textarea name="description"></textarea>

    <label>Prix:</label>
    <input type="number" name="price" step="0.01" required>

    <button type="submit">Créer</button>
</form>
