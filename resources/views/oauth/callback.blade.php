<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  <script>
    window.opener.postMessage({ access_token: "{{ $access_token }}", token_type: "{{ $token_type }}", expires_at: "{{ $expires_at }}" }, "http://localhost:3000")
    window.close()
  </script>
</head>
<body></body>
</body>
</html>