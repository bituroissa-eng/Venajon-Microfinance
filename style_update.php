<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);

    // Modernize cards
    $content = str_replace(
        'bg-white overflow-hidden shadow-sm sm:rounded-lg', 
        'bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100', 
        $content
    );

    // Modernize primary buttons
    $content = preg_replace(
        '/bg-blue-600 text-white rounded-md hover:bg-blue-700/', 
        'bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg hover:from-blue-700 hover:to-blue-600 shadow-md hover:shadow-lg transition-all duration-200 font-medium', 
        $content
    );

    // Modernize green buttons
    $content = preg_replace(
        '/bg-green-600 text-white rounded(.*?) hover:bg-green-700/', 
        'bg-gradient-to-r from-emerald-500 to-emerald-400 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-500 shadow-md hover:shadow-lg transition-all duration-200 font-medium', 
        $content
    );

    // Modernize headers
    $content = str_replace(
        'font-semibold text-xl text-gray-800 leading-tight',
        'font-bold text-2xl text-slate-800 leading-tight tracking-tight',
        $content
    );

    // Inputs focus styles
    $content = str_replace(
        'border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500',
        'border-slate-200 bg-slate-50 shadow-inner focus:border-blue-500 focus:ring-blue-500 focus:bg-white transition-colors duration-200',
        $content
    );

    // Table headers
    $content = str_replace(
        '<th class="border-b py-2 px-4',
        '<th class="border-b-2 border-slate-200 bg-slate-50 py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider',
        $content
    );

    // Table rows hover
    $content = str_replace(
        '<tr>
                                <td class="border-b',
        '<tr class="hover:bg-slate-50 transition-colors duration-150">
                                <td class="border-b',
        $content
    );
    
    // Labels
    $content = str_replace(
        'block text-sm font-medium text-gray-700',
        'block text-sm font-semibold text-slate-700 mb-1',
        $content
    );

    file_put_contents($path, $content);
}

echo "Views updated.\n";
