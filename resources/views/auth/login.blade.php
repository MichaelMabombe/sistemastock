<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SistemaStock</title>
    <style>
        body{margin:0;font-family:"Segoe UI",Tahoma,sans-serif;background:linear-gradient(120deg,#eaf7ee,#d7ebdf);min-height:100vh;display:grid;place-items:center}
        .card{background:#fff;border:1px solid #cfe2d4;border-radius:14px;padding:24px;max-width:380px;width:92%}
        h1{margin:0 0 14px;color:#133223}
        label{font-size:13px;color:#315442}
        input{width:100%;padding:10px;border-radius:8px;border:1px solid #b5ccb9;margin-top:4px;margin-bottom:12px}
        button{width:100%;padding:10px;border:0;border-radius:8px;background:#198754;color:#fff;font-weight:700;cursor:pointer}
        .err{background:#f8e4e6;border:1px solid #edc1c7;color:#87212e;border-radius:8px;padding:10px;margin-bottom:10px}
    </style>
</head>
<body>
    <form class="card" method="POST" action="{{ route('login.store') }}">
        @csrf
        <h1>Entrar no Sistema</h1>
        @if($errors->any())<div class="err">{{ $errors->first() }}</div>@endif
        <label>Email</label>
        <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@sistemastock.test">
        <label>Senha</label>
        <input type="password" name="password" required placeholder="********">
        <button type="submit">Entrar</button>
    </form>
</body>
</html>

