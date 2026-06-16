<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Funcoes extends CI_Controller {

    public function index(){
        $this->load->view('login');
    }

    public function indexPagina(){
        $this->load->view('index');
    }

    public function encerraSistema(){
        // Redireciona o usuário para a página de login
        header('Location: ' . base_url());
    }

    public function abreSala(){
        $this->load->view('sala');
    }

    public function abreProfessor(){
        $this->load->view('professor');
    }

    public function abreTurma(){
        $this->load->view('turma');
    }

    public function abrePeriodo(){
        $this->load->view('periodo');
    }

    // Métodos abreMapa() e abreRelatorio() ainda serão implementados
    // (conteúdo a partir da página ~171 das notas de aula)

}
