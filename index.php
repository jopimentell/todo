<?php

$pdo = new PDO("mysql:host=localhost;dbname=tarefas", "root", "");

// NOVA TAREFA
if ( isset($_POST['nova_tarefa']) ) {
    $titulo = $_POST['titulo'] ;

    $sql = $pdo->prepare("INSERT INTO tarefas (titulo) VALUES (?)");
    $sql->execute([$titulo]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
// APAGA TAREFA
if ( isset($_GET['delete']) ) {
    $id = $_GET['delete'] ;

    $sql = $pdo->prepare("DELETE FROM tarefas WHERE id=?");
    $sql->execute([$id]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// EDITAR TAREFA
if ( isset($_POST['editar']) ) {
    $id = $_POST['id'] ;
    $titulo = $_POST['titulo'] ;
    $concluido = isset($_POST['concluido']) ? 1 : 0;

    $sql = $pdo->prepare("UPDATE tarefas SET titulo=?, concluido=? WHERE id=?");
    $sql->execute([$titulo, $concluido, $id]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
// MARCAR TAREFA
if ( isset($_GET['toggle']) ) {
    $id = $_GET['toggle'] ;

    $sql = $pdo->prepare("UPDATE tarefas SET concluido = NOT concluido  WHERE id=?");
    $sql->execute([$id]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

$tarefas = $pdo->query("SELECT * FROM tarefas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$pendentes = [];
$concluidas = [];

foreach ($tarefas as $tarefa) {
    if ($tarefa['concluido']) {
        $concluidas[] = $tarefa;
    } else {
        $pendentes[] = $tarefa;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas Simples</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">


    <header class="bg-primary text-white text-center py-3 shadow-sm">
        <h1>Minha Lista</h1>
    </header>


    <main class="container mt-4 max-w-custom">
        <div class="card shadow-sm">
            <div class="card-body">
               
                <ul class="list-group list-group-flush mb-3">

                <?php foreach($pendentes as $tarefa): ?>
                   
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">

                        <div class="d-flex align-items-center">

                            <input class="form-check-input me-3" type="checkbox"
                            id="check<?= $tarefa['id'] ?>"
                            onclick="window.location='?toggle=<?= $tarefa['id'] ?>'"
                            <?= $tarefa['concluido'] ? 'checked' : '' ?>>

                            <label class="form-check-label" for="check<?= $tarefa['id'] ?>"><?= $tarefa['titulo'] ?></label>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-1" 
                            data-bs-toggle="modal" data-bs-target="#modalEditar" 
                            data-id="<?= $tarefa['id'] ?>"
                            data-titulo="<?= $tarefa['titulo'] ?>"
                            data-concluido="<?= $tarefa['concluido'] ?>">Editar</button>

                            <a href="?delete=<?= $tarefa['id'] ?>" class="btn btn-sm btn-outline-danger">
                            Apagar
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php foreach($concluidas as $tarefa): ?>
                   
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">

                        <div class="d-flex align-items-center">

                            <input class="form-check-input me-3" type="checkbox"
                            id="check<?= $tarefa['id'] ?>"
                            onclick="window.location='?toggle=<?= $tarefa['id'] ?>'"
                            <?= $tarefa['concluido'] ? 'checked' : '' ?>>

                            <label class="form-check-label" for="check<?= $tarefa['id'] ?>"><?= $tarefa['titulo'] ?></label>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-1" 
                            data-bs-toggle="modal" data-bs-target="#modalEditar" 
                            data-id="<?= $tarefa['id'] ?>"
                            data-titulo="<?= $tarefa['titulo'] ?>"
                            data-concluido="<?= $tarefa['concluido'] ?>">Editar</button>

                            <a href="?delete=<?= $tarefa['id'] ?>" class="btn btn-sm btn-outline-danger">
                            Apagar
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>


                </ul>


                <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#modalAdicionar">
                    + Adicionar Item
                </button>
               
            </div>
        </div>
    </main>

    <form method="POST">
        <div class="modal fade" id="modalAdicionar" tabindex="-1" aria-labelledby="modalAdicionarLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAdicionarLabel">Adicionar Novo Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" name="titulo" required placeholder="Digite o nome do item...">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="nova_tarefa"  class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form method="POST">
        <input type="hidden" name="id" id="edit-id">

        <div class="modal fade" 
        id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarLabel">Editar Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body d-flex align-items-center gap-1">
                        
                        <div class="form-check">
                                <input class="form-check-input me-3" 
                                name="concluido" id="edit-concluido"  type="checkbox" >
                            </div>

                        <input type="text" class="form-control" name="titulo" id="edit-titulo">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar" class="btn btn-primary">Atualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script>
        
        const modalEditar = document.getElementById('modalEditar');

        modalEditar.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        document.getElementById('edit-id').value =
        button.getAttribute('data-id');

        document.getElementById('edit-titulo').value =
        button.getAttribute('data-titulo');

        document.getElementById('edit-concluido').checked =
        button.getAttribute('data-concluido') == 1;

        });

    </script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
