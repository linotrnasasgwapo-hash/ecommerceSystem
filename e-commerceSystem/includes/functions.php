<?php
/**
 * Utility Functions
 */

function getCartCount(PDO $pdo, ?int $userId): int {
    if (!$userId) return 0;
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(quantity), 0) FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    return (int) $stmt->fetchColumn();
}

function formatPrice(float $price): string {
    return '$' . number_format($price, 2);
}

function sanitize(string $data): string {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function baseUrl(string $path = ''): string {
    return '/e-commerceSystem/' . ltrim($path, '/');
}
