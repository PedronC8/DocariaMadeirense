@extends('layouts.app')

@section('title', 'Dashboard - A Docaria')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-3"><strong>Dashboard</strong></h1>
    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-xxl-12">
        <div class="card flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Bem-vindo ao Sistema de Gestão</h5>
            </div>
            <div class="card-body">
                <p>Use o menu lateral para navegar:</p>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i data-feather="shopping-cart" class="feather-lg text-primary mb-2"></i>
                                <h5>Encomendas</h5>
                                <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm mt-2">
                                    Ver Encomendas
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i data-feather="package" class="feather-lg text-success mb-2"></i>
                                <h5>Produtos</h5>
                                <a href="{{ route('products.index') }}" class="btn btn-success btn-sm mt-2">
                                    Ver Produtos
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i data-feather="users" class="feather-lg text-info mb-2"></i>
                                <h5>Clientes</h5>
                                <a href="{{ route('clients.index') }}" class="btn btn-info btn-sm mt-2">
                                    Ver Clientes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
