<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * NOTA SOBRE O CAMPO "estatus":
 * O código assume que o campo de status usa 'A' para Ativo e 'D' para
 * Desativado (igual ao que já era usado em desativar()). Ajuste os
 * valores 'A' e 'D' abaixo se a sua tabela usar outra convenção
 * (ex.: 1/0, S/N, etc).
 */
class M_horario extends CI_Model
{
    const ESTATUS_ATIVO      = 'A';
    const ESTATUS_DESATIVADO = 'D';

    public function inserir($descricao, $horaInicial, $horaFinal)
    {
        try {
            // Verifico se já existe um horário ATIVO com o mesmo intervalo
            // (não passamos código porque é um registro novo)
            $retornoConsulta = $this->consultarHorario('', $horaInicial, $horaFinal);

            // codigo 9  = existe e está desativado
            // codigo 10 = existe e está ativo
            // codigo 98 = não encontrado -> pode inserir
            if ($retornoConsulta['codigo'] != 9 && $retornoConsulta['codigo'] != 10) {
                // Query de inserção de dados (com binding para evitar SQL Injection)
                $this->db->query(
                    "INSERT INTO tbl_horario (descricao, hora_ini, hora_fim, estatus)
                     VALUES (?, ?, ?, ?)",
                    array($descricao, $horaInicial, $horaFinal, self::ESTATUS_ATIVO)
                );

                // Verifica se a inserção ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Horário cadastrado corretamente'
                    );
                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na inserção na tabela de horários.'
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

    /**
     * Verifica a existência de um horário, seja por código (consulta
     * direta de um registro específico) ou por intervalo de hora
     * (verificação de duplicidade antes de inserir/alterar).
     *
     * CORREÇÃO PRINCIPAL: quando filtramos por hora_ini/hora_fim para
     * checar duplicidade, agora também filtramos por estatus = ATIVO.
     * Antes a query buscava QUALQUER linha (ativa ou desativada) com
     * aquele intervalo, então um registro antigo desativado bloqueava
     * a criação de um horário novo com o mesmo intervalo, mostrando
     * incorretamente "Horário desativado do sistema".
     *
     * Também foi adicionado suporte a $codigoIgnorar, usado em alterar()
     * para não comparar o próprio registro contra ele mesmo.
     */
    public function consultarHorario($codigo, $horaInicial, $horaFinal, $codigoIgnorar = '')
    {
        try {
            if ($codigo != '') {
                $sql = "SELECT * FROM tbl_horario WHERE codigo = ?";
                $params = array($codigo);

                $retornoHorario = $this->db->query($sql, $params);
            } else {
                $sql = "SELECT * FROM tbl_horario
                        WHERE hora_ini = ?
                        AND hora_fim = ?
                        AND estatus = ?";
                $params = array($horaInicial, $horaFinal, self::ESTATUS_ATIVO);

                if ($codigoIgnorar != '') {
                    $sql .= " AND codigo != ?";
                    $params[] = $codigoIgnorar;
                }

                $retornoHorario = $this->db->query($sql, $params);
            }

            if ($retornoHorario->num_rows() > 0) {
                $linha = $retornoHorario->row();
                if (trim($linha->estatus) == self::ESTATUS_DESATIVADO) {
                    $dados = array(
                        'codigo' => 9,
                        'msg' => 'Horário desativado do sistema, caso precise reativar o mesmo, fale com o administrador.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 10,
                        'msg' => 'Horário já cadastrado no sistema.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 98,
                    'msg' => 'Horário não encontrado.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: o seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }

        return $dados;
    }

    /**
     * CORREÇÃO: a query original filtrava "where estatus = ''", o que
     * nunca retornava nenhuma linha (a menos que existissem registros
     * literalmente com estatus vazio). Trocado para filtrar pelo status
     * ATIVO, que é o comportamento esperado de uma listagem padrão.
     */
    public function consultar($codigo, $descricao, $horaInicial, $horaFinal)
    {
        try {
            $sql = "SELECT * FROM tbl_horario WHERE estatus = ?";
            $params = array(self::ESTATUS_ATIVO);

            if (trim($codigo) != "") {
                $sql .= " AND codigo = ?";
                $params[] = $codigo;
            }

            if (trim($descricao) != "") {
                $sql .= " AND descricao LIKE ?";
                $params[] = '%' . $descricao . '%';
            }

            if (trim($horaInicial) != "") {
                $sql .= " AND hora_ini = ?";
                $params[] = $horaInicial;
            }

            if (trim($horaFinal) != "") {
                $sql .= " AND hora_fim = ?";
                $params[] = $horaFinal;
            }

            $sql .= " ORDER BY codigo";

            $retorno = $this->db->query($sql, $params);

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
                    "dados" => array()
                );
            }
        } catch (Exception $e) {
            $dados = array(
                "codigo" => 0,
                "msg" => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }

        return $dados;
    }

    /**
     * CORREÇÕES:
     * 1. A query SQL original tinha "set" colado no nome do campo e
     *    faltavam espaços antes da vírgula seguinte, gerando SQL
     *    inválido (ex.: "setdescricao = 'x',hora_ini = 'y',").
     * 2. Trocado para Query Bindings para evitar SQL Injection.
     */
    public function alterar($codigo, $descricao, $horaInicial, $horaFinal)
    {
        try {
            $retornoConsulta = $this->consultarHorario($codigo, '', '');

            if ($retornoConsulta['codigo'] == 10) {
                $campos = array();
                $params = array();

                if ($descricao !== '') {
                    $campos[] = "descricao = ?";
                    $params[] = $descricao;
                }
                if ($horaInicial !== '') {
                    $campos[] = "hora_ini = ?";
                    $params[] = $horaInicial;
                }
                if ($horaFinal !== '') {
                    $campos[] = "hora_fim = ?";
                    $params[] = $horaFinal;
                }

                if (empty($campos)) {
                    return array(
                        'codigo' => 12,
                        'msg' => 'Pelo menos um parâmetro precisa ser passado para atualização.'
                    );
                }

                $sql = "UPDATE tbl_horario SET " . implode(', ', $campos) . " WHERE codigo = ?";
                $params[] = $codigo;

                $this->db->query($sql, $params);

                if ($this->db->affected_rows() > 0) {
                    $dados = array('codigo' => 1, 'msg' => 'Horário atualizado corretamente.');
                } else {
                    $dados = array('codigo' => 8, 'msg' => 'Houve algum problema na atualização na tabela de horário.');
                }
            } else {
                $dados = array('codigo' => $retornoConsulta['codigo'], 'msg' => $retornoConsulta['msg']);
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 0, 'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage());
        }

        return $dados;
    }

    public function desativar($codigo)
    {
        try {
            $retornoConsulta = $this->consultarHorario($codigo, '', '');

            if ($retornoConsulta['codigo'] == 10) {
                $this->db->query(
                    "UPDATE tbl_horario SET estatus = ? WHERE codigo = ?",
                    array(self::ESTATUS_DESATIVADO, $codigo)
                );

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
            } elseif ($retornoConsulta['codigo'] == 9) {
                // Já está desativado - retorno mais claro do que repetir o aviso de "fale com o administrador"
                $dados = array(
                    'codigo' => 9,
                    'msg' => 'Este horário já está desativado.'
                );
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