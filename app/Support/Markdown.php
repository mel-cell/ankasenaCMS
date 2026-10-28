<?php

namespace App\Support;

/**
 * Konverter markdown minimal untuk konten artikel/halaman (paragraf, heading,
 * bold/italic/kode, link, list). Ganti ke league/commonmark kalau butuh tabel/dst.
 */
class Markdown
{
    public static function toHtml(?string $md): string
    {
        if (! $md) {
            return '';
        }

        $md = str_replace("\r\n", "\n", trim($md));
        $blocks = preg_split("/\n{2,}/", $md);
        $out = [];

        foreach ($blocks as $block) {
            $lines = explode("\n", trim($block));
            $isUl = collect($lines)->every(fn ($l) => str_starts_with(ltrim($l), '- '));
            $isOl = collect($lines)->every(fn ($l) => preg_match('/^\d+\.\s/', ltrim($l)));

            if ($isUl) {
                $out[] = '<ul>'.collect($lines)->map(fn ($l) => '<li>'.self::inline(substr(ltrim($l), 2)).'</li>')->implode('').'</ul>';
            } elseif ($isOl) {
                $out[] = '<ol>'.collect($lines)->map(fn ($l) => '<li>'.self::inline(preg_replace('/^\d+\.\s+/', '', ltrim($l))).'</li>')->implode('').'</ol>';
            } elseif (preg_match('/^(#{1,6})\s+(.+)$/', $lines[0], $m)) {
                $n = strlen($m[1]);
                $out[] = "<h{$n}>".self::inline($m[2])."</h{$n}>";
            } else {
                $out[] = '<p>'.self::inline($block).'</p>';
            }
        }

        return implode("\n", $out);
    }

    private static function inline(string $t): string
    {
        $t = e($t);
        $t = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $t);
        $t = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em>$1</em>', $t);
        $t = preg_replace('/`([^`]+)`/', '<code>$1</code>', $t);
        $t = preg_replace('/\[([^\]]+)\]\((https?:[^)\s]+)\)/', '<a href="$2" rel="noopener">$1</a>', $t);

        return nl2br($t);
    }
}
