<?php
require 'include/config.php';
require 'include/autenticacaoUsuario.php';

if (AlgumUsuarioAutenticado()) {
    header('Location: home.php');
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Organizador de tarefas - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <div class="container mt-5 d-flex align-items-center justify-content-center">
        <div class="w-50 d-flex flex-column justify-content-center align-itens-start">
            <form method="POST" action="include/processaLogin.php" class="w-100">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control w-50" id="email" name="email"
                        placeholder="Insira seu email">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control w-50" id="senha" name="senha"
                        placeholder="Insira sua senha" required>
                </div>
                <button type="submit" class="btn btn-primary">Entrar</button>
                <a href="index.php" class="btn btn-secondary">Voltar</a>
            </form>
        </div>
        <img src="include/imgs/capav2.png" alt="imagem de capa" class="w-50" />
    </div>
</body>

</html>