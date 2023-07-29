<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="/css/style.css" rel="stylesheet">
    <title>Fluxo de caixa PHP</title>
</head>
<body>
    <div class="container">
        <h1>Fluxo de caixa em PHP</h1>
        <hr>
        <div class="row dvCaixa">
            <div class="col">
                <h4>Valor Total</h4>
                <div>R$ <?= number_format($valor_total, 2, ',', '.') ?></div>
            </div>
            <div class="col">
                <h4>Receitas</h4>
                <div>R$ <?= number_format($receitas, 2, ',', '.') ?></div>
            </div>
            <div class="col">
                <h4>Despesas</h4>
                <div>R$ <?= number_format($despesas, 2, ',', '.') ?></div>
            </div>
        </div>
        
        <hr>

        <form action="/">
            <div class="row dvBusca">
                <div class="col-md-10">
                    <div class="input-group mb-3">
                            <input type="text" class="form-control" value="<?= $termo_de_busca ?>" name="tipo" placeholder="Digite algo...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-secondary" type="button">Buscar</button>
                            </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="/adicionar" class="btn btn-primary">Adicionar</a>
                </div>
            </div>
        </form>

        <hr>
        <div class="row dvTabela">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Tipo</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($extrato as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item->tipo) ?></td>
                        <td>R$ <?= number_format($item->valor, 2, ',', '.') ?></td>
                        <td style="background: <?= $item->status == 1 ? '#81c0ff' : 'red' ?>;"" ><?= $item->status == 1 ? 'Receita' : 'Despesa' ?></td>
                        <td style="width: 20px">
                            <a href="/excluir/<?= $item->id ?>" onclick="return confirm('Confirma?')" class="btn btn-danger">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>
