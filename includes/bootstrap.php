<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config/database.php';

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect(string $url): never { header('Location: ' . $url); exit; }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!current_user()) redirect('../pages/login.php'); }
function require_admin(): void { if (!current_user() || current_user()['role'] !== 'admin') redirect('../index.php'); require_once __DIR__.'/flash-popup.php'; }
function flash(string $type, string $message): void { $_SESSION['flash'] = ['type'=>$type, 'message'=>$message]; }
function get_flash(): ?array { $message = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $message; }
