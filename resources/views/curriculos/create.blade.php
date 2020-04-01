@extends('layouts.clear')

@section('css-header')
<link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@endsection

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
    @if(Session::has('create_curriculo'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <div class="text-center py-5">
        <h2>{{ session('create_curriculo') }}</h2>
        <hr class="my-4">
        <p class="lead">Obrigado por cadastrar seu currículo, em breve entraremos em contato</p>
      </div>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Atenção!</strong> Todos campos marcados com <strong>*</strong> são de preenchimento obrigatório.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="container">
      <form method="POST" action="{{ route('curriculo.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-row">
        <div class="form-group col-md-5">
          <label for="nome">Nome do Candidato<strong  class="text-danger">(*)</strong></label>
          <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" name="nome" value="{{ old('nome') ?? '' }}">
          @if ($errors->has('nome'))
          <div class="invalid-feedback">
          {{ $errors->first('nome') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-2">
          <label for="nascimento">Data Nascimento<strong  class="text-danger">(*)</strong></label>
          <input type="text" class="form-control{{ $errors->has('nascimento') ? ' is-invalid' : '' }}" name="nascimento" id="nascimento" value="{{ old('nascimento') ?? '' }}">
          @if ($errors->has('nascimento'))
          <div class="invalid-feedback">
          {{ $errors->first('nascimento') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-2">
          <label for="genero">Gênero<strong  class="text-danger">(*)</strong></label>
          <select class="form-control {{ $errors->has('genero') ? ' is-invalid' : '' }}" name="genero" id="genero">
            <option value="" selected="true">Selecione ...</option>        
            <option value="f" {{ old("genero") == "f" ? "selected":"" }}>Feminino</option>
            <option value="m" {{ old("genero") == "m" ? "selected":"" }}>Masculino</option>
          </select>
          @if ($errors->has('genero'))
          <div class="invalid-feedback">
          {{ $errors->first('genero') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-3">
          <label for="cpf">CPF<strong  class="text-danger">(*)</strong></label>
          <input type="text" class="form-control{{ $errors->has('cpf') ? ' is-invalid' : '' }}" name="cpf" id="cpf" value="{{ old('cpf') ?? '' }}">
          @if ($errors->has('cpf'))
          <div class="invalid-feedback">
          {{ $errors->first('cpf') }}
          </div>
          @endif
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="email">E-mail<strong  class="text-danger">(*)</strong></label>
          <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') ?? '' }}">
          @if ($errors->has('email'))
          <div class="invalid-feedback">
          {{ $errors->first('email') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-6">
          <label for="email_confirmation">Confirmação do E-mail<strong  class="text-danger">(*)</strong></label>
          <input type="text" class="form-control{{ $errors->has('email_confirmation') ? ' is-invalid' : '' }}" name="email_confirmation" value="{{ old('email_confirmation') ?? '' }}">
          @if ($errors->has('email_confirmation'))
          <div class="invalid-feedback">
          {{ $errors->first('email_confirmation') }}
          </div>
          @endif
        </div>
      </div> 
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="cel1">N&lowast; Celular<strong  class="text-danger">(*)</strong></label>
          <input type="text" class="form-control{{ $errors->has('cel1') ? ' is-invalid' : '' }}" name="cel1" id="cel1" value="{{ old('cel1') ?? '' }}">
          @if ($errors->has('cel1'))
          <div class="invalid-feedback">
          {{ $errors->first('cel1') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-4">
          <label for="cel2">N&lowast; Celular Alternativo<strong  class="text-warning">(opcional)</strong></label>
          <input type="text" class="form-control" name="cel2" id="cel2" value="{{ old('cel2') ?? '' }}">
        </div>
        <div class="form-group col-md-4">
          <label for="tel">N&lowast; Telefone Fixo<strong  class="text-warning">(opcional)</strong></label>
          <input type="text" class="form-control" name="tel" value="{{ old('tel') ?? '' }}">
        </div>
      </div> 
      <div class="form-row">
        <div class="form-group col-md-2">
          <label for="cep">CEP<strong  class="text-danger">(*)</strong></label>  
          <input type="text" class="form-control{{ $errors->has('cep') ? ' is-invalid' : '' }}" name="cep" id="cep" value="{{ old('cep') ?? '' }}">
          @if ($errors->has('cep'))
          <div class="invalid-feedback">
          {{ $errors->first('cep') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-5">  
          <label for="logradouro">Logradouro <strong  class="text-danger">(*)</strong></label>  
          <input type="text" class="form-control{{ $errors->has('logradouro') ? ' is-invalid' : '' }}" name="logradouro" id="logradouro" value="{{ old('logradouro') ?? '' }}">
          @if ($errors->has('logradouro'))
          <div class="invalid-feedback">
          {{ $errors->first('logradouro') }}
          </div>
          @endif
        </div> 
        <div class="form-group col-md-2">  
          <label for="numero">Nº <strong  class="text-danger">(*)</strong></label>  
          <input type="text" class="form-control{{ $errors->has('numero') ? ' is-invalid' : '' }}" name="numero" id="numero" value="{{ old('numero') ?? '' }}">
          @if ($errors->has('numero'))
          <div class="invalid-feedback">
          {{ $errors->first('numero') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-3">  
          <label for="complemento">Complemento <strong  class="text-warning">(opcional)</strong></label>  
          <input type="text" class="form-control" name="complemento" id="complemento" value="{{ old('complemento') ?? '' }}">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="bairro">Bairro <strong  class="text-danger">(*)</strong></label>  
          <input type="text" class="form-control{{ $errors->has('bairro') ? ' is-invalid' : '' }}" name="bairro" id="bairro" value="{{ old('bairro') ?? '' }}">
          @if ($errors->has('bairro'))
          <div class="invalid-feedback">
          {{ $errors->first('bairro') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-6">  
          <label for="cidade">Cidade <strong  class="text-danger">(*)</strong></label>  
          <input type="text" class="form-control{{ $errors->has('cidade') ? ' is-invalid' : '' }}" name="cidade" id="cidade" value="{{ old('cidade') ?? '' }}">
          @if ($errors->has('cidade'))
          <div class="invalid-feedback">
          {{ $errors->first('cidade') }}
          </div>
          @endif
        </div> 
        <div class="form-group col-md-2">  
          <label for="uf">UF <strong  class="text-danger">(*)</strong></label>  
          <input type="text" class="form-control{{ $errors->has('uf') ? ' is-invalid' : '' }}" name="uf" id="uf" value="{{ old('uf') ?? '' }}">
          @if ($errors->has('uf'))
          <div class="invalid-feedback">
          {{ $errors->first('uf') }}
          </div>
          @endif
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="origemcontagem">Reside em Contagem?<strong  class="text-danger">(*)</strong></label>
          <select class="form-control {{ $errors->has('origemcontagem') ? ' is-invalid' : '' }}" name="origemcontagem" id="origemcontagem">
            <option value="" selected="true">Selecione ...</option>        
            <option value="s" {{ old("origemcontagem") == "s" ? "selected":"" }}>Sim</option>
            <option value="n" {{ old("origemcontagem") == "n" ? "selected":"" }}>Não</option>
          </select>
          @if ($errors->has('origemcontagem'))
          <div class="invalid-feedback">
          {{ $errors->first('origemcontagem') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-6">
          <label for="cargo_id">Cargo<strong  class="text-danger">(*)</strong></label>
          <select class="form-control {{ $errors->has('cargo_id') ? ' is-invalid' : '' }}" name="cargo_id" id="cargo_id">
            <option value="" selected="true">Selecione ...</option>        
            @foreach($cargos as $cargo)
            <option value="{{$cargo->id}}" {{ old("cargo_id") == $cargo->id ? "selected":"" }}>{{$cargo->descricao}}</option>
            @endforeach
          </select>
          @if ($errors->has('cargo_id'))
          <div class="invalid-feedback">
          {{ $errors->first('cargo_id') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-3">
          <label for="registro">Registro do Conselho<strong  class="text-warning">(opcional)</strong></label>
          <input type="text" class="form-control" name="registro" value="{{ old('registro') ?? '' }}">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-7">
          <p>Disponibilidade de Carga Horária Semanal</p>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="c20h" id="c20h" value="s" {{ old("c20h") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="c20h">20h semanais</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="c24h" id="c24h" value="s" {{ old("c24h") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="c24h">24h semanais</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="c30h" id="c30h" value="s"  {{ old("c30h") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="c30h">30h semanais</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="c40h" id="c40h" value="s"  {{ old("c40h") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="c40h">40h semanais</label>
          </div>
        </div>
        <div class="form-group col-md-5">
          <p>Disponibilidade de Turno</p>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="turnomanha" id="turnomanha" value="s" {{ old("turnomanha") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="turnomanha">Manhã</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="turnotarde" id="turnotarde" value="s" {{ old("turnotarde") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="turnotarde">Tarde</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="turnonoite" id="turnonoite" value="s" {{ old("turnonoite") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="turnonoite">Noite</label>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-7">
          <p>Disponibilidade Dias da Semana</p>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="seg" id="seg" value="s"  {{ old("seg") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="seg">SEG</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="ter" id="ter" value="s"  {{ old("ter") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="ter">TER</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="qua" id="qua" value="s"  {{ old("qua") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="qua">QUA</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="qui" id="qui" value="s"  {{ old("qui") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="qui">QUI</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="sex" id="sex" value="s"  {{ old("sex") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="sex">SEX</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="sab" id="sab" value="s"  {{ old("sab") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="sab">SAB</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="dom" id="dom" value="s" {{ old("dom") == "s" ? "checked":"" }}>
            <label class="form-check-label" for="dom">DOM</label>
          </div>
        </div>
        <div class="form-group col-md-5">
          <label for="deficiente">Portador de Deficiência?<strong  class="text-danger">(*)</strong></label>
          <select class="form-control {{ $errors->has('deficiente') ? ' is-invalid' : '' }}" name="deficiente" id="deficiente">
            <option value="" selected="true">Selecione ...</option>        
            <option value="s" {{ old("deficiente") == "s" ? "selected":"" }}>Sim</option>
            <option value="n" {{ old("deficiente") == "n" ? "selected":"" }}>Não</option>
          </select>
          @if ($errors->has('deficiente'))
          <div class="invalid-feedback">
          {{ $errors->first('deficiente') }}
          </div>
          @endif
        </div>
      </div>
      <div class="form-group">
        <label for="protadordoencas">É portador de doenças crônicas, tais como: diabetes, hipertensão, doença respiratória, cardiopatias, insuficiência renal crônica, imunossuprimidos, pacientes oncológicos?<strong  class="text-danger">(*)</strong></label>
          <select class="form-control {{ $errors->has('protadordoencas') ? ' is-invalid' : '' }}" name="protadordoencas" id="protadordoencas">
            <option value="" selected="true">Selecione ...</option>        
            <option value="s" {{ old("protadordoencas") == "s" ? "selected":"" }}>Sim</option>
            <option value="n" {{ old("protadordoencas") == "n" ? "selected":"" }}>Não</option>
          </select>
          @if ($errors->has('protadordoencas'))
          <div class="invalid-feedback">
          {{ $errors->first('protadordoencas') }}
          </div>
          @endif
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">  
          <label for="qualdoenca">Caso seja portador de doença crônica listada anteriormente, digite quais?</label>  
          <input type="text" class="form-control" name="qualdoenca" value="{{ old('qualdoenca') ?? '' }}">
        </div>
        <div class="form-group col-md-6">  
          <label for="outrasdoenca">Possui outras doenças, quais?</label>  
          <input type="text" class="form-control" name="outrasdoenca" value="{{ old('outrasdoenca') ?? '' }}">
        </div>  
      </div>
      <div class="form-group">
        <label for="gestante">É gestante ou lactante?<strong  class="text-danger">(*)</strong></label>
          <select class="form-control {{ $errors->has('gestante') ? ' is-invalid' : '' }}" name="gestante" id="gestante">
            <option value="" selected="true">Selecione ...</option>        
            <option value="s" {{ old("gestante") == "s" ? "selected":"" }}>Sim</option>
            <option value="n" {{ old("gestante") == "n" ? "selected":"" }}>Não</option>
          </select>
          @if ($errors->has('gestante'))
          <div class="invalid-feedback">
          {{ $errors->first('gestante') }}
          </div>
          @endif
      </div>
      <div class="form-group">
        <label for="temparente">Possui parente que trabalhe na Prefeitura Municipal de Contagem ocupante de cargo em comissão?<strong  class="text-danger">(*)</strong></label>
          <select class="form-control {{ $errors->has('temparente') ? ' is-invalid' : '' }}" name="temparente" id="temparente">
            <option value="" selected="true">Selecione ...</option>        
            <option value="s" {{ old("temparente") == "s" ? "selected":"" }}>Sim</option>
            <option value="n" {{ old("temparente") == "n" ? "selected":"" }}>Não</option>
          </select>
          @if ($errors->has('temparente'))
          <div class="invalid-feedback">
          {{ $errors->first('temparente') }}
          </div>
          @endif
      </div>
      <div class="form-group">
        <label for="nomeparente">Caso tenho respondido sim na questão anterior, escreva o nome do servidor:</strong></label>  
        <input type="text" class="form-control" name="nomeparente" value="{{ old('nomeparente') ?? '' }}"> 
      </div>
      <div class="container bg-primary text-white">
        <p class="text-center">Anexo de Currículo</p>
      </div>
      <div class="form-row">
        <div class="form-group col-md-5">
          <label for="arquivo">Escolha o arquivo com seu currículo...<strong  class="text-danger">(*)</strong></label>
          <input type="file" class="form-control-file  {{ $errors->has('arquivo') ? ' is-invalid' : '' }}" id="arquivo" name="arquivo">
          @if ($errors->has('arquivo'))
          <div class="invalid-feedback">
          {{ $errors->first('arquivo') }}
          </div>
          @endif
        </div>
        <div class="form-group col-md-7">
          <div class="alert alert-warning" role="alert">
            <p><strong  class="text-danger">(!)</strong> Só serão aceitos os seguintes formatos: pdf, doc, rft ou txt</p>
            <p><strong  class="text-danger">(!)</strong> O arquivo não pode ter mais de <strong>2MB</strong></p>
            <p><strong  class="text-danger">(!)</strong> Não serão aceitos currículos manuscritos</p>
          </div>
        </div>  
      </div>
      <div class="form-group">
        <div class="alert alert-primary" role="alert">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="declaro" value="s">
            <label class="form-check-label" for="declaro"><strong>Declaro que as informações prestadas neste formulário eletrônico são verdadeiras, e assumo inteira responsabilidade pelas mesmas, estando ciente e concordando com às condições exigidas pela inscrição no Edital /CP Nº 01/2020 e me submentendo a todas às normas expressas neste.</strong></label>            
          </div>    
        </div>
        @if ($errors->has('declaro'))
        <div class="alert alert-danger" role="alert">
        <p><strong  class="text-danger">(!)</strong>{{ $errors->first('declaro') }}
        </div>
        @endif
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Enviar Currículo</button>
    </div>
  </div>
@endsection

@section('script-footer')
<script src="{{ asset('js/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('locales/bootstrap-datepicker.pt-BR.min.js') }}"></script>
<script>
  $(document).ready(function(){

        $('#nascimento').datepicker({
          format: "dd/mm/yyyy",
          todayBtn: "linked",
          clearBtn: true,
          language: "pt-BR",
          autoclose: true,
          todayHighlight: true
      });

      $("#cpf").inputmask({"mask": "999.999.999-99"});
      $("#cel1").inputmask({"mask": "(99) 99999-9999"});
      $("#cel2").inputmask({"mask": "(99) 99999-9999"});
      $("#cep").inputmask({"mask": "99.999-999"});


      $("#cep").blur(function () {
          var cep = $(this).val().replace(/\D/g, '');
          if (cep != "") {
              var validacep = /^[0-9]{8}$/;
              if(validacep.test(cep)) {
                  $("#logradouro").val("...");
                  $("#bairro").val("...");
                  $("#cidade").val("...");
                  $("#uf").val("...");
                  $.ajax({
                      dataType: "json",
                      url: "https://erisilva.net/cep/?value=" + cep + "&field=cep&method=json",
                      type: "GET",
                      success: function(json) {
                          if (json['erro']) {
                              limpa_formulario_cep();
                              console.log('cep inválido');
                          } else {
                              $("#bairro").val(json[0]['bairro']);
                              $("#cidade").val(json[0]['cidade']);
                              $("#uf").val(json[0]['uf'].toUpperCase());
                              var tipo = json[0]['tipo'];
                              var logradouro = json[0]['logradouro'];
                              $("#logradouro").val(tipo + ' ' + logradouro);
                          }
                      }
                  });
              } else {
                  limpa_formulario_cep();
              }
          } else {
              limpa_formulario_cep();
          }
      });      

  });
</script>

@endsection      
