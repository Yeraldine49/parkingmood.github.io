<!DOCTYPE html>
require_once 'config/database.html';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $rol_seleccionado = $_POST['rol'] ?? '';

    if ($correo && $contrasena) {
        $conn = conectar();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ? AND rol = ?");
        $stmt->bind_param("ss", $correo, $rol_seleccionado);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre']     = $usuario['nombre'];
            $_SESSION['correo']     = $usuario['correo'];
            $_SESSION['rol']        = $usuario['rol'];

            // Redirigir según rol
            if ($usuario['rol'] === 'Cliente') {
                header('Location: cliente/dashboard.php');
            } else {
                header('Location: admin/dashboard.php');
            }
            exit();
        } else {
            $error = 'Correo, contraseña o rol incorrectos.';
        }
        $conn->close();
    } else {
        $error = 'Por favor completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Control - Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Nunito',sans-serif;
            min-height:100vh; display:flex;
            background: linear-gradient(135deg, #003087 0%, #0a5c2f 100%);
        }
        .left-panel {
            flex:1; display:flex; align-items:center; justify-content:center;
            padding:40px;
        }
        .left-content { text-align:center; color:#fff; max-width:380px; }
        .left-content .park-icon {
            width:120px; height:120px; background:rgba(255,255,255,.15);
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            font-size:60px; color:#fff; margin:0 auto 24px; border:3px solid rgba(255,255,255,.3);
        }
        .left-content h2 { font-size:32px; font-weight:900; margin-bottom:12px; }
        .left-content p { font-size:15px; opacity:.8; line-height:1.7; }

        .right-panel {
            width:420px; background:#fff;
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            padding:50px 40px;
        }
        .logo { display:flex; align-items:center; gap:12px; margin-bottom:32px; }
        .logo .icon { width:48px; height:48px; background:#28a745; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:22px; font-weight:900; }
        .logo .text strong { font-size:20px; color:#003087; display:block; line-height:1.1; }
        .logo .text span { font-size:11px; color:#28a745; text-transform:uppercase; letter-spacing:1px; font-weight:700; }

        h3 { font-size:22px; font-weight:800; color:#003087; margin-bottom:4px; }
        .subtitle { color:#888; font-size:14px; margin-bottom:28px; }

        .form-group { margin-bottom:16px; width:100%; }
        .form-group label { display:block; font-size:13px; font-weight:700; color:#555; margin-bottom:6px; }
        .input-wrap { position:relative; }
        .input-wrap i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#aaa; font-size:14px; }
        .form-control {
            width:100%; padding:11px 14px 11px 38px; border:1.5px solid #dee2e6;
            border-radius:8px; font-family:'Nunito',sans-serif; font-size:14px;
            transition:.2s; color:#333; background:#f8f9fa;
        }
        .form-control:focus { outline:none; border-color:#28a745; background:#fff; box-shadow:0 0 0 3px rgba(40,167,69,.12); }

        .btn-login {
            width:100%; padding:13px; background:#28a745; color:#fff; border:none;
            border-radius:8px; font-size:16px; font-weight:800; cursor:pointer;
            font-family:'Nunito',sans-serif; transition:.2s; margin-top:6px;
        }
        .btn-login:hover { background:#1e7e34; }

        .alert-error { background:#f8d7da; color:#721c24; border-left:4px solid #dc3545; padding:12px 14px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600; width:100%; }

        .back-link { margin-top:20px; font-size:13px; color:#888; }
        .back-link a { color:#003087; font-weight:700; text-decoration:none; }

        footer-note { font-size:12px; color:#bbb; margin-top:32px; }

        @media(max-width:700px) {
            .left-panel { display:none; }
            .right-panel { width:100%; }
        }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="left-content">
        <div class="park-icon"><i class="fas fa-parking"></i></div>
        <h2>Bienvenido a<br>Parking Control</h2>
        <p>Sistema inteligente para gestionar entradas, salidas, tiempos y cobros de tu estacionamiento.</p>
    </div>
</div>

<div class="right-panel">
    <div class="logo">
        <div class="icon">PM</div>
        <div class="text"><strong>PARKING</strong><span>CONTROL</span></div>
    </div>

    <h3>Iniciar Sesión</h3>
    <p class="subtitle">Accede a tu cuenta</p>

    <?php if ($error): ?>
    <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" style="width:100%;">
        <div class="form-group">
            <label>Correo electrónico</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo" class="form-control" placeholder="correo@ejemplo.com" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="contrasena" class="form-control" placeholder="Tu contraseña" required>
            </div>
        </div>
        <div class="form-group">
            <label>Selecciona tu rol</label>
            <div class="input-wrap">
                <i class="fas fa-user-tag"></i>
                <select name="rol" class="form-control" required>
                    <option value="">-- Selecciona tu rol --</option>
                    <option value="Administrador" <?= ($_POST['rol'] ?? '') === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                    <option value="Trabajador" <?= ($_POST['rol'] ?? '') === 'Trabajador' ? 'selected' : '' ?>>Trabajador</option>
                    <option value="Cliente" <?= ($_POST['rol'] ?? '') === 'Cliente' ? 'selected' : '' ?>>Cliente</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</button>
    </form>

    <p class="back-link">¿No tienes cuenta? <a href="index.php">Volver al inicio</a></p>
    <p style="font-size:11px;color:#ccc;margin-top:24px;text-align:center;">© 2026 Parking Control. Todos los derechos reservados.</p>
</div>

</body>
</html>
