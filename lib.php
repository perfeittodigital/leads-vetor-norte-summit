<?php
// Leads Vetor Norte Summit · Perfeitto Digital. PHP puro + SQLite (pronto para migrar a MySQL via PDO).
declare(strict_types=1);
mb_internal_encoding('UTF-8');
date_default_timezone_set('America/Sao_Paulo');
define('DADOS', dirname(__DIR__) . '/dados-leads');
define('EVENTO', 'Vetor Norte Summit');
define('V', '13');
if (!is_dir(DADOS)) { @mkdir(DADOS, 0750, true); @file_put_contents(DADOS . '/.htaccess', "Require all denied\n"); }
if (!is_dir(DADOS . '/sessoes')) @mkdir(DADOS . '/sessoes', 0750, true);
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
ini_set('session.save_path', DADOS . '/sessoes');
ini_set('session.gc_maxlifetime', '1209600');
session_set_cookie_params(['lifetime' => 1209600, 'path' => '/', 'secure' => $https, 'httponly' => true, 'samesite' => 'Lax']);
session_name('pdleads');
session_start();

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $novo = !file_exists(DADOS . '/leads.sqlite');
    $pdo = new PDO('sqlite:' . DADOS . '/leads.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA journal_mode=WAL; PRAGMA busy_timeout=4000;');
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (id INTEGER PRIMARY KEY AUTOINCREMENT, nome VARCHAR(120) NOT NULL, usuario VARCHAR(60) NOT NULL UNIQUE, senha VARCHAR(255) NOT NULL, papel VARCHAR(20) NOT NULL DEFAULT 'usuario', ativo INTEGER NOT NULL DEFAULT 1, trocar_senha INTEGER NOT NULL DEFAULT 1, criado_em DATETIME NOT NULL);
    CREATE TABLE IF NOT EXISTS leads (id INTEGER PRIMARY KEY AUTOINCREMENT, nome VARCHAR(160), whatsapp VARCHAR(30), email VARCHAR(160), empresa VARCHAR(160), tipo_negocio VARCHAR(120), observacao TEXT, interesse VARCHAR(20), contatado INTEGER NOT NULL DEFAULT 0, evento VARCHAR(80), usuario_id INTEGER NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME);
    CREATE TABLE IF NOT EXISTS materiais (id INTEGER PRIMARY KEY AUTOINCREMENT, titulo VARCHAR(160) NOT NULL, descricao TEXT, link VARCHAR(500) NOT NULL, usuario_id INTEGER, criado_em DATETIME NOT NULL);
    CREATE TABLE IF NOT EXISTS tentativas (id INTEGER PRIMARY KEY AUTOINCREMENT, ip VARCHAR(60), quando INTEGER);
    CREATE INDEX IF NOT EXISTS ix_leads_usuario ON leads(usuario_id); CREATE INDEX IF NOT EXISTS ix_leads_data ON leads(criado_em);");
    if (!$pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn()) {
        $st = $pdo->prepare('INSERT INTO usuarios (nome, usuario, senha, papel, criado_em) VALUES (?,?,?,?,?)');
        foreach ([['Edgard Barros', 'edgard', '$2y$10$H4YS2MzWQ9LS5X0BdV6QpOQKfDHnjDhRbdCIG6X.rZpXuYNq76HSi', 'admin'], ['Carolina', 'carolina', '$2y$10$3NEfM/3aIdxiVm8bIpZ8GOuzF6PPRPneVh9n4Uxx/hYVHRNxprq9q', 'gestor'], ['Pablo', 'pablo', '$2y$10$5hrhXTrLBOTGvPiCTYk/n.thXH3Mpsz/6m5LlVLgd.69Z8v5BpIiS', 'usuario']] as $u) $st->execute([$u[0], $u[1], $u[2], $u[3], date('Y-m-d H:i:s')]);
    }
    return $pdo;
}
function e(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function eu(): ?array {
    static $u = false;
    if ($u !== false) return $u;
    $u = null;
    if (!empty($_SESSION['uid'])) { $st = db()->prepare('SELECT * FROM usuarios WHERE id=? AND ativo=1'); $st->execute([$_SESSION['uid']]); $u = $st->fetch() ?: null; }
    return $u;
}
function gestor(): bool { $u = eu(); return $u && in_array($u['papel'], ['admin', 'gestor'], true); }
function admin(): bool { $u = eu(); return $u && $u['papel'] === 'admin'; }
function csrf(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(20)); return $_SESSION['csrf']; }
function csrf_ok(): bool { $t = $_POST['csrf'] ?? ($_SERVER['HTTP_X_CSRF'] ?? ''); return is_string($t) && $t !== '' && hash_equals($_SESSION['csrf'] ?? '', $t); }
function ir(string $p, string $msg = ''): void { if ($msg !== '') $_SESSION['msg'] = $msg; header('Location: ?p=' . $p); exit; }
function ajax(): bool { return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch'; }
function json_out(array $d, int $c = 200): void { http_response_code($c); header('Content-Type: application/json; charset=utf-8'); echo json_encode($d, JSON_UNESCAPED_UNICODE); exit; }
function titulo(string $s): string {
    $s = trim(preg_replace('/\s+/u', ' ', $s));
    if ($s === '') return '';
    $s = mb_convert_case(mb_strtolower($s), MB_CASE_TITLE);
    return preg_replace_callback('/(?<=\s)(De|Da|Do|Das|Dos|E)(?=\s)/u', fn($m) => mb_strtolower($m[1]), $s);
}
function so_digitos(string $s): string { $d = preg_replace('/\D/', '', $s); if (strlen($d) > 11 && str_starts_with($d, '55')) $d = substr($d, 2); return substr($d, 0, 11); }
function fone(string $s): string {
    $d = so_digitos($s);
    if (strlen($d) === 11) return '(' . substr($d, 0, 2) . ') ' . substr($d, 2, 5) . '-' . substr($d, 7);
    if (strlen($d) === 10) return '(' . substr($d, 0, 2) . ') ' . substr($d, 2, 4) . '-' . substr($d, 6);
    return $d;
}
function zap(array $l, string $quem): string {
    $d = so_digitos((string)$l['whatsapp']);
    if (strlen($d) < 10) return '';
    $pn = explode(' ', trim((string)$l['nome']))[0] ?? '';
    $msg = 'Oi' . ($pn !== '' ? ', ' . $pn : '') . '! Aqui é ' . explode(' ', $quem)[0] . ', da Perfeitto Digital. Estivemos juntos no ' . EVENTO . '. Posso te mostrar em 15 minutos onde a inteligência artificial pode te devolver tempo e vendas?';
    return 'https://wa.me/55' . $d . '?text=' . rawurlencode($msg);
}
function ip(): string { return (string)($_SERVER['REMOTE_ADDR'] ?? '0'); }
function bloqueado(): bool { $st = db()->prepare('SELECT COUNT(*) FROM tentativas WHERE ip=? AND quando>?'); $st->execute([ip(), time() - 600]); return (int)$st->fetchColumn() >= 8; }
