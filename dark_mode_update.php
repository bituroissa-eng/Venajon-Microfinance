<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);

    // Dark mode for text-gray-900 / text-slate-900 / text-gray-800 / text-slate-800
    $content = preg_replace('/text-gray-900/', 'text-slate-900 dark:text-slate-100', $content);
    $content = preg_replace('/text-slate-900(?!\s+dark:)/', 'text-slate-900 dark:text-slate-100', $content);
    $content = preg_replace('/text-gray-800/', 'text-slate-800 dark:text-slate-200', $content);
    $content = preg_replace('/text-slate-800(?!\s+dark:)/', 'text-slate-800 dark:text-slate-200', $content);
    
    // Subtext
    $content = preg_replace('/text-gray-500/', 'text-slate-500 dark:text-slate-400', $content);
    $content = preg_replace('/text-slate-500(?!\s+dark:)/', 'text-slate-500 dark:text-slate-400', $content);

    // Backgrounds
    $content = preg_replace('/bg-white(?!\s+dark:)/', 'bg-white dark:bg-slate-800', $content);
    $content = preg_replace('/bg-gray-50(?!\s+dark:)/', 'bg-slate-50 dark:bg-slate-900/50', $content);
    $content = preg_replace('/bg-slate-50(?!\s+dark:)/', 'bg-slate-50 dark:bg-slate-900/50', $content);

    // Borders
    $content = preg_replace('/border-gray-200/', 'border-slate-200 dark:border-slate-700', $content);
    $content = preg_replace('/border-slate-200(?!\s+dark:)/', 'border-slate-200 dark:border-slate-700', $content);
    $content = preg_replace('/border-b pb-4/', 'border-b border-slate-200 dark:border-slate-700 pb-4', $content);
    $content = preg_replace('/border-b pb-2/', 'border-b border-slate-200 dark:border-slate-700 pb-2', $content);

    // Status Badges
    $content = preg_replace('/bg-yellow-100 text-yellow-800/', 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200', $content);
    $content = preg_replace('/bg-blue-100 text-blue-800/', 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200', $content);
    $content = preg_replace('/bg-gray-100 text-gray-800/', 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300', $content);
    $content = preg_replace('/bg-green-100 text-green-800/', 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200', $content);

    // Table elements (clean up duplicate classes to rely on global css where possible or add dark variants)
    // Removed specific replace to let app.css handle inputs/tables mostly, but will add basic dark to elements that might be skipped
    $content = str_replace(
        'bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100', 
        'bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100 dark:border-slate-700 transition-colors duration-300', 
        $content
    );

    file_put_contents($path, $content);
}

echo "Dark mode classes injected into views.\n";
