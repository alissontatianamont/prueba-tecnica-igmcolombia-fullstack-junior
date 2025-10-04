<!DOCTYPE html>
<html>
<head>
    <title>Debug Test</title>
</head>
<body>
    <h1>Laravel Debug Test</h1>
    <p>Si ves esta página, Laravel está funcionando correctamente.</p>
    <p>Environment: {{ app()->environment() }}</p>
    <p>Debug enabled: {{ config('app.debug') ? 'Yes' : 'No' }}</p>
    <p>Timestamp: {{ now() }}</p>
</body>
</html>