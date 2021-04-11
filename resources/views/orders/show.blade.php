@extends('layout')
@section('body')
<div class="row">
    <div class="col">
        <h1>Detalle Orden</h1>
        <hr>
        @if (!empty($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif        
        @if (!empty($warning))
            <div class="alert alert-warning">
                {{ $warning }}
            </div>
        @endif
        @if (!empty($success))
            <div class="alert alert-success">
                {{ $success }}
            </div>
        @endif
        <table class="table">
            <tr>
                <th class="table-dark" scope="col">Referencia</th>
                <td>{{ $order->reference }}</td>
            </tr>
            <tr>
                <th class="table-dark" scope="col">Nombres</th>
                <td>{{ $order->customer_name }}</td>
            </tr>
            <tr>
                <th class="table-dark" scope="col">Email</th>
                <td>{{ $order->customer_email }}</td>
            </tr>
            <tr>
                <th class="table-dark" scope="col">Celular</th>
                <td>{{ $order->customer_mobile }}</td>
            </tr>
            <tr>
                <th class="table-dark" scope="col">Precio</th>
                <td>${{ $order->price }}</td>
            </tr>
            <tr>
                <th class="table-dark" scope="col">Estado</th>
                <td>{{ $order->status }}</td>
            </tr>
        </table> 
        @if ($order->status == 'CREATED' || $order->status == 'REJECTED')
            <div class="row">
                <div class="col offset-8">
                    <div class="d-grid gap-2">
                        <a class="btn btn-success btn-lg" href="{{ $order->process_url }}">
                            Pagar
                        </a>
                    </div>                
                </div>
            </div>            
        @endif
    </div>
</div>
@stop