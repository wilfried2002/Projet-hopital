
<!DOCTYPE html>
<h2 style="text-align: center;">Liste des Produits</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Quantité</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ number_format($product->price, 2) }} FCFA</td>
                <td>{{ $product->quantite }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
