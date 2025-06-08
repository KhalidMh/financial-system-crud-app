<?php

/**
 * Helper function to load view files
 *
 * @param string $view The view path relative to the Views directory
 * @param array $data Variables to extract into the view's scope
 * @return void
 */
function view(string $view, array $data = []): void
{
    // Extract any data variables into the current scope
    if (!empty($data)) {
        extract($data);
    }

    // Construct the full path to the view file
    $viewPath = __DIR__ . '/Views/' . $view . '.view.php';

    // Check if the file exists
    if (!file_exists($viewPath)) {
        throw new Exception("View '{$view}' not found at {$viewPath}");
    }

    // Include the view file
    require_once $viewPath;
}
