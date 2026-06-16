<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// =============================================================================
// FUNÇÃO: verificarParam
// Função para verificar os parâmetros vindos do FrontEnd.
// Verifica se os campos passados pelo Front estão nos atributos necessários.
// =============================================================================
function verificarParam($atributos, $lista)
{
    // 1º - Verificar se os elementos do Front estão nos atributos necessários
    foreach ($lista as $key => $value) {
        if (array_key_exists($key, get_object_vars($atributos))) {
            $estatus = 1;
        } else {
            $estatus = 0;
            break;
        }
    }

    // 2º - Verificando a quantidade de elementos
    if (count(get_object_vars($atributos)) != count($lista)) {
        $estatus = 0;
    }

    return $estatus;
}

// =============================================================================
// FUNÇÃO: validarDados
// Função para verificar os tipos de dados.
// Usada na INSERÇÃO e ALTERAÇÃO (campos obrigatórios).
//
// Códigos de retorno (codigoHelper):
//  0  - Validação correta
//  2  - Conteúdo nulo ou vazio
//  3  - Conteúdo zerado
//  4  - Conteúdo não inteiro
//  5  - Conteúdo não é um texto
//  6  - Data em formato inválido
//  7  - Hora em formato inválido
//  0  - Tipo de dado não definido (default)
// =============================================================================
function validarDados($valor, $tipo, $tamanhoZero = true)
{
    // Verifica vazio ou nulo
    if (is_null($valor) || $valor === '') {
        return array('codigoHelper' => 2, 'msg' => 'Conteúdo nulo ou vazio.');
    }

    // Se considerar '0' como vazio
    if ($tamanhoZero && ($valor === 0 || $valor === '0')) {
        return array('codigoHelper' => 3, 'msg' => 'Conteúdo zerado.');
    }

    switch ($tipo) {
        case 'int':
            // Filtra como inteiro, aceita '123' ou 123
            if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                return array('codigoHelper' => 4, 'msg' => 'Conteúdo não inteiro.');
            }
            break;

        case 'string':
            // Garante que é string não vazia após trim
            if (!is_string($valor) || trim($valor) === '') {
                return array('codigoHelper' => 5, 'msg' => 'Conteúdo não é um texto.');
            }
            break;

        case 'date':
            // Verifica se tem padrão de data (Y-m-d)
            if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)) {
                return array('codigoHelper' => 6, 'msg' => 'Data em formato inválido.');
            } else {
                // Tenta criar DateTime no formato Y-m-d
                $d = DateTime::createFromFormat('Y-m-d', $valor);
                if (($d->format('Y-m-d') === $valor) == false) {
                    return array('codigoHelper' => 6, 'msg' => 'Data inválida.');
                }
            }
            break;

        case 'hora':
            // Verifica se tem padrão de hora (HH:MM)
            if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $valor)) {
                return array('codigoHelper' => 7, 'msg' => 'Hora em formato inválido.');
            }
            break;

        case 'email':
            // Verifica se tem padrão de hora
            if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                return array('codigoHelper' => 8, 'msg' => 'E-mail em formato inválido.');
            }
            break;

        default:
            return array('codigoHelper' => 0, 'msg' => 'Tipo de dado não definido.');
    }

    // Valor default da variável $retorno caso não ocorra erro
    return array('codigoHelper' => 0, 'msg' => 'Validação correta.');
}

// =============================================================================
// FUNÇÃO: validarDadosConsulta
// Função para verificar os tipos de dados para Consulta.
// Diferente de validarDados(), aqui o campo pode ser vazio/vazio —
// só valida o tipo SE o valor não estiver vazio.
//
// Códigos de retorno (codigoHelper):
//  0  - Validação correta (inclusive campo em branco)
//  4  - Conteúdo não inteiro
//  5  - Conteúdo não é um texto
//  6  - Data em formato inválido
//  7  - Hora em formato inválido
//  97 - Tipo de dado não definido
// =============================================================================
function validarDadosConsulta($valor, $tipo)
{
    if ($valor != '') {
        switch ($tipo) {
            case 'int':
                // Filtra como inteiro, aceita '123' ou 123
                if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                    return array('codigoHelper' => 4, 'msg' => 'Conteúdo não inteiro.');
                }
                break;

            case 'string':
                // Garante que é string não vazia após trim
                if (!is_string($valor) || trim($valor) === '') {
                    return array('codigoHelper' => 5, 'msg' => 'Conteúdo não é um texto.');
                }
                break;

            case 'date':
                // Verifica se tem padrão de data (Y-m-d)
                if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)) {
                    return array('codigoHelper' => 6, 'msg' => 'Data em formato inválido.');
                } else {
                    // Tenta criar DateTime no formato Y-m-d
                    $d = DateTime::createFromFormat('Y-m-d', $valor);
                    if (($d->format('Y-m-d') === $valor) == false) {
                        return array('codigoHelper' => 6, 'msg' => 'Data inválida.');
                    }
                }
                break;

            case 'hora':
                // Verifica se tem padrão de hora (HH:MM)
                if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $valor)) {
                    return array('codigoHelper' => 7, 'msg' => 'Hora em formato inválido.');
                }
                break;

            case 'email':
                // Verifica se tem padrão de e-mail
                if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    return array('codigoHelper' => 8, 'msg' => 'E-mail em formato inválido.');
                }
                break;

            default:
                return array('codigoHelper' => 97, 'msg' => 'Tipo de dado não definido.');
        }
    }

    // Valor default da variável $retorno caso não ocorra erro
    return array('codigoHelper' => 0, 'msg' => 'Validação correta.');
}

