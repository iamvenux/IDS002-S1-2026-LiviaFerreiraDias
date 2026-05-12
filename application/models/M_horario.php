<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class M_horario extends CI_Model{
public function inserir($descricao, $horaInicial, $horaFinal){
        try {
            // Verifico se a sala já está cadastrada
            $retornoConsulta = $this->consultarHorario('', $horaInicial, $horaFinal);

            if($retornoConsulta['codigo'] != 9 && 
               $retornoConsulta['codigo'] != 10){
              // Query de inserção de dados
              $this->db->query("insert into tbl_horario (descricao, hora_ini, hora_fim)
                               values ('$descricao','$horaInicial', '$horaFinal')");

             // Verifica se a inserção ocorreu com sucesso
             if ($this->db->affected_rows() > 0){
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Horário cadastrado corretamente'
                );
             }else {
                $dados = array (
                    'codigo' => 8,
                    'msg' => 'Houve algum problema na inserção na tabela de horários.'
                );
             }
            } else{
                $dados = array ( 'codigo' => $retornoConsulta['codigo'],
                                  'msg' => $retornoConsulta['msg']);
            }
        } catch (Exception $e) {
            $dados = array( 
                'codigo'=> 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }

        // Envia o array $dados com as informações tratadas
        // acima pela estrutuura de decisão if

        return $dados;
    }

    public function consultarHorario($codigo, $horaInicial, $horaFinal){
        try{
            if($codigo != ''){
                $sql = "select * from tbl_horario
                where codigo = $codigo ";
            }else{
                $sql = "select * from tbl_horario
                where hora_ini = '$horaInicial'
                and hora_fim = '$horaFinal'";
            }
            $retornoHorario = $this->db->query($sql);

            if($retornoHorario->num_rows() > 0){
                $linha = $retornoHorario->row();
                if (trim($linha->estatus) == "D"){
                    $dados = array(
                        'codigo' => 9,
                        'msg' => 'Horário desativado do sistema, caso precise reativar o mesmo, 
                        fale com o administrador.'
                    );
                    
                } else{
                    $dados = array(
                        'codigo' => 10,
                        'msg' => 'Horário já cadastrado no sistema.'
                    );
                } 
            }    
            else{
                    $dados = array(
                        'codigo' => 98,
                        'msg' => 'Horário não encontrado.'
                    );
                }

        }   catch (Exception $e) {
            $dados = array(
                    'codigo' => 0,
                    'msg' =>'ATENÇÂO: o seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }

    public function consultar($codigo, $descricao, $horaInicial, $horaFinal)
{
    try {
        //Query para consultar dados de acordo com parãmetros passados
        $sql = "select * from tbl_horario where estatus = '' ";

        if(trim($codigo) != "") {
            $sql = $sql . " and codigo = $codigo";
        }

        if(trim($descricao) != "") {
            $sql = $sql . " and descricao like '%$descricao%'";
        }

        if(trim($horaInicial) != "") {
            $sql = $sql . " and hora_ini = '$horaInicial'";
        }

        if(trim($horaFinal) != "") {
            $sql = $sql . " and hora_fim = '$horaFinal'";
        }

        $sql = $sql . " order by codigo";

        $retorno = $this->db->query($sql);

        //Verificar se a consulta ocorreu com sucesso
        if ($retorno->num_rows() > 0) {
            $dados = array(
                "codigo" => 1,
                "msg" => "Consulta efetuada com sucesso",
                "dados" => $retorno->result()
            );
        } else {
            $dados = array(
                "codigo" => 11,
                "msg" => "Horário não encontrado",
            );
        }
    } catch (Exception $e) {
        $dados = array(
            "codigo" => 00,
            "msg" => 'ATENÇÃO: O seguinte erro aconteceu ->' . $e->getMessage()
        );
    }

    return $dados;
}

public function alterar($codigo, $descricao, $horaInicial, $horaFinal)
{
    try {
        $retornoConsulta = $this->consultarHorario($codigo, '', '');

        if ($retornoConsulta['codigo'] == 10) {
            $query = "update tbl_horario set";

            if ($descricao !== '') {
                $query .= "descricao = '$descricao',";
            }
            if ($horaInicial !== '') {
                $query .= "hora_ini = '$horaInicial',";
            }
            if ($horaFinal !== '') {
                $query .= "hora_fim = '$horaFinal',";
            }

            $queryFinal = rtrim($query, ",") . " where codigo = $codigo";
            $this->db->query($queryFinal);

            if ($this->db->affected_rows() > 0) {
                $dados = array('codigo' => 1, 'msg' => 'Horário atualizado corretamente.');
            } else {
                $dados = array('codigo' => 8, 'msg' => 'Houve algum problema na atualização na tabela de horário.');
            }

        } else {
            $dados = array('codigo' => $retornoConsulta['codigo'], 'msg' => $retornoConsulta['msg']);
        }
    // ↑ aqui fecha o try
    } catch (Exception $e) {
        $dados = array('codigo' => 0, 'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage());
    }

    return $dados;
}

public function desativar($codigo) {
    try {
        // Query para consultar dados de acordo com parâmetros passados
        $retornoConsulta = $this->consultarHorario($codigo, '', '');

        if ($retornoConsulta['codigo'] == 10) {

            $this->db->query("UPDATE tbl_horario 
                              SET estatus = 'D'
                              WHERE codigo = $codigo");

            if ($this->db->affected_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Horário DESATIVADO corretamente.'
                );
            } else {
                $dados = array(
                    'codigo' => 8,
                    'msg' => 'Houve algum problema na DESATIVAÇÃO do Horário.'
                );
            }

        } else {
            $dados = array(
                'codigo' => $retornoConsulta['codigo'],
                'msg' => $retornoConsulta['msg']
            );
        }

    } catch (Exception $e) {
        $dados = array(
            'codigo' => 0,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
        );
    }

    return $dados;
}
}
    
?>