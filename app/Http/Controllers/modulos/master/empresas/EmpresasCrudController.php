<?php

namespace App\Http\Controllers\modulos\master\empresas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Empresas;
use App\API\ApiErros;
use App\API\ValidaRequests;
use App\Support\Email\Email;


class EmpresasCrudController extends Controller
{
    //lista todos
    public function index(){
        $query = DB::table('empresas')->join('cidades','empresas.cidade_id','=','cidades.id')
            ->select('empresas.id','empresas.razao_social','empresas.cnpj','empresas.situacao','empresas.bairro','empresas.rua',
                'empresas.cep','empresas.taxaEntrega','empresas.tempoEntrega','empresas.categoria','empresas.telefone',
                'empresas.celular','empresas.email','empresas.instagram','empresas.numero','cidades.nome')
            ->orderBy('empresas.razao_social','asc')
            ->paginate(10);
        return response()->json( $query,200);
    }

    //buscar um registro
    public function show(Empresas $id){
        try {
            $empresa_id = $id->id;
            $query = DB::table('empresas')->join('cidades','empresas.cidade_id','=','cidades.id')
                ->select('empresas.razao_social','empresas.cnpj','empresas.situacao','empresas.categoria','cidades.nome')
                ->where('empresas.id',$empresa_id)
                ->first();
            return response()->json( $query,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1026));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os dados da empresa',1026));
        }
    }

    //inserindo registro
    public function store(Request $request){
        $cnpjCadastrado = DB::table('empresas')->where('cnpj', $request->cnpj)->value('cnpj');
        if($cnpjCadastrado != null){
            return response()->json(['Resposta' => "CNPJ já cadastrado!"],401);
        }else{
            $retorno = ValidaRequests::validaCadastroEmpresa($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
             try{
                $empresa = new Empresas([
                    'razao_social' => $request->razao_social,
                    'cnpj' => $request->cnpj,
                    'situacao' => $request->situacao,
                    'cidade_id' => $request->cidade_id,
                    'bairro' => $request->bairro,
                    'rua' => $request->rua,
                    'cep' => $request->cep,
                    'categoria' => $request->categoria,
                    'telefone' => $request->telefone,
                    'celular' => $request->celular,
                    'email' => $request->email,
                    'instagram' => $request->instagram,
                    'taxaEntrega' => $request->taxaEntrega,
                    'tempoEntrega' => $request->tempoEntrega,
                    'numero' => $request->numero

                ]);
                $empresa->save();
                if ( !empty($empresa->email) ){
                    //mensagem com anexo
                    $email = new Email();
                    $email->add(
                        "Cadastro realizado",
                        "<h1>A empresa $empresa->razao_social</h1>" .
                        "<b>Foi cadastrada com sucesso!!</b> <p> A empresa $empresa->razao_social,
                        acaba de ser cadastrada com sucesso na plataforma familyFoods. Agora seu negocío é integrante de uma
                        das mais novas e avançadas soluções para o ramo de delivery da sua região. Desde já
                        desejamos sucesso no seu negócio.
                        Para maiores informações, entre em contato pelos seguintes meios:
                        <ul>
                            <li><b>Email</b>: PlataformaFamilyFoods@gmail.com</li>
                            <li><b>Fone</b>: (88) 9.9615-7492</li>
                            <li><b>Instagram</b>: @familyFoods</li>
                        </ul>",
                        "$empresa->razao_social",
                        "$empresa->email"
                    );

                    $email->send();
                }
                return response()->json([
                    'resposta' => "Empresa cadastrada com sucesso!"
                ],201);
            }catch(\Exception $e){
                //para opção de debug
                if(config('app.debug')){
                    return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1010));
                }
                    //para opção de produção
                    return response()->
                    json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao realizar o cadastro da empresa, por favor tente novamente',1010));
            }

        }

    }

    //atualizando uma empresa
    public function update(Request $request, $id){
        $testeRetorno = " Empresa Atualizado com sucesso!";
        $retorno = ValidaRequests::validaAtualizaEmpresa($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            try{
                Empresas::where('id', $id)->update($request->except('id'));
                return response()->json($testeRetorno,200);
            }catch(\Exception $e){
                //para opção de debug
                if(config('app.debug')){
                    return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1011));
                }
                    //para opção de produção
                    return response()->
                    json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao realizar a atualização neste empresa',1011));
            }
    }

    //Torna Inativa uma empresa
    public function updateSituacao(Empresas $empresas){
        try {
            $id = $empresas->id;
            $empresa = Empresas::find($id);
            $empresa->situacao = 'Inativa';
            $empresa->save();
            return response()->json(['data' => 'Empresa atualizada como Inativa'],200);
        }catch (\Exception $e){
            //para opção de debug
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1025));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao realizar a atualização neste empresa',1025));
        }
    }

    //apagando empresa
    public function delete(Empresas $id){
        try{
            $retornoSucesso = " foi removida com sucesso";
            $id->delete();
            return response()->json(['data'=>'Empresa removida com sucesso.'],200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()
                ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1012));
            }
                //para opção de produção
                return response()->
                json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao apagar empresa',1012));
        }
    }

    public function extraParaExibirDadosCompletos(Empresas $empresa){
        foreach ($empresa->cidade() as $cidades)
        $cidade = $empresa->cidade->nome;
        $estado = $empresa->cidade->estado->nome;
        return response()->json( ['data' => $empresa]);

    }
}
