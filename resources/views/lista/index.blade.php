@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('lista.index') }}">Lista de Currículos Recebidos</a></li>
    </ol>
  </nav>

  <div class="btn-group py-1" role="group" aria-label="Opções">
    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalFilter"><i class="fas fa-filter"></i> Filtrar</button>
    <div class="btn-group" role="group">
      <button id="btnGroupDropOptions" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Opções
      </button>
      <div class="dropdown-menu" aria-labelledby="btnGroupDropOptions">
        <a class="dropdown-item" href="#" id="btnExportarCSV"><i class="fas fa-file-download"></i> Exportar Planilha</a>
        <a class="dropdown-item" href="#" id="btnExportarPDF"><i class="fas fa-file-download"></i> Exportar PDF</a>
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Cód.</th>
                <th scope="col">Data</th>
                <th scope="col">Hora</th>
                <th scope="col">Nome</th>
                <th scope="col">CPF</th>
                <th scope="col">Cargo</th>
                <th scope="col">Celular</th>
                <th scope="col">E-mail</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($curriculos as $curriculo)
            <tr>
                <td>{{ $curriculo->id }}</td>
                <td>{{ $curriculo->created_at->format('d/m/Y') }}</td>
                <td>{{ $curriculo->created_at->format('H:i') }}</td>
                <td>{{ $curriculo->nome }}</td>
                <td>{{ $curriculo->cpf }}</td>
                <td>{{ $curriculo->cargo->descricao }}</td>
                <td>{{ $curriculo->cel1 }}</td>
                <td>{{ $curriculo->email }}</td>
                <td><a href="{{ $curriculo->arquivoUrl }}" target="_blank">Anexo</a></td>
                <td></td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="{{route('lista.show', $curriculo->id)}}" class="btn btn-primary btn-sm" role="button"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('lista.export.pdf.individual', $curriculo->id) }}" class="btn btn-primary btn-sm" role="button"><i class="fas fa-print"></i></a>

                  </div>
                </td>
            </tr>    
            @endforeach                                                 
        </tbody>
    </table>
  </div>
  <p class="text-center">Página {{ $curriculos->currentPage() }} de {{ $curriculos->lastPage() }}. Total de registros: {{ $curriculos->total() }}.</p>
  <div class="container-fluid">
      {{ $curriculos->links() }}
  </div>
  <!-- Janela de filtragem da consulta -->
  <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="JanelaFiltro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-filter"></i> Filtro</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Filtragem dos dados -->
          <!-- Seleção de número de resultados por página -->
          <div class="form-group">
            <select class="form-control" name="perpage" id="perpage">
              @foreach($perpages as $perpage)
              <option value="{{$perpage->valor}}"  {{($perpage->valor == session('perPage')) ? 'selected' : ''}}>{{$perpage->nome}}</option>
              @endforeach
            </select>
          </div>
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Fechar</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script-footer')
<script>
$(document).ready(function(){
    $('#perpage').on('change', function() {
        perpage = $(this).find(":selected").val(); 
        
        window.open("{{ route('lista.index') }}" + "?perpage=" + perpage,"_self");
    });

    $('#btnExportarCSV').on('click', function(){
        window.open("{{ route('lista.export.csv') }}","_self");
    });

    $('#btnExportarPDF').on('click', function(){
        window.open("{{ route('lista.export.pdf') }}","_self");
    });
}); 
</script>
@endsection