// =============================================================================
// FUNÇÃO: compararDataHora
// Função para verificar se datas ou horários iniciais são maiores entre eles.
//
// Parâmetros:
//  $valorInicial - string com data ou hora inicial
//  $valorFinal   - string com data ou hora final
//  $tipo         - 'hora' ou 'date'
//
// Códigos de retorno (codigoHelper):
//  0  - Validação correta
//  13 - Hora Final menor que a Hora Inicial
//  14 - Data Final menor que a Data Inicial
//  97 - Tipo de verificação não definida
//
// CORREÇÃO: a versão anterior comparava strtotime($valorInicial) !== ''
// e strtotime($valorFinal) !== '', mas strtotime() NUNCA retorna '' —
// retorna false em caso de erro/vazio. Essa comparação era sempre
// verdadeira, fazendo a função tentar comparar valores mesmo quando os
// campos vinham vazios (ex.: na consulta, que manda horaInicial/horaFinal
// vazios). Agora verificamos a string original ANTES de converter, e só
// comparamos se ambos os valores originais não estiverem vazios e o
// strtotime() tiver convertido com sucesso (diferente de false).
// =============================================================================
function compararDataHora($valorInicial, $valorFinal, $tipo)
{
    // Se algum dos dois vier vazio/nulo, não há o que comparar
    if ($valorInicial === '' || $valorInicial === null ||
        $valorFinal   === '' || $valorFinal   === null) {
        return array('codigoHelper' => 0, 'msg' => 'Validação correta.');
    }

    // Passamos a string para timestamp
    $tsInicial = strtotime($valorInicial);
    $tsFinal   = strtotime($valorFinal);

    // Se a conversão falhar para algum dos dois, não tentamos comparar
    // (o erro de formato já é tratado por validarDados/validarDadosConsulta)
    if ($tsInicial === false || $tsFinal === false) {
        return array('codigoHelper' => 0, 'msg' => 'Validação correta.');
    }

    if ($tsInicial > $tsFinal) {
        switch ($tipo) {
            case 'hora':
                return array('codigoHelper' => 13, 'msg' => 'Hora Final menor que a Hora Inicial.');

            case 'date':
                return array('codigoHelper' => 14, 'msg' => 'Data Final menor que a Data Inicial.');

            default:
                return array('codigoHelper' => 97, 'msg' => 'Tipo de verificação não definida.');
        }
    }

    // Valor default da variável $retorno caso não ocorra erro
    return array('codigoHelper' => 0, 'msg' => 'Validação correta.');
}

// =============================================================================
// FUNÇÃO: validarCPF
// Função para verificar se o CPF é válido quanto a sua estrutura.
//
// Códigos de retorno (codigoHelper):
//  0  - CPF válido
//  15 - CPF com menos de 11 dígitos
//  16 - CPF com todos os dígitos iguais
//  17 - CPF com dígitos verificadores incorretos
// =============================================================================
function validarCPF($cpf)
{
    // Remove tudo que não for número
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // CPF deve ter 11 dígitos
    if (strlen($cpf) != 11) {
        return array('codigoHelper' => 15, 'msg' => 'CPF com menos de 11 dígitos.');
    }

    // Rejeita CPFs com todos os dígitos iguais
    if (preg_match('/^(\d)\1{10}$/', $cpf)) {
        return array('codigoHelper' => 16, 'msg' => 'CPF com todos dígitos iguais.');
    }

    // Calcula os dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }
        $digito  = (10 * $soma) % 11;
        $digito  = ($digito == 10) ? 0 : $digito;

        if ($cpf[$t] != $digito) {
            return array('codigoHelper' => 17, 'msg' => 'CPF com dígitos verificadores incorretos.');
        }
    }

    return array('codigoHelper' => 0, 'msg' => 'CPF válido.');
}
?>