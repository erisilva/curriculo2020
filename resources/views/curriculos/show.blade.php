@extends('layouts.clear')


@section('content')
  <div class="container">
    <div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="text-center">
              <img class="img-fluid" src="http://www.contagem.mg.gov.br/novoportal/wp-content/themes/pmc/images/logo-prefeitura-contagem.png">
            </div>  
          </div>
          <div class="col-md-8">
            <h3>Prefeitura Municipal de Contagem</h3>
            <h4">Secretaria Municipal de Saúde</h4>
            <p class="lead">Edital de Chamamento Público - Edital /CP Nº 01/2020</p>
            <hr class="my-4">
            <p>Banco de currículos</p>
          </div>
        </div>
      </div>        
    </div>

    <div class="container bg-primary text-white">
      <p class="text-center">Comprovante de Inscrição</p>
    </div>
    <div class="container">
      <form>
        <div class="form-row">
          <div class="form-group col-md-4">
            <div class="p-3 bg-primary text-white text-right h2">Nº Inscrição: {{ $curriculo->id }}</div>    
          </div>
          <div class="form-group col-md-2">
            <label for="dia">Data</label>
            <input type="text" class="form-control" name="dia" value="{{ $curriculo->created_at->format('d/m/Y') }}" readonly>
          </div>
          <div class="form-group col-md-2">
            <label for="hora">Hora</label>
            <input type="text" class="form-control" name="hora" value="{{ $curriculo->created_at->format('H:i') }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label for="nome">Nome do Candidato</label>
            <input type="text" class="form-control" name="nome" value="{{ $curriculo->nome }}" readonly>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="cpf">CPF do Candidato</label>
            <input type="text" class="form-control" name="cpf" value="{{ $curriculo->cpf }}" readonly>  
          </div>
          <div class="form-group col-md-8">
           <label for="cargo">Cargo</label>
            <input type="text" class="form-control" name="cargo" value="{{ $curriculo->cargo->descricao }}" readonly>    
          </div>
        </div>
        <div class="container">
          <a href="" onclick="window.print()" class="btn btn-primary" role="button"><i class="fas fa-print"></i> Imprimir</a>
          <a href="{{ route('curriculo.create') }}" class="btn btn-primary" role="button"><i class="fas fa-plus-square"></i> Fazer Nova Inscrição</a>
        </div>

      </form>  
    </div>  

  </div>
@endsection    
