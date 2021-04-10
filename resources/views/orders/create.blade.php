@extends('layout')
@section('body')
<div class="row">
    <div class="col">
        <h1>Crear Orden</h1>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('order.create') }}">
            @csrf
            <div class="mb-3">
                <label for="customer_name" class="form-label">Nombres</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name">              
            </div>
            <div class="mb-3">
              <label for="customer_email" class="form-label">Email</label>
              <input type="text" class="form-control" id="customer_email" name="customer_email">              
            </div>
            <div class="mb-3">
                <label for="customer_mobile" class="form-label">Celular</label>
                <input type="text" class="form-control" id="customer_mobile" name="customer_mobile">              
            </div>            
            <button type="submit" class="btn btn-primary">Enviar</button>
          </form>
    </div>
</div>
@stop