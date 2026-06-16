<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sala extends CI_Model
{
    // validação dos tipos de retorno nas valid.. (codigo erro)
    // 0 - erro de exceção
    //1 - Operação realizada no banco com sucesso
    //8 - houve algum problema de inserir ou etc
    // 9 - sala desativada
    //10 - sala ja cadastrada
    //98 metodo auxiliar de consulta n trouxe dados

    public function inserir($codigo, $descricao, $andar, $capacidade)
    {
        try {
            // vekrifico se a sala ja esta cadastrada
            $retornoConsulta = $this->consultaSala($codigo);

            if (
                $retornoConsulta['codigo'] != 9 &&
                $retornoConsulta['codigo'] != 10
            ) {
                // query de inserção de dados
                $this->db->query("insert into tbl_sala (codigo, descricao, andar, capacidade)
                               values ($codigo, '$descricao', $andar, $capacidade)");

                // verifica se a inserção ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Sala cadastrada corretamente'
                    );
                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na inserção na tabela de sala.'
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
                'msg' => 'ATENÇÃO: O seguinte erro aconteceus -> ',
                $e->getMessage()
            );
        }

        // envia o array $dados com as info tratadas
        // acima pela estrutuura de decisão if

        return $dados;
 
    }

    //Metodo privado, pois sera auxiliar nesta classe
    public function consultaSala($codigo)
    {
        try {
            //query p consultar dados de aconrdo com os parametros passados
            $sql = "select * from tbl_sala where codigo = $codigo ";

            $retornoSala = $this->db->query($sql);

            // verifica se a conslta ocorreu c sucesso
            if ($retornoSala->num_rows() > 0) {
                $linha = $retornoSala->row();
                if (trim($linha->estatus) == 'D') {
                    $dados = array(
                        'codigo' => 9,
                        'msg' => 'Sala desativada no sistema, caso precise reativar a mesma,
                                 fale com o administrados.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 10,
                        'msg' => 'Sala ja cadastrada no sistema.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 98,
                    'msg' => 'Sala não encontrada.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 0,
                'msg' => 'ATENÇÂO: o seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        //Envia o array $dados c as info tratadas
        // acima pela estrutura de decisão if
        return $dados;
    }
    public function consultar($codigo, $descricao, $andar, $capacidade)
    {
        try {
            //Quero consultar dados de acordo com parâmetros válidos
            $sql = "select * from tbl_sala where estatus = '' ";

            if (trim($codigo) != '') {
                $sql = $sql . " and codigo = $codigo ";
            }

            if (trim($andar) != '') {
                $sql = $sql . " and andar = '$andar' ";
            }

            if (trim($descricao) != '') {
                $sql = $sql . " and descricao like '%$descricao%' ";
            }

            if (trim($capacidade) != '') {
                $sql = $sql . " and capacidade = '$capacidade' ";
            }

            $sql = $sql . " order by codigo ";

            $retorno = $this->db->query($sql);

            //Verificar se a consulta ocorreu com sucesso
            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.',
                    'dados' => $retorno->result()
                );
            } else {
                $dados = array(
                    'codigo' => 11,
                    'msg' => 'Sala não encontrada.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }

        //Envio o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }
    public function alterar($codigo, $descricao, $andar, $capacidade){
        try {
            //Verifico se a sala já está cadastrada
            $retornoConsulta = $this->consultaSala($codigo);

            if ($retornoConsulta['codigo'] == 10) {
                //Inicio a query para atualização
                $query = "update tbl_sala set ";

                //Vamos comparar os itens
                if ($descricao !== '') {
                    $query .= "descricao = '$descricao', ";
                }

                if ($andar !== '') {
                    $query .= "andar = $andar, ";
                }

                if ($capacidade !== '') {
                    $query .= "capacidade = $capacidade, ";
                }

                //Termino a concatenação da query
                $queryFinal = rtrim($query, ", ") . " where codigo = $codigo";

                //Executo a Query de atualização dos dados
                $this->db->query($queryFinal);

                //Verificar se a atualização ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Sala atualizada corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na atualização na tabela de sala.'
                    );
                }
            } else {
                $dados = array('codigo' => $retornoConsulta['codigo'],
                                'msg' => $retornoConsulta['msg']);
            }

        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }
    public function desativar($codigo){
        try {
            //Verifico se a sala já está cadastrada
            $retornoConsulta = $this->consultaSala($codigo);

            if ($retornoConsulta['codigo'] == 10) {

                //Query de atualização dos dados
                $this->db->query("update tbl_sala set estatus = 'D'
                                    where codigo = $codigo");

                //Verificar se a atualização ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Sala DESATIVADA corretamente.'
                    );

                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na DESATIVAÇÃO da Sala.'
                    );
                }

            } else {
                $dados = array('codigo' => $retornoConsulta['codigo'],
                                'msg' => $retornoConsulta['msg']);
            }

        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }
}