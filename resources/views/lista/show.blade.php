@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('lista.index') }}">Lista de Currículos Recebidos</a></li>
      <li class="breadcrumb-item active" aria-current="page">Exibir Registro</li>
    </ol>
  </nav>
</div>
<div class="container">

    <form>
        <div class="form-row">
          <div class="form-group col-md-4">
            <div class="p-3 bg-primary text-white text-right h2">Nº {{ $curriculo->id }}</div>    
          </div>
          <div class="form-group col-md-2">
            <label for="dia">Data</label>
            <input type="text" class="form-control" name="dia" value="{{ $curriculo->created_at->format('d/m/Y') }}" readonly>
          </div>
          <div class="form-group col-md-2">
            <label for="hora">Hora</label>
            <input type="text" class="form-control" name="hora" value="{{ $curriculo->created_at->format('H:i') }}" readonly>
          </div>
        </div>


      <div class="form-row">
        <div class="form-group col-md-5">
          <label for="nome">Nome do Candidato</label>
          <input type="text" class="form-control" name="nome" value="{{ $curriculo->id }}" readonly>

        </div>
        <div class="form-group col-md-2">
          <label for="nascimento">Data Nascimento</label>
          <input type="text" class="form-control" name="nascimento" id="nascimento" value="{{ $curriculo->nascimento->format('d/m/Y') }}" readonly>
        </div>
        <div class="form-group col-md-2">
          <label for="genero">Gênero</label>
          <input type="text" class="form-control" name="nome" value="{{ $curriculo->genero == 'f' ? 'feminino' : 'masculino' }}" readonly>
        </div>
        <div class="form-group col-md-3">
          <label for="cpf">CPF</label>
          <input type="text" class="form-control" name="cpf" id="cpf" value="{{ $curriculo->cpf }}" readonly>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="email">E-mail</label>
          <input type="text" class="form-control" name="email" value="{{ $curriculo->email }}" readonly>
        </div>
        <div class="form-group col-md-6">

        </div>
      </div> 
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="cel1">N&lowast; Celular</label>
          <input type="text" class="form-control" name="cel1" id="cel1" value="{{ $curriculo->cel1 }}" readonly>
        </div>
        <div class="form-group col-md-4">
          <label for="cel2">N&lowast; Celular Alternativo</label>
          <input type="text" class="form-control" name="cel2" id="cel2" value="{{ $curriculo->cel2 }}" readonly>
        </div>
        <div class="form-group col-md-4">
          <label for="tel">N&lowast; Telefone Fixo</label>
          <input type="text" class="form-control" name="tel" value="{{ $curriculo->tel }}" readonly>
        </div>
      </div> 
      <div class="form-row">
        <div class="form-group col-md-2">
          <label for="cep">CEP</label>  
          <input type="text" class="form-control" name="cep" id="cep" value="{{ $curriculo->cep }}" readonly>
        </div>
        <div class="form-group col-md-5">  
          <label for="logradouro">Logradouro</label>  
          <input type="text" class="form-control" name="logradouro" id="logradouro" value="{{ $curriculo->logradouro }}" readonly>
        </div> 
        <div class="form-group col-md-2">  
          <label for="numero">Nº</label>  
          <input type="text" class="form-control" name="numero" id="numero" value="{{ $curriculo->numero }}" readonly>

        </div>
        <div class="form-group col-md-3">  
          <label for="complemento">Complemento</label>  
          <input type="text" class="form-control" name="complemento" id="complemento" value="{{ $curriculo->complemento }}" readonly>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="bairro">Bairro</label>  
          <input type="text" class="form-control" name="bairro" id="bairro" value="{{ $curriculo->bairro }}" readonly>
        </div>
        <div class="form-group col-md-6">  
          <label for="cidade">Cidade</label>  
          <input type="text" class="form-control" name="cidade" id="cidade" value="{{ $curriculo->cidade }}" readonly>
        </div> 
        <div class="form-group col-md-2">  
          <label for="uf">UF</label>  
          <input type="text" class="form-control" name="uf" id="uf" value="{{ $curriculo->uf }}" readonly>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="origemcontagem">Reside em Contagem?</label>
          <input type="text" class="form-control" name="origemcontagem" id="origemcontagem" value="{{ $curriculo->origemcontagem == 's' ? 'Sim' : 'Não' }}" readonly>
        </div>
        <div class="form-group col-md-6">
          <label for="cargo_id">Cargo</label>
          <input type="text" class="form-control" name="cargo_id" id="cargo_id" value="{{ $curriculo->cargo->descricao }}" readonly>
        </div>
        <div class="form-group col-md-3">
          <label for="registro">Registro do Conselho</label>
          <input type="text" class="form-control" name="registro" value="{{ $curriculo->registro }}" readonly>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-7">
          <p>Disponibilidade de Carga Horária Semanal</p>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="c20h" id="c20h" value="s" {{ $curriculo->c20h == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="c20h">20h semanais</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="c24h" id="c24h" value="s" {{ $curriculo->c24h == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="c24h">24h semanais</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="c30h" id="c30h" value="s" {{ $curriculo->c30h == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="c30h">30h semanais</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="c40h" id="c40h" value="s" {{ $curriculo->c40h == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="c40h">40h semanais</label>
          </div>
        </div>
        <div class="form-group col-md-5">
          <p>Disponibilidade de Turno</p>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="turnomanha" id="turnomanha" value="s" {{ $curriculo->turnomanha == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="turnomanha">Manhã</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="turnotarde" id="turnotarde" value="s" {{ $curriculo->turnotarde == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="turnotarde">Tarde</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="turnonoite" id="turnonoite" value="s" {{ $curriculo->turnonoite == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="turnonoite">Noite</label>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-7">
          <p>Disponibilidade Dias da Semana</p>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="seg" id="seg" value="s" {{ $curriculo->seg == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="seg">SEG</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="ter" id="ter" value="s" {{ $curriculo->ter == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="ter">TER</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="qua" id="qua" value="s" {{ $curriculo->qua == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="qua">QUA</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="qui" id="qui" value="s" {{ $curriculo->qui == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="qui">QUI</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="sex" id="sex" value="s" {{ $curriculo->sex == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="sex">SEX</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="sab" id="sab" value="s" {{ $curriculo->sab == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="sab">SAB</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="dom" id="dom" value="s" {{ $curriculo->dom == 's' ? 'checked' : '' }}>
            <label class="form-check-label" for="dom">DOM</label>
          </div>
        </div>
        <div class="form-group col-md-5">
          <label for="deficiente">Portador de Deficiência?</label>
          <input type="text" class="form-control" name="deficiente" value="{{ $curriculo->deficiente == 's' ? 'Sim' : 'Não' }}" readonly>
        </div>
      </div>

      <div class="form-group">
        <label for="protadordoencas">É portador de doenças crônicas, tai como: diabetes, hipertensão, doença respiratória, cardiopatias, insuficiência renal crônica, imunossuprimidos, pacientes oncológicos?</label>
        <input type="text" class="form-control" name="protadordoencas" value="{{ $curriculo->protadordoencas == 's' ? 'Sim' : 'Não' }}" readonly>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">  
          <label for="qualdoenca">Caso seja portador de doença crônica listada anteriormente, digite quais?</label>  
          <input type="text" class="form-control" name="qualdoenca" value="{{ $curriculo->qualdoenca }}" readonly>
        </div>
        <div class="form-group col-md-6">  
          <label for="outrasdoenca">Possui outras doenças, quais?</label>  
          <input type="text" class="form-control" name="outrasdoenca" value="{{ $curriculo->outrasdoenca }}" readonly>
        </div>  
      </div>


      <div class="form-group">
        <label for="gestante">É gestante ou lactante?</label>
        <input type="text" class="form-control" name="gestante" value="{{ $curriculo->gestante == 's' ? 'Sim' : 'Não' }}" readonly>
      </div>


      <div class="form-group">
        <label for="temparente">Possui parente que trabalhe na Prefeitura Municipal de Contagem ocupante de cargo em comissão?</label>
        <input type="text" class="form-control" name="temparente" value="{{ $curriculo->temparente == 's' ? 'Sim' : 'Não' }}" readonly>
      </div>


      <div class="form-group">
        <label for="nomeparente">Caso tenho respondido sim na questão anterior, escreva o nome do servidor:</strong></label>  
        <input type="text" class="form-control" name="nomeparente" value="{{ $curriculo->nomeparente }}"> 
      </div>


      <div class="form-group">
        <a class="btn btn-warning btn-sm" role="button" href="{{ $curriculo->arquivoUrl }}" target="_blank">Fazer Download do Currículo Anexado</a>
      </div>




        </div>

      </form>


  <br>
  <div class="container">
    <a href="{{ route('lista.index') }}" class="btn btn-primary" role="button"><i class="fas fa-long-arrow-alt-left"></i> Voltar</i></a>
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalLixeira"><i class="fas fa-trash-alt"></i> Enviar para Lixeira</button>
  </div>
  <div class="modal fade" id="modalLixeira" tabindex="-1" role="dialog" aria-labelledby="JanelaProfissional" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-question-circle"></i> Enviar Profissional para Lixeira</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger" role="alert">
            <p><strong>Atenção!</strong> Confirma enviar esse currículo para a lixeira?</p>
            <h2>Confirma?</h2>
          </div>
          <form method="post" action="{{route('lista.destroy', $curriculo->id)}}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Enviar para Lixeira</button>
          </form>
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
        </div>
      </div>
    </div>
  </div>      
</div>

@endsection
