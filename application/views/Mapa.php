<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../IDS002-S1-2026-LiviaFerreiraDias/assets/css/reset.css">
    <link rel="stylesheet" href="../../IDS002-S1-2026-LiviaFerreiraDias/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../IDS002-S1-2026-LiviaFerreiraDias/assets/css/styleCadastro.css">
    <title>Mapeamento</title>
    <link rel="icon" href=".../../IDS002-S1-2026-LiviaFerreiraDias/assets/img/icone_fatecSR.ico" type="image/x-icon">
    <style>
        .navbar-nav { width: 100%; display: flex; justify-content: space-around; }
        .nav-item { flex-grow: 1; text-align: center; }
        .nav-link { display: block; color: #ffffff !important; font-weight: bold; padding: 0px; }
        .nav-link:hover { background-color: #495057; }
    </style>
</head>

<body>
    <header>
        <a href="../Funcoes/indexPagina">
            <h1 id="headerTitle">Mapeamento de Salas</h1>
        </a>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../funcoes/abreSala">Sala de Aula</a></li>
                    <li class="nav-item"><a class="nav-link" href="../funcoes/abreProfessor">Docente</a></li>
                    <li class="nav-item"><a class="nav-link" href="../funcoes/abreTurma">Turma</a></li>
                    <li class="nav-item"><a class="nav-link" href="../funcoes/abrePeriodo">Período</a></li>
                    <li class="nav-item"><a class="nav-link" href="../funcoes/abreMapa">Reservas</a></li>
                    <li class="nav-item"><a class="nav-link" href="../funcoes/abreRelatorio">Relatório</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <!-- SEÇÃO CADASTRO -->
        <section class="secao4" id="cadastroMapeamento">
            <div id="btnCadastroModal" style="display: flex; gap: 10px; align-items: center;">
                <input type="text" id="inputPesquisa" class="form-control" placeholder="Pesquisar"
                    onkeyup="filtrarTabela()" style="max-width: 250px; height: 100%;">
                <button id="botaoModal" type="button" class="btn btn-outline-primary btnAcao"
                    data-toggle="modal" data-target="#cadastroMapeamentoModal" style="height: 100%;">
                    Cadastrar Nova Reserva
                </button>
            </div>

            <!-- MODAL CADASTRO -->
            <div class="modal fade" id="cadastroMapeamentoModal" tabindex="-1" role="dialog"
                aria-labelledby="cadastroMapeamentoModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadastroMapeamentoModalLabel">Cadastrar Nova Reserva</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="formCadastroMapeamento" method="post" class="modal-content">
                            <div class="modal-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="selectSalas" class="col-form-label">Sala</label>
                                        <select id="selectSalas" name="selectSalas" class="form-control" required>
                                            <option value="">Selecione uma sala</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="selectTurma" class="col-form-label">Turma</label>
                                        <select id="selectTurma" name="selectTurma" class="form-control" required>
                                            <option value="">Selecione uma Turma</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="selectProfessor" class="col-form-label">Docente</label>
                                        <select id="selectProfessor" name="selectProfessor" class="form-control" required>
                                            <option value="">Selecione um Docente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="dataFim" class="col-form-label">Data Final</label>
                                        <input type="date" id="dataFim" name="dataFim" class="form-control" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="selectHorario" class="col-form-label">Horário</label>
                                        <select id="selectHorario" name="selectHorario" class="form-control" required>
                                            <option value="">Selecione um Horário</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btnAcao" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btnAcao" onclick="cadastro();">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- MODAL EDITAR -->
        <section>
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Editar Mapeamento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="formEditMapeamento" method="post">
                            <div class="modal-body">
                                <input type="hidden" id="editId" name="editId">
                                <div class="form-group">
                                    <label for="editSelectSalas" class="col-form-label">Sala</label>
                                    <select id="editSelectSalas" name="editSelectSalas" class="form-control" required>
                                        <option value="">Selecione uma sala</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label for="editSelectTurma" class="col-form-label">Turma</label>
                                    <select id="editSelectTurma" name="editSelectTurma" class="form-control" required>
                                        <option value="">Selecione uma Turma</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="editSelectProfessor" class="col-form-label">Professor</label>
                                        <select id="editSelectProfessor" name="editSelectProfessor" class="form-control" required>
                                            <option value="">Selecione um Docente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-5">
                                        <label for="dataEditar" class="col-form-label">Data</label>
                                        <input type="date" id="dataEditar" name="dataEditar" class="form-control" required>
                                    </div>
                                    <div class="col-md-7">
                                        <label for="editSelectHorario" class="col-form-label">Horário</label>
                                        <select id="editSelectHorario" name="editSelectHorario" class="form-control" required>
                                            <option value="">Selecione um Horário</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btnAcao" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btnAcao" onclick="editarMapeamento(event);">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- TABELA -->
        <section id="mostrarCadastro">
            <div class="table-responsive tabela-scroll">
                <div id="spinner" style="display: none; text-align: center; margin-top: 10px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Carregando...</span>
                    </div>
                </div>
                <table class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Sala</th>
                            <th>Descrição da Sala</th>
                            <th>Turma</th>
                            <th>Docente</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="conteudo-Mapeamento">
                        <!-- As linhas serão geradas dinamicamente via JavaScript -->
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer></footer>

    <script src="../../IDS002-S1-2026-LiviaFerreiraDias/assets/js/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="../../IDS002-S1-2026-LiviaFerreiraDias/assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../IDS002-S1-2026-LiviaFerreiraDias/assets/js/sweetalert2.all.min.js" type="text/javascript"></script>

    <script>
        async function cadastro() {
            event.preventDefault();
            try {
                const sala       = document.getElementById('selectSalas').value;
                const turma      = document.getElementById('selectTurma').value;
                const professor  = document.getElementById('selectProfessor').value;
                const horario    = document.getElementById('selectHorario').value;
                const dataReserva = document.getElementById('dataFim').value;

                const response = await fetch('../Mapa/inserir', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        codSala: sala,
                        codHorario: horario,
                        codTurma: turma,
                        codProfessor: professor,
                        dataReserva: dataReserva
                    })
                });

                const result = await response.json();

                if (result.codigo == 1) {
                    $('#cadastroMapeamentoModal').modal('hide');
                    Swal.fire('Sucesso!', result.msg, 'success');
                    carregarDados();
                } else {
                    const mensagensDeErro = result.erros.map(erro => {
                        return `<p><strong>[${erro.campo ?? erro.codigo}]</strong> ${erro.msg}</p>`;
                    }).join('');
                    Swal.fire({
                        title: 'Houve(ram) erro(s) de validação:',
                        html: mensagensDeErro,
                        icon: 'error',
                        confirmButtonText: 'Fechar'
                    });
                }
            } catch (error) {
                console.error('Erro ao cadastrar o Mapeamento:', error);
                Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição.', 'error');
            }
        }

        const spinner = document.getElementById('spinner');

        async function carregarDados() {
            try {
                spinner.style.display = 'block';

                const response = await fetch('../Mapa/consultar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        codigo: '',
                        dataReserva: '',
                        codSala: '',
                        codHorario: '',
                        codTurma: '',
                        codProfessor: ''
                    })
                });

                const data = await response.json();
                const conteudoAcesso = document.getElementById('conteudo-Mapeamento');
                conteudoAcesso.innerHTML = '';

                if (data && Array.isArray(data.dados) && data.dados.length > 0) {
                    const fragmento = document.createDocumentFragment();

                    data.dados.forEach(item => {
                        const linha = document.createElement('tr');
                        linha.classList.add('alert', 'alert-warning');
                        linha.innerHTML = `
                            <td style="display:none"><input type="checkbox" class="selecionar-item" value="${item.codigo}"></td>
                            <td>${item.sala}</td>
                            <td>${item.descsala}</td>
                            <td>${item.descturma}</td>
                            <td style="display:none">${item.codigo_turma}</td>
                            <td>${item.nome_professor}</td>
                            <td style="display:none">${item.codigo_professor}</td>
                            <td>${item.datareservabra}</td>
                            <td style="display:none">${item.datareserva}</td>
                            <td>${item.deshorario}</td>
                            <td style="display:none">${item.codigo_horario}</td>
                            <td>
                                <div class="row">
                                    <div id="btnEditModal" style="display: flex; gap: 10px; align-items: center;">
                                        <button class="btn btn-warning btnAcao" onclick="openEditModal(this, ${item.codigo})">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                    </div>
                                    <button class="btn btn-danger btnAcao btnAcaoExcluir" onclick="deletarMapeamento(${item.codigo})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>`;
                        fragmento.appendChild(linha);
                    });

                    conteudoAcesso.appendChild(fragmento);
                } else {
                    conteudoAcesso.innerHTML = '<tr><td colspan="10">Nenhum dado encontrado.</td></tr>';
                }
            } catch (error) {
                console.error('Erro ao carregar os dados:', error);
            } finally {
                spinner.style.display = 'none';
            }
        }

        function debounce(func, delay) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => func(...args), delay);
            };
        }

        const carregarDadosDebounced = debounce(carregarDados, 300);

        $(document).ready(function() {
            carregarDados();

            $('#cadastroMapeamentoModal').on('show.bs.modal', function() {
                $('#formCadastroMapeamento')[0].reset();
            });
        });

        function openEditModal(button, codigo) {
            const row = button.closest('tr');

            // Índices das células (considera as hidden)
            const sala            = row.cells[1].innerText;
            const turma           = row.cells[4].innerText;  // codigo_turma (hidden)
            const professor       = row.cells[6].innerText;  // codigo_professor (hidden)
            const dataMapeamento  = row.cells[8].innerText;  // datareserva ISO (hidden)
            const horario         = row.cells[10].innerText; // codigo_horario (hidden)

            document.getElementById('editId').value = codigo;
            document.getElementById('editSelectSalas').value     = sala;
            document.getElementById('editSelectTurma').value     = turma;
            document.getElementById('editSelectProfessor').value = professor;
            document.getElementById('dataEditar').value          = dataMapeamento;
            document.getElementById('editSelectHorario').value   = horario;

            $('#editModal').modal('show');
        }

        async function editarMapeamento() {
            event.preventDefault();
            try {
                const codigo          = document.getElementById('editId').value;
                const sala            = document.getElementById('editSelectSalas').value;
                const turma           = document.getElementById('editSelectTurma').value;
                const professor       = document.getElementById('editSelectProfessor').value;
                const dataMapeamento  = document.getElementById('dataEditar').value;
                const horario         = document.getElementById('editSelectHorario').value;

                const response = await fetch('../Mapa/alterar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        codigo: codigo,
                        dataReserva: dataMapeamento,
                        codSala: sala,
                        codHorario: horario,
                        codTurma: turma,
                        codProfessor: professor
                    })
                });

                const result = await response.json();

                if (result.codigo == 1) {
                    $('#editModal').modal('hide');
                    Swal.fire('Sucesso!', result.msg, 'success');
                    carregarDados();
                } else {
                    const mensagensDeErro = result.erros.map(erro => {
                        return `<p><strong>[${erro.campo ?? erro.codigo}]</strong> ${erro.msg}</p>`;
                    }).join('');
                    Swal.fire({
                        title: 'Houve(ram) erro(s) de validação:',
                        html: mensagensDeErro,
                        icon: 'error',
                        confirmButtonText: 'Fechar'
                    });
                }
                $('#cadastroMapeamentoModal').modal('hide');
                carregarDados();

            } catch (error) {
                console.error('Erro ao editar Mapeamento:', error);
                Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição.', 'error');
            }
        }

        async function deletarMapeamentoMultiplos(codigos) {
            Swal.fire({
                title: 'Atenção!',
                text: 'Tem certeza que deseja remover esses Mapeamentos?',
                icon: 'question',
                showConfirmButton: true,
                showCancelButton: true,
                customClass: {
                    popup: 'my-swal-popup',
                    title: 'my-swal-title',
                    html: 'my-swal-text',
                    confirmButton: 'btn btn-danger btnAcao my-swal-button',
                    cancelButton: 'btn btn-secondary btnAcao my-swal-button',
                },
                buttonsStyling: false
            }).then(async function(res) {
                if (res.isConfirmed) {
                    const config = {
                        method: 'post',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            codigos: codigos // Enviar a chave 'codigos' como esperado pelo backend
                        })
                    };

                    const request  = await fetch('../Mapa/desativarMultiplos', config);
                    const response = await request.json();

                    Swal.fire({
                        title: 'Atenção!',
                        text: response.msg,
                        icon: response.codigo == 1 ? 'success' : 'error',
                        customClass: {
                            popup: 'my-swal-popup',
                            title: 'my-swal-title',
                            html: 'my-swal-text',
                            confirmButton: 'btn btn-primary btnAcao',
                        },
                        buttonsStyling: false
                    });
                    carregarDados();
                }
            });
        }

        async function deletarMapeamento(codigo) {
            Swal.fire({
                title: 'Atenção!',
                text: 'Tem certeza que deseja remover essa Mapeamento?',
                icon: 'question',
                showConfirmButton: true,
                showCancelButton: true,
                customClass: {
                    popup: 'my-swal-popup',
                    title: 'my-swal-title',
                    html: 'my-swal-text',
                    confirmButton: 'btn btn-danger btnAcao my-swal-button',
                    cancelButton: 'btn btn-secondary btnAcao my-swal-button',
                },
                buttonsStyling: false
            }).then(async function(res) {
                if (res.isConfirmed) {
                    const config = {
                        method: 'post',
                        body: JSON.stringify({ codigo: codigo })
                    };
                    const request  = await fetch('../Mapa/desativar', config);
                    const response = await request.json();

                    Swal.fire({
                        title: 'Atenção!',
                        text: response.msg,
                        icon: response.codigo == 1 ? 'success' : 'error',
                        customClass: {
                            popup: 'my-swal-popup',
                            title: 'my-swal-title',
                            html: 'my-swal-text',
                            confirmButton: 'btn btn-primary btnAcao',
                        },
                        buttonsStyling: false
                    });
                    carregarDados();
                }
            });
        }

        function filtrarTabela() {
            const input  = document.getElementById("inputPesquisa");
            const filter = input.value.trim().toLowerCase();
            const tabela = document.getElementById("conteudo-Mapeamento");
            const linhas = tabela.getElementsByTagName("tr");

            for (let linha of linhas) {
                const celulas = linha.getElementsByTagName("td");
                if (celulas.length > 0) {
                    const conteudoLinha = Array.from(celulas)
                        .map(celula => celula.textContent.trim().toLowerCase())
                        .join(" ");
                    linha.style.display = conteudoLinha.includes(filter) ? "" : "none";
                }
            }
        }

        /* ===================== AJAX para popular selects ===================== */
        $(document).ready(function() {

            // Helper para popular um select via AJAX
            function popularSelect(selectId, url, textField) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({}),
                    success: function(retorno) {
                        if (retorno.codigo == 1) {
                            $.each(retorno.dados, function(index, item) {
                                $('#' + selectId).append($('<option>', {
                                    value: item.codigo,
                                    text: item[textField]
                                }));
                            });
                        } else {
                            $('#' + selectId).append('<option value="">Nenhum item cadastrado</option>');
                        }
                    },
                    error: function() { alert('Erro ao carregar informações.'); }
                });
            }

            // Salas - cadastro e edição
            $.ajax({
                url: '../Sala/consultar', method: 'POST', dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ codigo: '', descricao: '', andar: '', capacidade: '' }),
                success: function(retorno) {
                    if (retorno.codigo == 1) {
                        $.each(retorno.dados, function(i, item) {
                            var opt = $('<option>', { value: item.codigo, text: item.codigo + " - " + item.descricao });
                            $('#selectSalas').append(opt.clone());
                            $('#editSelectSalas').append(opt);
                        });
                    } else {
                        $('#selectSalas, #editSelectSalas').append('<option value="">Nenhuma sala cadastrada</option>');
                    }
                },
                error: function() { alert('Erro ao carregar as salas.'); }
            });

            // Professores - cadastro e edição
            $.ajax({
                url: '../Professor/consultar', method: 'POST', dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ codigo: '', nome: '', cpf: '', tipo: '' }),
                success: function(retorno) {
                    if (retorno.codigo == 1) {
                        $.each(retorno.dados, function(i, item) {
                            var opt = $('<option>', { value: item.codigo, text: item.nome });
                            $('#selectProfessor').append(opt.clone());
                            $('#editSelectProfessor').append(opt);
                        });
                    } else {
                        $('#selectProfessor, #editSelectProfessor').append('<option value="">Nenhum professor cadastrado</option>');
                    }
                },
                error: function() { alert('Erro ao carregar informações.'); }
            });

            // Turmas - cadastro e edição
            $.ajax({
                url: '../Turma/consultar', method: 'POST', dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ codigo: '', descricao: '', capacidade: '', dataInicio: '' }),
                success: function(retorno) {
                    if (retorno.codigo == 1) {
                        $.each(retorno.dados, function(i, item) {
                            var opt = $('<option>', { value: item.codigo, text: item.descricao });
                            $('#selectTurma').append(opt.clone());
                            $('#editSelectTurma').append(opt);
                        });
                    } else {
                        $('#selectTurma, #editSelectTurma').append('<option value="">Nenhuma turma cadastrada</option>');
                    }
                },
                error: function() { alert('Erro ao carregar informações.'); }
            });

            // Horários - cadastro e edição
            $.ajax({
                url: '../Horario/consultar', method: 'POST', dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ codigo: '', descricao: '', horaInicial: '', horaFinal: '' }),
                success: function(retorno) {
                    if (retorno.codigo == 1) {
                        $.each(retorno.dados, function(i, item) {
                            var opt = $('<option>', { value: item.codigo, text: item.descricao });
                            $('#selectHorario').append(opt.clone());
                            $('#editSelectHorario').append(opt);
                        });
                    } else {
                        $('#selectHorario, #editSelectHorario').append('<option value="">Nenhum horário cadastrado</option>');
                    }
                },
                error: function() { alert('Erro ao carregar informações.'); }
            });

        });
    </script>
</body>
</html>
