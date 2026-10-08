<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Salario Quincenal</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary:   #621a6b;
            --secondary: #eb25ca;
            --accent:    #f0a500;
            --light-bg:  #f0f4ff;
            --card-bg:   #ffffff;
        }

        body {
            background: linear-gradient(135deg, #e8f0fe 0%, #dbeafe 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }

        /* ── Header ── */
        .app-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            border-radius: 16px 16px 0 0;
            padding: 2rem 2.5rem 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .app-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .app-header::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -30px;
            width: 240px; height: 240px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .app-header .icon-wrap {
            width: 56px; height: 56px;
            background: rgba(255,255,255,0.15);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem;
            margin-bottom: .75rem;
        }
        .app-header h1 { font-size: 1.6rem; font-weight: 700; margin: 0; }
        .app-header p  { font-size: .9rem; opacity: .8; margin: .25rem 0 0; }

        /* ── Card ── */
        .main-card {
            border: none;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 20px 60px rgba(26,58,107,.15);
        }

        /* ── Section titles ── */
        .section-title {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--secondary);
            border-left: 3px solid var(--accent);
            padding-left: .6rem;
            margin-bottom: 1.2rem;
        }

        /* ── Labels & inputs ── */
        .form-label {
            font-weight: 600;
            font-size: .875rem;
            color: #374151;
            margin-bottom: .3rem;
        }
        .form-control, .form-select {
            border: 1.5px solid #d1d5db;
            border-radius: 10px;
            padding: .6rem .9rem;
            font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }
        .input-group-text {
            background: var(--light-bg);
            border: 1.5px solid #d1d5db;
            border-radius: 10px 0 0 10px;
            color: var(--secondary);
            font-weight: 600;
        }
        .input-group .form-control { border-radius: 0 10px 10px 0; }

        /* ── Tarifas badge ── */
        .tarifa-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: var(--light-bg);
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: .4rem .8rem;
            font-size: .82rem;
            color: var(--primary);
            font-weight: 600;
        }
        .tarifa-badge .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
        }

        /* ── Submit button ── */
        .btn-calcular {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            padding: .8rem 2.5rem;
            border-radius: 12px;
            letter-spacing: .03em;
            transition: transform .15s, box-shadow .15s;
        }
        .btn-calcular:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,.35);
            color: #fff;
        }
        .btn-calcular:active { transform: translateY(0); }

        /* ── Alert errors ── */
        .alert-custom {
            border-radius: 12px;
            border-left: 4px solid #ef4444;
            background: #fef2f2;
            color: #b91c1c;
        }

        /* ── Footer note ── */
        .footer-note {
            font-size: .78rem;
            color: #9ca3af;
            text-align: center;
            padding: 1rem 0 .5rem;
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 720px;">

    <!-- ── Header ── -->
    <div class="app-header">
        <div class="icon-wrap"><i class="bi bi-calculator-fill"></i></div>
        <h1>Calculadora de Salario Quincenal</h1>
        <p>Ingrese los datos del empleado para obtener el cálculo detallado</p>
    </div>

    <!-- ── Main Card ── -->
    <div class="card main-card">
        <div class="card-body p-4 p-md-5">

            <?php
            /* ─────────────────────────────────────────────
             *  PROCESAMIENTO PHP (mismo archivo, método POST)
             * ───────────────────────────────────────────── */

            // Tarifas fijas por hora (Bs.)
            define('TARIFA_DIURNA',      675.00);
            define('TARIFA_VESPERTINA',  700.00);
            define('TARIFA_NOCTURNA',    956.23);

            $errores   = [];
            $resultado = null;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                /* ── 1. Recoger y sanear entradas ── */
                $nombre     = trim(htmlspecialchars($_POST['nombre']     ?? ''));
                $cedula     = trim(htmlspecialchars($_POST['cedula']     ?? ''));
                $h_diurnas  = trim($_POST['h_diurnas']  ?? '');
                $h_vespert  = trim($_POST['h_vespert']  ?? '');
                $h_nocturna = trim($_POST['h_nocturna'] ?? '');

                /* ── 2. Validaciones ── */
                if ($nombre === '') {
                    $errores[] = 'El campo <strong>Nombre completo</strong> es obligatorio.';
                }
                if ($cedula === '') {
                    $errores[] = 'El campo <strong>Cédula de identidad</strong> es obligatorio.';
                } elseif (!preg_match('/^\d{6,9}$/', $cedula)) {
                    $errores[] = 'La <strong>Cédula de identidad</strong> debe contener entre 6 y 9 dígitos numéricos.';
                }

                // Función auxiliar: valida número >= 0
                $validarHoras = function(string $valor, string $campo) use (&$errores): ?float {
                    if ($valor === '') {
                        $errores[] = "El campo <strong>$campo</strong> es obligatorio.";
                        return null;
                    }
                    if (!is_numeric($valor) || (float)$valor < 0) {
                        $errores[] = "El campo <strong>$campo</strong> debe ser un número mayor o igual a 0.";
                        return null;
                    }
                    return (float)$valor;
                };

                $hd = $validarHoras($h_diurnas,  'Horas turno diurno');
                $hv = $validarHoras($h_vespert,  'Horas turno vespertino');
                $hn = $validarHoras($h_nocturna, 'Horas turno nocturno');

                /* ── 3. Cálculos (solo si no hay errores) ── */
                if (empty($errores) && $hd !== null && $hv !== null && $hn !== null) {

                    $bruto_diurno    = $hd * TARIFA_DIURNA;
                    $bruto_vespert   = $hv * TARIFA_VESPERTINA;
                    $bruto_nocturno  = $hn * TARIFA_NOCTURNA;
                    $sueldo_bruto    = $bruto_diurno + $bruto_vespert + $bruto_nocturno;

                    // Escala de deducciones
                    if ($sueldo_bruto < 85000) {
                        $pct_ah = 0.001;   // 0.10%
                        $pct_ss = 0.0015;  // 0.15%
                        $escala = 'Escala I  (Bruto < Bs. 85.000,00)';
                    } elseif ($sueldo_bruto <= 150000) {
                        $pct_ah = 0.0015;  // 0.15%
                        $pct_ss = 0.002;   // 0.20%
                        $escala = 'Escala II  (Bs. 85.000,00 – Bs. 150.000,00)';
                    } else {
                        $pct_ah = 0.003;   // 0.30%
                        $pct_ss = 0.0025;  // 0.25%
                        $escala = 'Escala III  (Bruto > Bs. 150.000,00)';
                    }

                    $monto_ah     = $sueldo_bruto * $pct_ah;
                    $monto_ss     = $sueldo_bruto * $pct_ss;
                    $total_ded    = $monto_ah + $monto_ss;
                    $sueldo_neto  = $sueldo_bruto - $total_ded;

                    // Guardar en array para mostrar
                    $resultado = compact(
                        'nombre', 'cedula',
                        'hd', 'hv', 'hn',
                        'bruto_diurno', 'bruto_vespert', 'bruto_nocturno',
                        'sueldo_bruto',
                        'pct_ah', 'pct_ss', 'escala',
                        'monto_ah', 'monto_ss',
                        'total_ded', 'sueldo_neto'
                    );
                }
            }

            /* ─────────────────
             *  Helper: formatear
             * ───────────────── */
            function bs(float $valor): string {
                return 'Bs.&nbsp;' . number_format($valor, 2, ',', '.');
            }
            function pct(float $decimal): string {
                return number_format($decimal * 100, 2) . '%';
            }
            ?>

            <?php if (!empty($errores)): ?>
                <!-- ── Errores de validación ── -->
                <div class="alert alert-custom mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Por favor corrija los siguientes errores:</div>
                    <ul class="mb-0 mt-1 ps-3">
                        <?php foreach ($errores as $e): ?>
                            <li><?= $e ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($resultado): ?>
                <!-- ══════════════════════════════════
                     TARJETA DE RESULTADOS
                     ══════════════════════════════════ -->
                <div class="mb-5">
                    <!-- Encabezado empleado -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:52px;height:52px;background:linear-gradient(135deg,#1a3a6b,#2563eb);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5"><?= htmlspecialchars($resultado['nombre']) ?></div>
                            <div class="text-muted" style="font-size:.85rem;"><i class="bi bi-card-text me-1"></i>C.I.: <?= htmlspecialchars($resultado['cedula']) ?></div>
                        </div>
                        <span class="badge ms-auto" style="background:#dcfce7;color:#166534;font-size:.8rem;padding:.45rem .9rem;border-radius:20px;">
                            <i class="bi bi-check-circle-fill me-1"></i>Calculado
                        </span>
                    </div>

                    <!-- Escala aplicada -->
                    <div class="alert mb-4 py-2 px-3" style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;font-size:.85rem;color:#1e40af;">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Escala salarial aplicada:</strong> <?= $resultado['escala'] ?>
                        &nbsp;|&nbsp; Ahorro Habitacional: <strong><?= pct($resultado['pct_ah']) ?></strong>
                        &nbsp;|&nbsp; Seguro Social: <strong><?= pct($resultado['pct_ss']) ?></strong>
                    </div>

                    <!-- Desglose de horas -->
                    <p class="section-title">Desglose por Turno</p>
                    <div class="row g-3 mb-4">
                        <?php
                        $turnos = [
                            ['Diurno',      'bi-sun-fill',        '#fef9c3','#854d0e', $resultado['hd'], TARIFA_DIURNA,     $resultado['bruto_diurno']],
                            ['Vespertino',  'bi-sunset-fill',     '#ffedd5','#9a3412', $resultado['hv'], TARIFA_VESPERTINA, $resultado['bruto_vespert']],
                            ['Nocturno',    'bi-moon-stars-fill', '#ede9fe','#4c1d95', $resultado['hn'], TARIFA_NOCTURNA,   $resultado['bruto_nocturno']],
                        ];
                        foreach ($turnos as [$label, $icon, $bg, $color, $h, $tarifa, $sub]):
                        ?>
                        <div class="col-md-4">
                            <div class="p-3 h-100" style="background:<?= $bg ?>;border-radius:12px;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:<?= $color ?>;">
                                        <?= $label ?>
                                    </span>
                                    <i class="<?= $icon ?>" style="color:<?= $color ?>;font-size:1.1rem;"></i>
                                </div>
                                <div style="font-size:.82rem;color:<?= $color ?>;opacity:.8;"><?= number_format($h, 2, ',', '.') ?> h × <?= bs($tarifa) ?>/h</div>
                                <div style="font-size:1.15rem;font-weight:700;color:<?= $color ?>;margin-top:.3rem;"><?= bs($sub) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Resumen financiero -->
                    <p class="section-title">Resumen Financiero</p>
                    <div class="table-responsive">
                        <table class="table table-borderless" style="font-size:.9rem;">
                            <tbody>
                                <tr style="background:#f8fafc;border-radius:10px;">
                                    <td class="py-3 ps-3 fw-semibold text-muted">Sueldo Bruto</td>
                                    <td class="py-3 pe-3 text-end fw-bold fs-5" style="color:var(--primary);">
                                        <?= bs($resultado['sueldo_bruto']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 ps-3 text-muted">
                                        <i class="bi bi-dash-circle text-danger me-2"></i>
                                        Ahorro Habitacional (<?= pct($resultado['pct_ah']) ?>)
                                    </td>
                                    <td class="py-2 pe-3 text-end text-danger fw-semibold">
                                        − <?= bs($resultado['monto_ah']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 ps-3 text-muted">
                                        <i class="bi bi-dash-circle text-danger me-2"></i>
                                        Seguro Social (<?= pct($resultado['pct_ss']) ?>)
                                    </td>
                                    <td class="py-2 pe-3 text-end text-danger fw-semibold">
                                        − <?= bs($resultado['monto_ss']) ?>
                                    </td>
                                </tr>
                                <tr style="border-top:2px dashed #e5e7eb;">
                                    <td class="py-2 ps-3 text-muted fw-semibold">Total Deducciones</td>
                                    <td class="py-2 pe-3 text-end fw-bold text-danger">
                                        − <?= bs($resultado['total_ded']) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Sueldo Neto destacado -->
                    <div class="p-4 mt-2" style="background:linear-gradient(135deg,#1a3a6b 0%,#2563eb 100%);border-radius:14px;color:#fff;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <div style="font-size:.8rem;opacity:.75;text-transform:uppercase;letter-spacing:.1em;">Sueldo Neto Quincenal</div>
                                <div style="font-size:2rem;font-weight:800;letter-spacing:-.02em;">
                                    <?= bs($resultado['sueldo_neto']) ?>
                                </div>
                            </div>
                            <div style="width:60px;height:60px;background:rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.8rem;">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Botón volver -->
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-outline-secondary" style="border-radius:10px;font-weight:600;">
                            <i class="bi bi-arrow-left-circle me-2"></i>Calcular otro empleado
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- ══════════════════════════════════
                     FORMULARIO DE ENTRADA
                     ══════════════════════════════════ -->
                <form method="POST" action="index.php" novalidate>

                    <!-- ── Datos personales ── -->
                    <p class="section-title"><i class="bi bi-person-lines-fill me-1"></i>Datos del Empleado</p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label for="nombre" class="form-label">Nombre completo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" id="nombre" name="nombre" class="form-control"
                                       placeholder="Ej.: María García Rodríguez"
                                       value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="cedula" class="form-label">Cédula de identidad <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" id="cedula" name="cedula" class="form-control"
                                       placeholder="Ej.: 12345678"
                                       value="<?= htmlspecialchars($_POST['cedula'] ?? '') ?>"
                                       maxlength="9" required>
                            </div>
                        </div>
                    </div>

                    <!-- ── Tarifas de referencia ── -->
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="tarifa-badge"><span class="dot" style="background:#ca8a04;"></span>Diurna: Bs. 675,00/h</span>
                        <span class="tarifa-badge"><span class="dot" style="background:#ea580c;"></span>Vespertina: Bs. 700,00/h</span>
                        <span class="tarifa-badge"><span class="dot" style="background:#7c3aed;"></span>Nocturna: Bs. 956,23/h</span>
                    </div>

                    <!-- ── Horas trabajadas ── -->
                    <p class="section-title"><i class="bi bi-clock-history me-1"></i>Horas Trabajadas por Turno</p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="h_diurnas" class="form-label">
                                <i class="bi bi-sun-fill text-warning me-1"></i>Turno Diurno (h)
                            </label>
                            <input type="number" id="h_diurnas" name="h_diurnas" class="form-control"
                                   placeholder="0" min="0" step="0.5"
                                   value="<?= htmlspecialchars($_POST['h_diurnas'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="h_vespert" class="form-label">
                                <i class="bi bi-sunset-fill text-warning me-1" style="color:#ea580c!important;"></i>Turno Vespertino (h)
                            </label>
                            <input type="number" id="h_vespert" name="h_vespert" class="form-control"
                                   placeholder="0" min="0" step="0.5"
                                   value="<?= htmlspecialchars($_POST['h_vespert'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="h_nocturna" class="form-label">
                                <i class="bi bi-moon-stars-fill me-1" style="color:#7c3aed;"></i>Turno Nocturno (h)
                            </label>
                            <input type="number" id="h_nocturna" name="h_nocturna" class="form-control"
                                   placeholder="0" min="0" step="0.5"
                                   value="<?= htmlspecialchars($_POST['h_nocturna'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- ── Escalas informativas ── -->
                    <div class="p-3 mb-4" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;font-size:.82rem;color:#475569;">
                        <div class="fw-bold mb-2" style="color:#1e40af;"><i class="bi bi-layers me-1"></i>Escalas de deducciones aplicables:</div>
                        <div class="row g-1">
                            <div class="col-12 col-sm-4"><span style="color:#16a34a;">●</span> &lt; Bs. 85.000 → AH 0,10% | SS 0,15%</div>
                            <div class="col-12 col-sm-4"><span style="color:#d97706;">●</span> Bs. 85.000–150.000 → AH 0,15% | SS 0,20%</div>
                            <div class="col-12 col-sm-4"><span style="color:#dc2626;">●</span> &gt; Bs. 150.000 → AH 0,30% | SS 0,25%</div>
                        </div>
                    </div>

                    <!-- ── Submit ── -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-calcular btn-lg">
                            <i class="bi bi-calculator me-2"></i>Calcular Salario Quincenal
                        </button>
                    </div>

                </form>

            <?php endif; ?>

            <div class="footer-note">
                <i class="bi bi-shield-check me-1"></i>
                Sistema de Nómina Quincenal &nbsp;·&nbsp; Tarifas vigentes Bs. 2025
            </div>

        </div><!-- /card-body -->
    </div><!-- /main-card -->

</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
