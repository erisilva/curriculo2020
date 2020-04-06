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

class ListaController extends Controller
{

    protected $pdf;

    /**
     * Construtor.
     *
     * precisa estar logado ao sistema
     * precisa ter a conta ativa (access)
     *
     * @return 
     */
    public function __construct(\App\Reports\CurriculoReport $pdf)
    {
        $this->middleware(['middleware' => 'auth']);
        $this->middleware(['middleware' => 'hasaccess']);

        $this->pdf = $pdf;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curriculos = new Curriculo;

        // filtros

        if (request()->has('codigo')){
            if (request('codigo') != ""){
                $curriculos = $curriculos->where('id', '=', request('codigo'));
            }
        }

        if (request()->has('nome')){
            $curriculos = $curriculos->where('nome', 'like', '%' . request('nome') . '%');
        }

        if (request()->has('cidade')){
            $curriculos = $curriculos->where('cidade', 'like', '%' . request('cidade') . '%');
        }

        if (request()->has('cargo_id')){
            if (request('cargo_id') != ""){
                $curriculos = $curriculos->where('cargo_id', '=', request('cargo_id'));
            }
        } 

        // ordena
        $curriculos = $curriculos->orderBy('id', 'desc');

        // se a requisição tiver um novo valor para a quantidade
        // de páginas por visualização ele altera aqui
        if(request()->has('perpage')) {
            session(['perPage' => request('perpage')]);
        }

        // consulta a tabela perpage para ter a lista de
        // quantidades de paginação
        $perpages = Perpage::orderBy('valor')->get();

        // paginação
        $curriculos = $curriculos->paginate(session('perPage', '5'))->appends([          
            'codigo' => request('codigo'),
            'nome' => request('nome'),
            'cargo_id' => request('cargo_id'),
            'cidade' => request('cidade'),      
            ]);

        // consulta a tabela dos cargos
        $cargos = Cargo::orderBy('id', 'asc')->get();

        return view('lista.index', compact('curriculos', 'perpages', 'cargos'));
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

        return view('lista.show', compact('curriculo'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Curriculo::findOrFail($id)->delete();

        Session::flash('deleted_curriculo', 'Currículo enviado para a lixeira com sucesso!');

        return redirect(route('lista.index'));
    }


    /**
     * Exportação para planilha (csv)
     *
     * @param  int  $id
     * @return Response::stream()
     */
    public function exportcsv()
    {
        if (Gate::denies('cargo-export')) {
            abort(403, 'Acesso negado.');
        }

       $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=Curriculos_' .  date("Y-m-d H:i:s") . '.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $curriculos = DB::table('curriculos');

        //joins
        $curriculos = $curriculos->join('cargos', 'cargos.id', '=', 'curriculos.cargo_id');

        $curriculos = $curriculos->select(
            'curriculos.id as codigo', 
            DB::raw('DATE_FORMAT(curriculos.created_at, \'%d/%m/%Y\') AS data_cadastro'), 
            DB::raw('DATE_FORMAT(curriculos.created_at, \'%H:%i\') AS hora_cadastro'),
            'curriculos.nome',
            DB::raw('DATE_FORMAT(curriculos.nascimento, \'%d/%m/%Y\') AS data_nascimento'), 
            DB::raw('IF(curriculos.genero=\'f\', \'Feminino\', \'Masculino\') as genero'),
            'curriculos.cpf',
            'curriculos.email',
            'curriculos.cel1',
            'curriculos.cel2',
            'curriculos.tel',
            'curriculos.cep',
            'curriculos.logradouro',
            'curriculos.numero',
            'curriculos.complemento',
            'curriculos.bairro',
            'curriculos.cidade',
            'curriculos.uf',
            DB::raw('IF(curriculos.origemcontagem=\'s\', \'Sim\', \'Não\') as Reside_em_Contagem'),
            'cargos.descricao as cargo',
            'curriculos.registro',
            DB::raw('IF(curriculos.c20h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_20h'),
            DB::raw('IF(curriculos.c24h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_24h'),
            DB::raw('IF(curriculos.c30h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_30h'),
            DB::raw('IF(curriculos.c40h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_40h'),
            DB::raw('IF(curriculos.turnomanha=\'s\', \'Sim\', \'Não\') as Turno_Manha'),
            DB::raw('IF(curriculos.turnotarde=\'s\', \'Sim\', \'Não\') as Turno_Tarde'),
            DB::raw('IF(curriculos.turnonoite=\'s\', \'Sim\', \'Não\') as Turno_Noite'),

            DB::raw('IF(curriculos.seg=\'s\', \'Sim\', \'Não\') as SEG'),
            DB::raw('IF(curriculos.ter=\'s\', \'Sim\', \'Não\') as TER'),
            DB::raw('IF(curriculos.qua=\'s\', \'Sim\', \'Não\') as QUA'),
            DB::raw('IF(curriculos.qui=\'s\', \'Sim\', \'Não\') as QUI'),
            DB::raw('IF(curriculos.sex=\'s\', \'Sim\', \'Não\') as SEX'),
            DB::raw('IF(curriculos.sab=\'s\', \'Sim\', \'Não\') as SAB'),
            DB::raw('IF(curriculos.dom=\'s\', \'Sim\', \'Não\') as DOM'),


            DB::raw('IF(curriculos.deficiente=\'s\', \'Sim\', \'Não\') as Deficiente'),

            DB::raw('IF(curriculos.protadordoencas=\'s\', \'Sim\', \'Não\') as Portador_Doenca_Cronica'),
            'curriculos.qualdoenca as Quais_Doencas',
            'curriculos.outrasdoenca as Outras_Doencas',

            DB::raw('IF(curriculos.gestante=\'s\', \'Sim\', \'Não\') as Gestante_Lactante'),

            DB::raw('IF(curriculos.temparente=\'s\', \'Sim\', \'Não\') as Tem_Parente_PMC'),
            'curriculos.nomeparente as Nome_Parente_PMC',
        );

        if (request()->has('codigo')){
            if (request('codigo') != ""){
                $curriculos = $curriculos->where('curriculos.id', '=', request('codigo'));
            }
        }

        if (request()->has('nome')){
            $curriculos = $curriculos->where('curriculos.nome', 'like', '%' . request('nome') . '%');
        }

        if (request()->has('cidade')){
            $curriculos = $curriculos->where('curriculos.cidade', 'like', '%' . request('cidade') . '%');
        }

        if (request()->has('cargo_id')){
            if (request('cargo_id') != ""){
                $curriculos = $curriculos->where('curriculos.cargo_id', '=', request('cargo_id'));
            }
        } 

        $curriculos = $curriculos->orderBy('curriculos.id', 'asc');

        $list = $curriculos->get()->toArray();

        # converte os objetos para uma array
        $list = json_decode(json_encode($list), true);

        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

       $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            fputs($FH, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            foreach ($list as $row) {
                fputcsv($FH, $row, chr(9));
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
    } 

    /**
     * Exportação para pdf
     *
     * @param  
     * @return 
     */
    public function exportpdf()
    {
        if (Gate::denies('cargo-export')) {
            abort(403, 'Acesso negado.');
        }

        $this->pdf->AliasNbPages();   
        $this->pdf->SetMargins(12, 10, 12);
        $this->pdf->SetFont('Arial', '', 12);
        $this->pdf->AddPage();

        $curriculos = DB::table('curriculos');

        //joins
        $curriculos = $curriculos->join('cargos', 'cargos.id', '=', 'curriculos.cargo_id');

        $curriculos = $curriculos->select(
            'curriculos.id as codigo', 
            DB::raw('DATE_FORMAT(curriculos.created_at, \'%d/%m/%Y\') AS data_cadastro'), 
            DB::raw('DATE_FORMAT(curriculos.created_at, \'%H:%i\') AS hora_cadastro'),
            'curriculos.nome',
            DB::raw('DATE_FORMAT(curriculos.nascimento, \'%d/%m/%Y\') AS data_nascimento'), 
            DB::raw('IF(curriculos.genero=\'f\', \'Feminino\', \'Masculino\') as genero'),
            'curriculos.cpf',
            'curriculos.email',
            'curriculos.cel1',
            'curriculos.cel2',
            'curriculos.tel',
            'curriculos.cep',
            'curriculos.logradouro',
            'curriculos.numero',
            'curriculos.complemento',
            'curriculos.bairro',
            'curriculos.cidade',
            'curriculos.uf',
            DB::raw('IF(curriculos.origemcontagem=\'s\', \'Sim\', \'Não\') as Reside_em_Contagem'),
            'cargos.descricao as cargo',
            'curriculos.registro',
            DB::raw('IF(curriculos.c20h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_20h'),
            DB::raw('IF(curriculos.c24h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_24h'),
            DB::raw('IF(curriculos.c30h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_30h'),
            DB::raw('IF(curriculos.c40h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_40h'),
            DB::raw('IF(curriculos.turnomanha=\'s\', \'Sim\', \'Não\') as Turno_Manha'),
            DB::raw('IF(curriculos.turnotarde=\'s\', \'Sim\', \'Não\') as Turno_Tarde'),
            DB::raw('IF(curriculos.turnonoite=\'s\', \'Sim\', \'Não\') as Turno_Noite'),

            DB::raw('IF(curriculos.seg=\'s\', \'Sim\', \'Não\') as SEG'),
            DB::raw('IF(curriculos.ter=\'s\', \'Sim\', \'Não\') as TER'),
            DB::raw('IF(curriculos.qua=\'s\', \'Sim\', \'Não\') as QUA'),
            DB::raw('IF(curriculos.qui=\'s\', \'Sim\', \'Não\') as QUI'),
            DB::raw('IF(curriculos.sex=\'s\', \'Sim\', \'Não\') as SEX'),
            DB::raw('IF(curriculos.sab=\'s\', \'Sim\', \'Não\') as SAB'),
            DB::raw('IF(curriculos.dom=\'s\', \'Sim\', \'Não\') as DOM'),


            DB::raw('IF(curriculos.deficiente=\'s\', \'Sim\', \'Não\') as Deficiente'),

            DB::raw('IF(curriculos.protadordoencas=\'s\', \'Sim\', \'Não\') as Portador_Doenca_Cronica'),
            'curriculos.qualdoenca as Quais_Doencas',
            'curriculos.outrasdoenca as Outras_Doencas',

            DB::raw('IF(curriculos.gestante=\'s\', \'Sim\', \'Não\') as Gestante_Lactante'),

            DB::raw('IF(curriculos.temparente=\'s\', \'Sim\', \'Não\') as Tem_Parente_PMC'),
            'curriculos.nomeparente as Nome_Parente_PMC',
        );

        if (request()->has('codigo')){
            if (request('codigo') != ""){
                $curriculos = $curriculos->where('curriculos.id', '=', request('codigo'));
            }
        }

        if (request()->has('nome')){
            $curriculos = $curriculos->where('curriculos.nome', 'like', '%' . request('nome') . '%');
        }

        if (request()->has('cidade')){
            $curriculos = $curriculos->where('curriculos.cidade', 'like', '%' . request('cidade') . '%');
        }

        if (request()->has('cargo_id')){
            if (request('cargo_id') != ""){
                $curriculos = $curriculos->where('curriculos.cargo_id', '=', request('cargo_id'));
            }
        } 

        $curriculos = $curriculos->orderBy('curriculos.id', 'asc');  


        $curriculos = $curriculos->get();


        // configurações do relatório
        $this->pdf->AliasNbPages();   
        $this->pdf->SetMargins(12, 10, 12);
        $this->pdf->SetFont('Arial', '', 11);
        $nao_adicionar_pagina_inicio = false;
        foreach ($curriculos as $curriculo) {

            if ($nao_adicionar_pagina_inicio){
                $this->pdf->AddPage();    
            } else {
                $nao_adicionar_pagina_inicio = true;
            }   
            
            $this->pdf->Cell(40, 6, utf8_decode('Código'), 1, 0,'R');
            $this->pdf->Cell(28, 6, utf8_decode('Data'), 1, 0,'L');
            $this->pdf->Cell(18, 6, utf8_decode('Hora'), 1, 0,'L');
            $this->pdf->Cell(100, 6, utf8_decode('Nome'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(40, 6, utf8_decode($curriculo->codigo), 1, 0,'R');
            $this->pdf->Cell(28, 6, utf8_decode($curriculo->data_cadastro), 1, 0,'L');
            $this->pdf->Cell(18, 6, utf8_decode($curriculo->hora_cadastro), 1, 0,'L');
            $this->pdf->Cell(100, 6, utf8_decode($curriculo->nome), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(50, 6, utf8_decode('Data de Nascimento'), 1, 0,'L');
            $this->pdf->Cell(60, 6, utf8_decode('Gênero'), 1, 0,'L');
            $this->pdf->Cell(76, 6, utf8_decode('CPF'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(50, 6, utf8_decode($curriculo->data_nascimento), 1, 0,'L');
            $this->pdf->Cell(60, 6, utf8_decode($curriculo->genero), 1, 0,'L');
            $this->pdf->Cell(76, 6, utf8_decode($curriculo->cpf), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(186, 6, utf8_decode('E-mail'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(186, 6, utf8_decode($curriculo->email), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(62, 6, utf8_decode('Celular'), 1, 0,'L');
            $this->pdf->Cell(62, 6, utf8_decode('Celular Alternativo'), 1, 0,'L');
            $this->pdf->Cell(62, 6, utf8_decode('Telefone Fixo'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(62, 6, utf8_decode($curriculo->cel1), 1, 0,'L');
            $this->pdf->Cell(62, 6, utf8_decode($curriculo->cel2), 1, 0,'L');
            $this->pdf->Cell(62, 6, utf8_decode($curriculo->tel), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(26, 6, utf8_decode('CEP'), 1, 0,'L');
            $this->pdf->Cell(80, 6, utf8_decode('Logradouro'), 1, 0,'L');
            $this->pdf->Cell(20, 6, utf8_decode('Nº'), 1, 0,'L');
            $this->pdf->Cell(60, 6, utf8_decode('Complemento'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(26, 6, utf8_decode($curriculo->cep), 1, 0,'L');
            $this->pdf->Cell(80, 6, utf8_decode($curriculo->logradouro), 1, 0,'L');
            $this->pdf->Cell(20, 6, utf8_decode($curriculo->numero), 1, 0,'L');
            $this->pdf->Cell(60, 6, utf8_decode($curriculo->complemento), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(70, 6, utf8_decode('Bairro'), 1, 0,'L');
            $this->pdf->Cell(66, 6, utf8_decode('Cidade'), 1, 0,'L');
            $this->pdf->Cell(20, 6, utf8_decode('UF'), 1, 0,'L');
            $this->pdf->Cell(30, 6, utf8_decode('De Contagem'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(70, 6, utf8_decode($curriculo->bairro), 1, 0,'L');
            $this->pdf->Cell(66, 6, utf8_decode($curriculo->cidade), 1, 0,'L');
            $this->pdf->Cell(20, 6, utf8_decode($curriculo->uf), 1, 0,'L');
            $this->pdf->Cell(30, 6, utf8_decode($curriculo->Reside_em_Contagem), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(120, 6, utf8_decode('Cargo'), 1, 0,'L');
            $this->pdf->Cell(66, 6, utf8_decode('Registro do Conselho'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(120, 6, utf8_decode($curriculo->cargo), 1, 0,'L');
            $this->pdf->Cell(66, 6, utf8_decode($curriculo->registro), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(96, 6, utf8_decode('Disponibilidade de Carga Horária Semanal'), 1, 0,'L');
            $this->pdf->Cell(90, 6, utf8_decode('Disponibilidade de Turno'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(24, 6, utf8_decode('20h'), 1, 0,'L');
            $this->pdf->Cell(24, 6, utf8_decode('24h'), 1, 0,'L');
            $this->pdf->Cell(24, 6, utf8_decode('30h'), 1, 0,'L');
            $this->pdf->Cell(24, 6, utf8_decode('40h'), 1, 0,'L');
            $this->pdf->Cell(30, 6, utf8_decode('Manhã'), 1, 0,'L');
            $this->pdf->Cell(30, 6, utf8_decode('Tarde'), 1, 0,'L');
            $this->pdf->Cell(30, 6, utf8_decode('Noite'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(24, 6, utf8_decode($curriculo->Carga_Horaria_20h), 1, 0,'L');
            $this->pdf->Cell(24, 6, utf8_decode($curriculo->Carga_Horaria_24h), 1, 0,'L');
            $this->pdf->Cell(24, 6, utf8_decode($curriculo->Carga_Horaria_30h), 1, 0,'L');
            $this->pdf->Cell(24, 6, utf8_decode($curriculo->Carga_Horaria_40h), 1, 0,'L');
            $this->pdf->Cell(30, 6, utf8_decode($curriculo->Turno_Manha), 1, 0,'L');
            $this->pdf->Cell(30, 6, utf8_decode($curriculo->Turno_Tarde), 1, 0,'L');
            $this->pdf->Cell(30, 6, utf8_decode($curriculo->Turno_Noite), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(154, 6, utf8_decode('Disponibilidade Dias da Semana'), 1, 0,'L');
            $this->pdf->Cell(32, 6, '', 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(22, 6, utf8_decode('SEG'), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode('TER'), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode('QUA'), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode('QUI'), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode('SEX'), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode('SAB'), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode('DOM'), 1, 0,'L');
            $this->pdf->Cell(32, 6, '', 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(22, 6, utf8_decode($curriculo->SEG), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode($curriculo->TER), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode($curriculo->QUA), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode($curriculo->QUI), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode($curriculo->SEX), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode($curriculo->SAB), 1, 0,'L');
            $this->pdf->Cell(22, 6, utf8_decode($curriculo->DOM), 1, 0,'L');
            $this->pdf->Cell(32, 6, '', 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(45, 6, utf8_decode('Portador de Deficiência'), 1, 0,'L');
            $this->pdf->Cell(45, 6, utf8_decode('Doenças Crônicas?'), 1, 0,'L');
            $this->pdf->Cell(96, 6, utf8_decode('Quais Doenças Crônicas?'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(45, 6, utf8_decode($curriculo->Deficiente), 1, 0,'L');
            $this->pdf->Cell(45, 6, utf8_decode($curriculo->Portador_Doenca_Cronica), 1, 0,'L');
            $this->pdf->Cell(96, 6, utf8_decode($curriculo->Quais_Doencas), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(120, 6, utf8_decode('Outras Doenças'), 1, 0,'L');
            $this->pdf->Cell(66, 6, utf8_decode('Gestante/Lactante'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(120, 6, utf8_decode($curriculo->Outras_Doencas), 1, 0,'L');
            $this->pdf->Cell(66, 6, utf8_decode($curriculo->Gestante_Lactante), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(66, 6, utf8_decode('Possui Parente na PMC'), 1, 0,'L');
            $this->pdf->Cell(120, 6, utf8_decode('Nome do Parente'), 1, 0,'L');
            $this->pdf->Ln();
            $this->pdf->Cell(66, 6, utf8_decode($curriculo->Tem_Parente_PMC), 1, 0,'L');
            $this->pdf->Cell(120, 6, utf8_decode($curriculo->Nome_Parente_PMC), 1, 0,'L');
            $this->pdf->Ln();


            
        }

        $this->pdf->Output('D', 'Curriculos_' .  date("d-m-Y H:i:s") . '.pdf', true);
        exit;

    }

    /**
     * Exportação para pdf por protocolo
     *
     * @param  $id, id do protocolo
     * @return pdf
     */
    public function exportpdfindividual($id)
    {

                $curriculo = DB::table('curriculos');

        //joins
        $curriculo = $curriculo->join('cargos', 'cargos.id', '=', 'curriculos.cargo_id');

        $curriculo = $curriculo->select(
            'curriculos.id as codigo', 
            DB::raw('DATE_FORMAT(curriculos.created_at, \'%d/%m/%Y\') AS data_cadastro'), 
            DB::raw('DATE_FORMAT(curriculos.created_at, \'%H:%i\') AS hora_cadastro'),
            'curriculos.nome',
            DB::raw('DATE_FORMAT(curriculos.nascimento, \'%d/%m/%Y\') AS data_nascimento'), 
            DB::raw('IF(curriculos.genero=\'f\', \'Feminino\', \'Masculino\') as genero'),
            'curriculos.cpf',
            'curriculos.email',
            'curriculos.cel1',
            'curriculos.cel2',
            'curriculos.tel',
            'curriculos.cep',
            'curriculos.logradouro',
            'curriculos.numero',
            'curriculos.complemento',
            'curriculos.bairro',
            'curriculos.cidade',
            'curriculos.uf',
            DB::raw('IF(curriculos.origemcontagem=\'s\', \'Sim\', \'Não\') as Reside_em_Contagem'),
            'cargos.descricao as cargo',
            'curriculos.registro',
            DB::raw('IF(curriculos.c20h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_20h'),
            DB::raw('IF(curriculos.c24h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_24h'),
            DB::raw('IF(curriculos.c30h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_30h'),
            DB::raw('IF(curriculos.c40h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_40h'),
            DB::raw('IF(curriculos.turnomanha=\'s\', \'Sim\', \'Não\') as Turno_Manha'),
            DB::raw('IF(curriculos.turnotarde=\'s\', \'Sim\', \'Não\') as Turno_Tarde'),
            DB::raw('IF(curriculos.turnonoite=\'s\', \'Sim\', \'Não\') as Turno_Noite'),

            DB::raw('IF(curriculos.seg=\'s\', \'Sim\', \'Não\') as SEG'),
            DB::raw('IF(curriculos.ter=\'s\', \'Sim\', \'Não\') as TER'),
            DB::raw('IF(curriculos.qua=\'s\', \'Sim\', \'Não\') as QUA'),
            DB::raw('IF(curriculos.qui=\'s\', \'Sim\', \'Não\') as QUI'),
            DB::raw('IF(curriculos.sex=\'s\', \'Sim\', \'Não\') as SEX'),
            DB::raw('IF(curriculos.sab=\'s\', \'Sim\', \'Não\') as SAB'),
            DB::raw('IF(curriculos.dom=\'s\', \'Sim\', \'Não\') as DOM'),


            DB::raw('IF(curriculos.deficiente=\'s\', \'Sim\', \'Não\') as Deficiente'),

            DB::raw('IF(curriculos.protadordoencas=\'s\', \'Sim\', \'Não\') as Portador_Doenca_Cronica'),
            'curriculos.qualdoenca as Quais_Doencas',
            'curriculos.outrasdoenca as Outras_Doencas',

            DB::raw('IF(curriculos.gestante=\'s\', \'Sim\', \'Não\') as Gestante_Lactante'),

            DB::raw('IF(curriculos.temparente=\'s\', \'Sim\', \'Não\') as Tem_Parente_PMC'),
            'curriculos.nomeparente as Nome_Parente_PMC',
        );
 
        $curriculo = $curriculo->where('curriculos.id', '=', $id);


        $curriculo = $curriculo->get()->first();


        // configura o relatório
        $this->pdf->AliasNbPages();   
        $this->pdf->SetMargins(12, 10, 12);
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->AddPage();

        $this->pdf->Cell(40, 6, utf8_decode('Código'), 1, 0,'R');
        $this->pdf->Cell(28, 6, utf8_decode('Data'), 1, 0,'L');
        $this->pdf->Cell(18, 6, utf8_decode('Hora'), 1, 0,'L');
        $this->pdf->Cell(100, 6, utf8_decode('Nome'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(40, 6, utf8_decode($curriculo->codigo), 1, 0,'R');
        $this->pdf->Cell(28, 6, utf8_decode($curriculo->data_cadastro), 1, 0,'L');
        $this->pdf->Cell(18, 6, utf8_decode($curriculo->hora_cadastro), 1, 0,'L');
        $this->pdf->Cell(100, 6, utf8_decode($curriculo->nome), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(50, 6, utf8_decode('Data de Nascimento'), 1, 0,'L');
        $this->pdf->Cell(60, 6, utf8_decode('Gênero'), 1, 0,'L');
        $this->pdf->Cell(76, 6, utf8_decode('CPF'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(50, 6, utf8_decode($curriculo->data_nascimento), 1, 0,'L');
        $this->pdf->Cell(60, 6, utf8_decode($curriculo->genero), 1, 0,'L');
        $this->pdf->Cell(76, 6, utf8_decode($curriculo->cpf), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(186, 6, utf8_decode('E-mail'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(186, 6, utf8_decode($curriculo->email), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(62, 6, utf8_decode('Celular'), 1, 0,'L');
        $this->pdf->Cell(62, 6, utf8_decode('Celular Alternativo'), 1, 0,'L');
        $this->pdf->Cell(62, 6, utf8_decode('Telefone Fixo'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(62, 6, utf8_decode($curriculo->cel1), 1, 0,'L');
        $this->pdf->Cell(62, 6, utf8_decode($curriculo->cel2), 1, 0,'L');
        $this->pdf->Cell(62, 6, utf8_decode($curriculo->tel), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(26, 6, utf8_decode('CEP'), 1, 0,'L');
        $this->pdf->Cell(80, 6, utf8_decode('Logradouro'), 1, 0,'L');
        $this->pdf->Cell(20, 6, utf8_decode('Nº'), 1, 0,'L');
        $this->pdf->Cell(60, 6, utf8_decode('Complemento'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(26, 6, utf8_decode($curriculo->cep), 1, 0,'L');
        $this->pdf->Cell(80, 6, utf8_decode($curriculo->logradouro), 1, 0,'L');
        $this->pdf->Cell(20, 6, utf8_decode($curriculo->numero), 1, 0,'L');
        $this->pdf->Cell(60, 6, utf8_decode($curriculo->complemento), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(70, 6, utf8_decode('Bairro'), 1, 0,'L');
        $this->pdf->Cell(66, 6, utf8_decode('Cidade'), 1, 0,'L');
        $this->pdf->Cell(20, 6, utf8_decode('UF'), 1, 0,'L');
        $this->pdf->Cell(30, 6, utf8_decode('De Contagem'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(70, 6, utf8_decode($curriculo->bairro), 1, 0,'L');
        $this->pdf->Cell(66, 6, utf8_decode($curriculo->cidade), 1, 0,'L');
        $this->pdf->Cell(20, 6, utf8_decode($curriculo->uf), 1, 0,'L');
        $this->pdf->Cell(30, 6, utf8_decode($curriculo->Reside_em_Contagem), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(120, 6, utf8_decode('Cargo'), 1, 0,'L');
        $this->pdf->Cell(66, 6, utf8_decode('Registro do Conselho'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(120, 6, utf8_decode($curriculo->cargo), 1, 0,'L');
        $this->pdf->Cell(66, 6, utf8_decode($curriculo->registro), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(96, 6, utf8_decode('Disponibilidade de Carga Horária Semanal'), 1, 0,'L');
        $this->pdf->Cell(90, 6, utf8_decode('Disponibilidade de Turno'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(24, 6, utf8_decode('20h'), 1, 0,'L');
        $this->pdf->Cell(24, 6, utf8_decode('24h'), 1, 0,'L');
        $this->pdf->Cell(24, 6, utf8_decode('30h'), 1, 0,'L');
        $this->pdf->Cell(24, 6, utf8_decode('40h'), 1, 0,'L');
        $this->pdf->Cell(30, 6, utf8_decode('Manhã'), 1, 0,'L');
        $this->pdf->Cell(30, 6, utf8_decode('Tarde'), 1, 0,'L');
        $this->pdf->Cell(30, 6, utf8_decode('Noite'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(24, 6, utf8_decode($curriculo->Carga_Horaria_20h), 1, 0,'L');
        $this->pdf->Cell(24, 6, utf8_decode($curriculo->Carga_Horaria_24h), 1, 0,'L');
        $this->pdf->Cell(24, 6, utf8_decode($curriculo->Carga_Horaria_30h), 1, 0,'L');
        $this->pdf->Cell(24, 6, utf8_decode($curriculo->Carga_Horaria_40h), 1, 0,'L');
        $this->pdf->Cell(30, 6, utf8_decode($curriculo->Turno_Manha), 1, 0,'L');
        $this->pdf->Cell(30, 6, utf8_decode($curriculo->Turno_Tarde), 1, 0,'L');
        $this->pdf->Cell(30, 6, utf8_decode($curriculo->Turno_Noite), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(154, 6, utf8_decode('Disponibilidade Dias da Semana'), 1, 0,'L');
        $this->pdf->Cell(32, 6, '', 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(22, 6, utf8_decode('SEG'), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode('TER'), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode('QUA'), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode('QUI'), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode('SEX'), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode('SAB'), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode('DOM'), 1, 0,'L');
        $this->pdf->Cell(32, 6, '', 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(22, 6, utf8_decode($curriculo->SEG), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode($curriculo->TER), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode($curriculo->QUA), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode($curriculo->QUI), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode($curriculo->SEX), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode($curriculo->SAB), 1, 0,'L');
        $this->pdf->Cell(22, 6, utf8_decode($curriculo->DOM), 1, 0,'L');
        $this->pdf->Cell(32, 6, '', 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(45, 6, utf8_decode('Portador de Deficiência'), 1, 0,'L');
        $this->pdf->Cell(45, 6, utf8_decode('Doenças Crônicas?'), 1, 0,'L');
        $this->pdf->Cell(96, 6, utf8_decode('Quais Doenças Crônicas?'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(45, 6, utf8_decode($curriculo->Deficiente), 1, 0,'L');
        $this->pdf->Cell(45, 6, utf8_decode($curriculo->Portador_Doenca_Cronica), 1, 0,'L');
        $this->pdf->Cell(96, 6, utf8_decode($curriculo->Quais_Doencas), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(120, 6, utf8_decode('Outras Doenças'), 1, 0,'L');
        $this->pdf->Cell(66, 6, utf8_decode('Gestante/Lactante'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(120, 6, utf8_decode($curriculo->Outras_Doencas), 1, 0,'L');
        $this->pdf->Cell(66, 6, utf8_decode($curriculo->Gestante_Lactante), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(66, 6, utf8_decode('Possui Parente na PMC'), 1, 0,'L');
        $this->pdf->Cell(120, 6, utf8_decode('Nome do Parente'), 1, 0,'L');
        $this->pdf->Ln();
        $this->pdf->Cell(66, 6, utf8_decode($curriculo->Tem_Parente_PMC), 1, 0,'L');
        $this->pdf->Cell(120, 6, utf8_decode($curriculo->Nome_Parente_PMC), 1, 0,'L');
        $this->pdf->Ln();


        $this->pdf->Output('D', 'Curriculo_' . $curriculo->nome . '_' . date("d-m-Y H:i:s") . '.pdf', true);
        exit;
    }    
}
