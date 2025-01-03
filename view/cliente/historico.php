<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Atendimentos</title>
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <script src="path/to/jquery.min.js"></script>
    <script src="path/to/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Histórico de Atendimentos</h1>
        <div class="mt-4">
            <label for="selectUsuario">Selecione o Usuário:</label>
            <select id="selectUsuario" class="form-select">
                <option value="" disabled selected>Escolha um usuário...</option>
                <!-- Populado dinamicamente via AJAX -->
            </select>
        </div>
        <div class="mt-4" id="historicoDetalhes" style="display: none;">
            <h3 class="text-center mt-4">Histórico de <span id="usuarioNome"></span></h3>
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>Senha</th>
                        <th>Serviço</th>
                        <th>Data</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="listaHistorico">
                    <!-- Populado dinamicamente via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Carregar usuários
            $.ajax({
                url: '/usuario/listar',
                method: 'GET',
                success: function (response) {
                    response.forEach(usuario => {
                        $('#selectUsuario').append(
                            `<option value="${usuario.id}">${usuario.nome}</option>`
                        );
                    });
                }
            });

            // Atualizar histórico ao selecionar um usuário
            $('#selectUsuario').on('change', function () {
                const usuarioId = $(this).val();
                if (usuarioId) {
                    $.ajax({
                        url: `/senha/historico/${usuarioId}`,
                        method: 'GET',
                        success: function (response) {
                            $('#historicoDetalhes').show();
                            $('#usuarioNome').text(response.usuarioNome);
                            const tbody = $('#listaHistorico');
                            tbody.empty();
                            response.historico.forEach(item => {
                                tbody.append(`
                                    <tr>
                                        <td>${item.senha}</td>
                                        <td>${item.servico}</td>
                                        <td>${item.data}</td>
                                        <td>${item.status}</td>
                                    </tr>
                                `);
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
