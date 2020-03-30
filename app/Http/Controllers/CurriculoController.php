<?php

namespace App\Http\Controllers;

use App\Curriculo;
use App\Cargo;

use App\Perpage;


use Response;

use Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon; // tratamento de datas

use App\Rules\Cpf; // validação de um cpf

use Illuminate\Support\Facades\Redirect; // para poder usar o redirect

class CurriculoController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cargos = Cargo::orderBy('id', 'asc')->get();

        return view('curriculos.create', compact('cargos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'nome' => 'required',
            'nascimento' => 'required',
            'genero' => 'required',
            'cpf' => 'required',
            'cpf' => new Cpf,
            'email' => 'required|confirmed',
            'cel1' => 'required',
            'cep' => 'required',
            'logradouro' => 'required',
            'numero' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'uf' => 'required',
            'origemcontagem' => 'required',
            'cargo_id' => 'required',
            'deficiente' => 'required',
            'protadordoencas' => 'required',
            'gestante' => 'required',
            'temparente' => 'required',
            'declaro' => 'required',
            'arquivo' => 'required|mimes:pdf,doc,rtf,txt|max:2000',

        ],
        [
            'nome.required' => 'O nome do candidato é obrigatório',
            'nascimento.required' => 'A data de nascimento do candidato é obrigatória',
            'genero.required' => 'Escolha na lista o gênero do candidato',
            'cpf.required' => 'O CPF do candidato é obrigatório',
            'email.required' => 'O e-mail do candidato é obrigatório',
            'cel1.required' => 'O obrigatório digitar um número de celular para contato',
            'origemcontagem.required' => 'Selecione uma resposta',
            'cargo_id.required' => 'Selecione na lista um cargo',
            'deficiente.required' => 'Escolha na lista se o candidato é portador de deficiência',
            'arquivo.required' => 'É obrigatório anexar um arquivo com seu currículo',
            'protadordoencas.required' => 'Selecione uma resposta',
            'gestante.required' => 'Selecione uma resposta',
            'temparente.required' => 'Selecione uma resposta',
            'declaro.required' => 'Você precisa aceitar as condições exigidas de acordo com o edital clicando na caixa acima',
            'arquivo.mimes' => 'O arquivo anexado deve ser das seguintes extensões: pdf, doc, rft ou txt',
            'arquivo.max' => 'O arquivo anexado não pode ter mais de 3MB',
        ]);

        $input = $request->all();

        // ajusta data
        if ($input['nascimento'] != ""){
            $dataFormatadaMysql = Carbon::createFromFormat('d/m/Y', request('nascimento'))->format('Y-m-d');           
            $input['nascimento'] =  $dataFormatadaMysql;            
        }


        // geração de uma string aleatória de tamanho configurável
        function generateRandomString($length = 10) {
            return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
        }

        $local = generateRandomString(8);

        if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid()) {

            $nome_arquivo =  $input['nome'] . ' - ' . $input['cpf'] . '.' . $request->arquivo->extension();

            $path = $request->file('arquivo')->storeAs($local, $nome_arquivo, 'public');

            // full url
            $url = asset('storage/' . $local . '/' . $nome_arquivo);
        }

        // salva as informações do arquivo
        $input['arquivoNome'] =  $nome_arquivo;  
        $input['arquivoLocal'] =  $local;  
        $input['arquivoUrl'] =  $url;

        if (!isset($input['c20h'])) {
            $input['c20h'] = 'n';
        }

        if (!isset($input['c24h'])) {
            $input['c24h'] = 'n';
        }

        if (!isset($input['c30h'])) {
            $input['c30h'] = 'n';
        }

        if (!isset($input['c40h'])) {
            $input['c40h'] = 'n';
        }

        if (!isset($input['turnomanha'])) {
            $input['turnomanha'] = 'n';
        }

        if (!isset($input['turnotarde'])) {
            $input['turnotarde'] = 'n';
        }

        if (!isset($input['turnonoite'])) {
            $input['turnonoite'] = 'n';
        }

         if (!isset($input['seg'])) {
            $input['seg'] = 'n';
        }

         if (!isset($input['ter'])) {
            $input['ter'] = 'n';
        }

         if (!isset($input['qua'])) {
            $input['qua'] = 'n';
        }

         if (!isset($input['qui'])) {
            $input['qui'] = 'n';
        }

         if (!isset($input['sex'])) {
            $input['sex'] = 'n';
        }

         if (!isset($input['sab'])) {
            $input['sab'] = 'n';
        }

         if (!isset($input['dom'])) {
            $input['dom'] = 'n';
        }


        $newcurriculo = Curriculo::create($input); //salva

        Session::flash('create_curriculo', 'Currículo enviado com sucesso!');

        $cargos = Cargo::orderBy('id', 'asc')->get();

        // return redirect(route('curriculo.create'));

         return Redirect::route('curriculo.show', $newcurriculo->id);

    }


        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $curriculo = Curriculo::findOrFail($id);

        return view('curriculos.show', compact('curriculo'));
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(403, 'Acesso negado.');
    }

 
}
