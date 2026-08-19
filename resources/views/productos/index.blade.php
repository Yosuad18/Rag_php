<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos MongoDB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Crear Producto en MongoDB</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('productos.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ old('nombre') }}">
                            @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio ($)</label>
                            <input type="number" name="precio" id="precio" class="form-control" required value="{{ old('precio') }}">
                            @error('precio') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="atributos" class="form-label">Atributos (Formato JSON)</label>
                            <textarea name="atributos" id="atributos" class="form-control" rows="3" placeholder='{"color": "rojo", "almacenamiento": "128GB", "marca": "Samsung"}'>{{ old('atributos') }}</textarea>
                            <div class="form-text">Ingresa las propiedades dinámicas en formato JSON.</div>
                            @error('atributos') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">Guardar Producto</button>
                    </form>
                </div>
            </div>

            <!-- Listado de Productos -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Productos Guardados</h5>
                </div>
                <div class="card-body">
                    @if($productos->isEmpty())
                        <p class="text-muted mb-0">No hay productos registrados aún.</p>
                    @else
                        <ul class="list-group">
                            @foreach($productos as $producto)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{ $producto->nombre }} - ${{ number_format($producto->precio) }}</div>
                                        <small class="text-muted">
                                            ID: {{ $producto->_id }} <br>
                                            Atributos: {{ json_encode($producto->atributos) }}
                                        </small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>