<!DOCTYPE html>
<html>

<head>
    <title>Organizador de tarefas - Cadastro de usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        <div class="container mt-5 d-flex align-items-center justify-content-center">
            <div class="w-50 d-flex flex-column justify-content-center align-itens-start">
                <h2>Cadastro de Usuário</h2>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                    <a href="login.php" class="btn btn-success">Login</a>
                    <a href="index.php" class="btn btn-primary">Voltar</a>
                <?php else: ?>
                    <form method="POST" action="include/processaCadastroUsuario.php">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control w-50" id="nome" name="nome" placeholder="Insira seu nome"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control w-50" id="email" name="email"
                                placeholder="Insira seu email" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control w-50" id="senha" name="senha"
                                placeholder="Insira sua senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <a href="index.php" class="btn btn-primary">Voltar</a>
                    </form>
                <?php endif; ?>
            </div>
            <img src="include/imgs/capav2.png" alt="imagem de capa" class="w-50" />
        </div>
</body>

</html>