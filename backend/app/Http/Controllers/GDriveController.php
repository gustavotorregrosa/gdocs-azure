<?php

namespace App\Http\Controllers;

use App\GToken;
use CURLFile;

class GDriveController extends Controller
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

    public static function getArquivos()
    {
        $objToken = new GToken;
        $token = $objToken->getAccessToken();
        $token = "Bearer " . $token;
        $headers = "Authorization: " . $token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/drive/v3/files");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [$headers]);
        $output = curl_exec($ch);
        var_dump($output);
        curl_close($ch);
    }


    public static function salvaArquivo($arquivo)
    {
        $objToken = new GToken;
        $token = $objToken->getAccessToken();
        $token = "Bearer " . $token;
        $headers = ["Authorization: " . $token, "uploadType: media", "mimeType: application/pdf"];


        $ch = curl_init();
        $caminho = "arquivos/" . $arquivo;
        $tipo = 'application/pdf';
        $arquivoUpload = new CURLFile($caminho, $tipo, "meuarquivo.pdf");
        $params = [
            'mimeType' => 'application/pdf',
            'data' => $arquivoUpload,
            'fields' => 'id'

        ];
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/upload/drive/v3/files");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        $output = json_decode($output);
        curl_close($ch);
        return $output->id;
    }

    public static function getArquivo($arquivo)
    {
        $idGDocs = $arquivo->idGdocs; 
        $objToken = new GToken;
        $token = $objToken->getAccessToken();
        $token = "Bearer " . $token;
        $headers = "Authorization: " . $token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/drive/v3/files/".$idGDocs."?alt=media");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [$headers]);
        $output = curl_exec($ch);
        $nome = str_replace(' ', '', $arquivo->nomeoriginal);

        $fp = fopen("arquivosNuvem/".$nome, "w");
        fwrite($fp, $output);
        curl_close($ch);
        return $nome;
        

    }
}
