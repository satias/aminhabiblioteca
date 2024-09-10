<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

$user_id = "";
session_start();
if (isset($_SESSION['user_dados'])) {
    // Se 'user_dados' estiver definida
    $user_id = $_SESSION['user_dados']['id'];
}

$notificacoes = $controlo->notificacao_tab($user_id);

if ($notificacoes['success']) {
    foreach ($notificacoes['data'] as $notificacao) {
        $formattedDate = time_elapsed_string($notificacao['created_at']);
        ?>
        <div class="d-flex flex-column border-bottom-accent py-2 px-1">
            <span class="color-text texto-font font-w-500"><?php echo $notificacao['title']?></span>
            <span class="color-primary texto-font"><?php echo $notificacao['description']?></span>
            <span class="color-primary texto-pequeno-font"><?php echo $formattedDate?></span>
        </div>
        <?php
    }
}

function time_elapsed_string($datetime, $full = false)
{
    global $ano, $mes, $semana, $dia, $hora, $minuto, $segundo, $atras, $agora_mesmo;

    $now = new DateTime;
    $ago = new DateTime($datetime);

    // Verifica se a data é futura e retorna uma mensagem apropriada
    if ($ago > $now) {
        return "Data no futuro";
    }

    $diff = $now->diff($ago);

    // Mapear os intervalos de tempo para as unidades correspondentes
    $string = [
        'y' => $ano,
        'm' => $mes,
        'w' => '',
        'd' => $dia,
        'h' => $hora,
        'i' => $minuto,
        's' => $segundo,
    ];

    // Cálculo manual para semanas
    if ($diff->days >= 7) {
        $string['w'] = floor($diff->days / 7);
        $diff->d = $diff->days % 7;
    }

    foreach ($string as $k => &$v) {
        if ($k == 'w' && $v) {
            $v = $v . ' ' . $semana . ($v > 1 ? 's' : '');
        } elseif ($diff->$k) {
            $value = $diff->$k;
            if ($v == 'mês' && $value > 1) {
                $v = $value . ' meses';
            } elseif ($v == 'ano' && $value > 1) {
                $v = $value . ' anos';
            } else {
                $v = $value . ' ' . $v . ($value > 1 ? 's' : '');
            }
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) {
        $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . ' ' . $atras : $agora_mesmo;
}
