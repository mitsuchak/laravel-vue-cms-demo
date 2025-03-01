<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add the CSRF Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <!-- Vue app will be mounted here -->
    </div>
</body>
</html>