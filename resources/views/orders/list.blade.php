@extends('layout')
@section('body')
    <div class="row">
        <div class="col">
            <h1>Lista de ordenes</h1>
            <hr>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Referencia</th>
                        <th scope="col">Nombres</th>
                        <th scope="col">Email</th>
                        <th scope="col">Celular</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $item)
                        <tr>
                            <th scope="row">
                                {{ $loop->iteration }}
                            </th>
                            <td>
                                {{ $item->reference }}
                            </td>
                            <td>
                                {{ $item->customer_name }}
                            </td>
                            <td>
                                {{ $item->customer_email }}
                            </td>
                            <td>
                                {{ $item->customer_mobile }}
                            </td>
                            <td>
                                {{ $item->status }}
                            </td>
                            <td>
                                <a href="{{ route('order.show', ['reference' => $item->reference]) }}">
                                    <i class="bi bi-eye-fill"></i>
                                </a>                                
                            </td>
                        </tr>
                    @endforeach              
                </tbody>
            </table>
        </div>
    </div>
@stop