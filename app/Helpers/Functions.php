<?php

use function Termwind\render;

if (!function_exists('printInfo')) {
    function printInfo(string $message): void
    {
        $infoDiv = <<<'HTML'
            <div>
                <div class="px-1 bg-green-700 text-black">Info</div>
                <em class="ml-1 text-gray-300">%s</em>
            </div>
        HTML;

        render(sprintf($infoDiv, $message));
    }
}
