<h4>Socio Titular</h4>

<ul class="list-group">
    <li class="list-group-item"><strong>Nombre:</strong> {{ $socio->nombres }} {{ $socio->paterno }}</li>
    <li class="list-group-item"><strong>CI:</strong> {{ $socio->ci }}</li>
    <li class="list-group-item">
        <strong>Estado:</strong>
        <span class="badge bg-{{ $socio->estado_titular === 'ACTIVO' ? 'success' : 'danger' }}">
            {{ $socio->estado_titular }}
        </span>
    </li>
</ul>

<hr>

<h5>Beneficiarios</h5>
<table class="table table-sm">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Parentesco</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($socio->beneficiarios as $b)
        <tr>
            <td>{{ $b->nombres }} {{ $b->paterno }}</td>
            <td>{{ $b->parentesco }}</td>
            <td>{{ $b->estado_beneficiario }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
