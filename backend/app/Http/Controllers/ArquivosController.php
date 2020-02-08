<?php

namespace App\Http\Controllers;

use App\Arquivo;
use Illuminate\Http\Request;
use App\GToken;


class ArquivosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(){
        $arquivos = Arquivo::all();

        return response()->json($arquivos);
    }

    public function delete($id){
        $arquivo = Arquivo::find($id);
        unlink($_SERVER["DOCUMENT_ROOT"]."/arquivos/".$arquivo->nome);
        $arquivo->delete();
        return response("Arquivo deletado", 200);    
    }

    public function teste(){
        GDriveController::getArquivos();

    }

    public function getArquivoGDocs(Request $request, $id){
        $arquivo = Arquivo::find($id);
        $nome = GDriveController::getArquivo($arquivo);
        $novaURL = $request->root()."/arquivosNuvem/".$nome;
        return redirect($novaURL);
    }
    
    public function geraNome($nomeatual)
    {
        $nomeSemExtensao = $nomeatual;
        $posicaoPonto = strpos($nomeatual, ".");
        $extensao = "";
        if ($posicaoPonto) {
            $nomeSemExtensao = substr($nomeatual, 0, $posicaoPonto);
            $extensao = substr($nomeatual, $posicaoPonto, strlen($nomeatual));
        }
        $nomeNovo = md5($nomeSemExtensao);
        $nomeNovo .= time();
        if ($posicaoPonto) {
            $nomeNovo .= $extensao;
        }
        return $nomeNovo;
    }

    public function salvarUnit($arq)
    {
        $nome = $this->geraNome($arq['name']);
        $fp = fopen("arquivos/".$nome, "w");
        $data = base64_decode($arq['base64']);
        fwrite($fp, $data);
        $idGdocs = GDriveController::salvaArquivo($nome);
       
        fclose($fp);
        return [
            'nome' => $nome,
            'conteudo' => $data,
            'idGdocs' => $idGdocs
        ];
    }

    public function salvar(Request $request)
    {
        $arquivos = $request->input('arquivos');
        $listaNomes = [];
        $listaNomesConteudo = [];
        foreach($arquivos as $a){
            $arrayTempParamArquivo = $this->salvarUnit($a);
           
            $listaNomesConteudo[] = $arrayTempParamArquivo;
            $listaNomes[] = [
                'nomeoriginal' => $a['name'],
                'nome' => $arrayTempParamArquivo['nome'],
                'idGdocs' => $arrayTempParamArquivo['idGdocs']
            ];
        }

        foreach($listaNomes as $a){
            Arquivo::create($a);
        }

        return response()->json($listaNomes);
    }
}
