<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Horario extends CI_Controller {

    private $codigo;
    private $descricao;
    private $horaInicial;
    private $horaFinal;
    private $estatus;

    // Getters dos atributos

    public function getCodigo(){
        return $this->codigo;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function getHoraInicial(){
        return $this->horaInicial;
    }

    public function getHoraFinal(){
        return $this->horaFinal;
    }

    public function getEstatus(){
        return $this->estatus;
    }

    //Setters dos atributos 
    public function setCodigo($codigoFront){
        $this->codigo = $codigoFront;
    }

    public function setDescricao($descricaoFront){
        $this->descricao = $descricaoFront;
    }

    public function setHoraInicial($horaInicialFront){
        $this->horaInicial = $horaInicialFront;
    }

    public function setHoraFinal($horaFinalFront){
        $this->horaFinal = $horaFinalFront;
    }

    public function setEstatus($estatusFront){
        $this->estatus = $estatusFront;
    }

    public function inserir() {
        //Atributos para controlar o status do nosso método
        $erros = [];
        $sucesso = false;
        //$retorno =[];

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "descricao" => '0',
                "horaInicial" => '0',
                "horaFinal" => '0'
            ];

            if (verificarParam($resultado, $lista) != 1){
                //Validar vindos de forma correta de frontend (Helper)
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];                
            }else{
                //Validade campos quanto ao tipo de dados e tamanho (Helper)
                $retornoDescricao = validarDados($resultado->descricao, 'string', true);
                $retornoHoraInicial = validarDados($resultado->horaInicial, 'hora', true);
                $retornoHoraFinal = validarDados($resultado->horaFinal, 'hora', true);
                $retornoComparacaoHoras = compararDataHora($resultado->horaInicial,
                                                           $resultado->horaFinal, 'hora');

                if($retornoDescricao['codigoHelper'] !=0){
                    $erros[] = ['codigo'=> $retornoDescricao['codigoHelper'],
                                'campo' => 'Descricao',
                                'msg' => $retornoDescricao['msg']];
                }

                if($retornoHoraInicial['codigoHelper'] !=0){
                    $erros[] = ['codigo'=> $retornoHoraInicial['codigoHelper'],
                                'campo' => 'Hora Inicial',
                                'msg' => $retornoHoraInicial['msg']];
                }

                if($retornoHoraFinal['codigoHelper'] !=0){
                    $erros[] = ['codigo'=> $retornoHoraFinal['codigoHelper'],
                                'campo' => 'Hora Final',
                                'msg' => $retornoHoraFinal['msg']];
                }

                
                if($retornoComparacaoHoras['codigoHelper'] !=0){
                    $erros[] = ['codigo'=> $retornoComparacaoHoras['codigoHelper'],
                                'campo' => 'Hora Inicial e Hora Final',
                                'msg' => $retornoComparacaoHoras['msg']];
                }

                // Se não encontrar erros
                if (empty($erros)){
                    $this->setDescricao($resultado->descricao);
                    $this->setHoraInicial($resultado->horaInicial);
                    $this->setHoraFinal($resultado->horaFinal);

                    $this->load->model('M_horario');
                    $resBanco = $this->M_horario->inserir(   
                        $this->getDescricao(),
                        $this->getHoraInicial(),
                        $this->getHoraFinal()
                    );

                    if ($resBanco['codigo']== 1){
                        $sucesso = true;
                    }else {
                        // Captura erro do banco
                        $erros[] = [
                            'codigo'=> $resBanco['codigo'],
                            'msg'=> $resBanco['msg'],
                        ];
                    }                    
                }
            }            
        }catch(Exception $e){
            $erros[] = ['codigo'=> 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }
        
        // Monta retorno unico
        if ($sucesso == true){
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'],
                        'msg' => $resBanco['msg']];
        }else{
            $retorno = ['sucesso'=> $sucesso, 'erros' => $erros];
        }

        // Tranforma o array em JSON
        echo json_encode($retorno);
    }

    public function consultar()
    {
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "codigo" => '0',
                "descricao" => '0',
                "horaInicial" => '0',
                "horaFinal" => '0',
            ];

            if (verificarParam($resultado, $lista) != 1) {
                //Validar vindos de forma correta do frontend (Helper)
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {
                //Validar campos quanto ao tipo de dado e tamanho (Helper)
                $retornoCodigo = validarDadosConsulta($resultado->codigo, 'int');
                $retornoDescricao = validarDadosConsulta($resultado->descricao, 'string');
                $retornoHoraInicial = validarDadosConsulta($resultado->horaInicial, 'hora');
                $retornoHoraFinal = validarDadosConsulta($resultado->horaFinal, 'hora');
                $retornoComparacaoHoras = compararDataHora($resultado->horaInicial,
                                                           $resultado->horaFinal, 'hora');

                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']];
                }

                if ($retornoDescricao['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoDescricao['codigoHelper'],
                        'campo' => 'Descrição',
                        'msg' => $retornoDescricao['msg']];
                }

                if ($retornoHoraInicial['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoHoraInicial['codigoHelper'],
                        'campo' => 'Hora Inicial',
                        'msg' => $retornoHoraInicial['msg']];
                }

                if ($retornoHoraFinal['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoHoraFinal['codigoHelper'],
                        'campo' => 'Hora Final',
                        'msg' => $retornoHoraFinal['msg']];
                }

                if ($retornoComparacaoHoras['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoComparacaoHoras['codigoHelper'],
                        'campo' => 'Hora Inicial e Hora Final',
                        'msg' => $retornoComparacaoHoras['msg']];
                }

                //Se não encontrar erros
                if (empty($erros)){
                   $this->setCodigo($resultado->codigo);
                    $this->setDescricao($resultado->descricao);
                    $this->setHoraInicial($resultado->horaInicial);
                    $this->setHoraFinal($resultado->horaFinal);

                    $this->load->model('M_horario');
                    $resBanco = $this->M_horario->consultar(
                        $this->getCodigo(),
                        $this->getDescricao(),
                        $this->getHoraInicial(),
                        $this->getHoraFinal()
                    );

                    if($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        //Captura erro no banco
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg' => $resBanco['msg']
                        ];
                    }           
                 }               
             }                   
        } catch (Exception $e) {
        $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado' . $e->getMessage()];
    }

    //Monta retorno único
    if ($sucesso == true) {
        $retorno = [
            'sucesso' => $sucesso,
            'codigo' => $resBanco['codigo'],
            'msg' => $resBanco['msg'],
            'dados' => $resBanco['dados']];
    } else {
        $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
    }

    echo json_encode($retorno);
    }

    public function alterar()
    {
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "codigo" => '0',
                "descricao" => '0',
                "horaInicial" => '0',
                "horaFinal" => '0',
            ];

            if (verificarParam($resultado, $lista) != 1) {
                //Validar vindos de forma correta do frontend (Helper)
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {

            if(trim($resultado->descricao) == '' && trim($resultado->horaInicial) == '' && 
            trim($resultado->horaFinal) == '') {
                $erros[] = ['codigo'=> 12, 
                            'msg'=> 'Pelo menos um parâmetro precisa ser passado para atualização'];
            }else{
                //Validar campos quanto ao tipo de dado e tamanho (Helper)
                $retornoCodigo = validarDados($resultado->codigo, 'int', true);
                $retornoDescricao = validarDadosConsulta($resultado->descricao, 'string');
                $retornoHoraInicial = validarDadosConsulta($resultado->horaInicial, 'hora');
                $retornoHoraFinal = validarDadosConsulta($resultado->horaFinal, 'hora');
                $retornoComparacaoHoras = compararDataHora($resultado->horaInicial,
                                                           $resultado->horaFinal, 'hora');

                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']];
                }

                if ($retornoDescricao['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoDescricao['codigoHelper'],
                        'campo' => 'Descrição',
                        'msg' => $retornoDescricao['msg']];
                }

                if ($retornoHoraInicial['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoHoraInicial['codigoHelper'],
                        'campo' => 'Hora Inicial',
                        'msg' => $retornoHoraInicial['msg']];
                }

                if ($retornoHoraFinal['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoHoraFinal['codigoHelper'],
                        'campo' => 'Hora Final',
                        'msg' => $retornoHoraFinal['msg']];
                }

                if ($retornoComparacaoHoras['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoComparacaoHoras['codigoHelper'],
                        'campo' => 'Hora Inicial e Hora Final',
                        'msg' => $retornoComparacaoHoras['msg']];
                }

                //Se não encontrar erros
                if (empty($erros)){
                   $this->setCodigo($resultado->codigo);
                    $this->setDescricao($resultado->descricao);
                    $this->setHoraInicial($resultado->horaInicial);
                    $this->setHoraFinal($resultado->horaFinal);

                    $this->load->model('M_horario');
                    $resBanco = $this->M_horario->alterar(
                        $this->getCodigo(),
                        $this->getDescricao(),
                        $this->getHoraInicial(),
                        $this->getHoraFinal()
                    );

                    if($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        //Captura erro no banco
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg' => $resBanco['msg']];
                        }           
                    }  
                }             
            }                   
        } catch (Exception $e) {
        $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado' . $e->getMessage()];
    }

    //Monta retorno único
    if ($sucesso == true) {
        $retorno = [
            'sucesso' => $sucesso,
            'codigo' => $resBanco['codigo'],
            'msg' => $resBanco['msg']];
    } else {
        $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
    }

    echo json_encode($retorno);
    }

        public function desativar()
    {
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "codigo" => '0',
            ];

            if (verificarParam($resultado, $lista) != 1) {
                //Validar vindos de forma correta do frontend (Helper)
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {
                //Validar campos quanto ao tipo de dado e tamanho (Helper)
                $retornoCodigo = validarDados($resultado->codigo, 'int', true);


                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']];
                }

                //Se não encontrar erros
                if (empty($erros)){
                    $this->setCodigo($resultado->codigo);

                    $this->load->model('M_horario');
                    $resBanco = $this->M_horario->desativar(
                        $this->getCodigo(),
                    );

                    if($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        //Captura erro no banco
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg' => $resBanco['msg']];
                    }           
                }         
            }                   
        } catch (Exception $e) {
        $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado' . $e->getMessage()];
    }

    //Monta retorno único
    if ($sucesso == true) {
        $retorno = [
            'sucesso' => $sucesso,
            'codigo' => $resBanco['codigo'],
            'msg' => $resBanco['msg']];
    } else {
        $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
    }

    echo json_encode($retorno);
    }
}

?>