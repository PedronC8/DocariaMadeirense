
@extends('layouts.app')
@section('content')



<body>

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h1><strong>Clientes</strong></h1>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
         <a href="{{ route('clients.create') }}" class="btn btn-primary"><i class="align-middle me-2 " data-feather="plus"></i>Novo Cliente</a>
    </div>
</div>




<div class="card">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">

        <!-- FORM começa aqui -->
        <form method="GET" action="{{ route('clients.index') }}" class="d-flex align-items-center gap-2 w-100">

            <!-- input de pesquisa -->
            <input 
                type="text" 
                name="search"
                value="{{ request('search') }}"

                class="form-control ms-3" 
                style="width:250px;" 
                placeholder="Pesquisar por nome, telefone..."
            >

            <!-- FUTURO FILTRO VENDEDOR -->
            <!-- <select name="seller" class="form-select" style="width:180px;">
                <option value="">Vend: Todos</option>
            </select> -->

            <!-- FUTURO FILTRO DATA -->
            <!-- <select name="date" class="form-select" style="width:180px;">
                <option value="">Data: Todas</option>
            </select> -->

            <!-- div para botões, empurrando para a direita -->
            <div class="ms-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-feather="filter"></i> Filtrar
                </button>

                <a href="{{ route('clients.index') }}" class="btn btn-secondary me-2">
                    Limpar
                </a>
            </div>

        </form>
        <!-- FORM termina aqui -->

    </div>
</div>







<div class="card " >

    <div class="card-body p-0">

        <table class="table table-hover  mb-0">

            <thead style="background:#e2e8f0;color:#0f172a;border-bottom:2px solid #cbd5e1;">
    <tr>
        <th  >Nome</th>

        <th style="width:15%;">Telefone</th>

        <th style="width:20%;">Última Encomenda</th>

        <th style="width:15%;">Total Encomendas</th>

        <th class="text-end pe-4" style="width:15%;">Ações</th>
    </tr>
</thead>

            <tbody>

                @foreach($clients as $client)

                <tr >

                    <td  > 
                        {{ $client->name }} 
                    </td>

                    <td > 
                        {{ $client->contact ? $client->contact : '---' }}
                    </td>

                    <td > 
                        {{ $client->orders_max_order_date ? $client->orders_max_order_date : '---' }}
                    </td>

                    <td> 
                        {{ $client->orders_count }}
                    </td>

                    <td class="text-end ">
                        <a href="{{ route('clients.show', $client->id) }}" 
                           class="btn btn-primary btn-sm shadow-sm">
                           Detalhes
                        </a>
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

	<!-- Gera os links de paginação (Tailwind por padrão) -->
{{ $clients->links() }}
	
</body>
	



@endsection