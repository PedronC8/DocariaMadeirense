
@extends('layouts.app')
@section('content')



<body>

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h1><strong>Gestão de utilizadores</strong></h1>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
         <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="align-middle me-2 " data-feather="plus"></i>Novo Utilizador</a>
    </div>
</div>










<div class="card " >

    <div class="card-body p-0">

        <table class="table table-hover  mb-0">

            <thead style="background:#e2e8f0;color:#0f172a;border-bottom:2px solid #cbd5e1;">
    <tr>
        <th  >Nome</th>

        <th style="width:15%;">Email</th>

        <th style="width:20%;">Tipo de utilizador</th>

        <th style="width:15%;">Password</th>

        <th class="text-end pe-4" style="width:15%;">Ações</th>
    </tr>
</thead>

            <tbody>

                @foreach($users as $user)

                <tr >

                    <td  > 
                        {{ $user->name }} 
                    </td>

                    <td > 
                        {{ $user->email }}
                    </td>

                    <td > 
                        {{ $user->role }}
                    </td>

                    <td > 
                        {{ $user->password }}
                    </td>


                    <td class="text-end ">
                        <a href="{{ route('users.show', $user->id) }}" 
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
{{ $users->links() }}
	
</body>
	



@endsection