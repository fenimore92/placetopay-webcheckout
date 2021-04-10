@extends('layout')
@section('body')
<div class="row">
    <div class="col">
        <h1>Detalle Orden</h1>
        <hr>
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
                <th class="table-dark" scope="col">Estado</th>
                <td>{{ $order->status }}</td>
            </tr>
        </table>        
    </div>
</div>
@stop