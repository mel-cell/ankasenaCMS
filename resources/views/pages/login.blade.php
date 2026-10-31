@extends('layouts.site')

@section('title', 'Masuk')
@section('meta_description', 'Masuk ke akun Ankasena')

@section('content')
<section class="w pagehead">
    <h1>Masuk</h1>
    <p>Masuk untuk memilih kandidat, bikin twibon, atau mengelola situs.</p>
</section>

<section class="w pagefoot">
    <div class="card" style="max-width:26rem;margin-inline:auto;padding:1.75rem">
        <form method="POST" action="/login" style="display:grid;gap:.9rem">
            @csrf
            <label style="display:grid;gap:.35rem;font-weight:600">
                Email
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       style="padding:.7rem .9rem;border:1px solid var(--color-line);border-radius:var(--radius);font:inherit">
            </label>
            <label style="display:grid;gap:.35rem;font-weight:600">
                Kata sandi
                <input type="password" name="password" required
                       style="padding:.7rem .9rem;border:1px solid var(--color-line);border-radius:var(--radius);font:inherit">
            </label>
            <label style="display:flex;gap:.5rem;align-items:center">
                <input type="checkbox" name="ingat" value="1"> Ingat saya
            </label>
            @if ($errors->any())
                <p style="color:var(--red,#D62828);font-size:.9rem">{{ $errors->first() }}</p>
            @endif
            <button type="submit" class="btn btn-primary">Masuk</button>
        </form>

        <div style="display:flex;align-items:center;gap:.75rem;margin:1.25rem 0;color:var(--color-mut,#5c6b74)">
            <span style="flex:1;height:1px;background:var(--color-line)"></span>atau<span style="flex:1;height:1px;background:var(--color-line)"></span>
        </div>

        <a href="/auth/google" class="btn btn-secondary" style="width:100%">
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M23.5 12.3c0-.9-.1-1.5-.3-2.2H12v4.1h6.5c-.1 1.1-.8 2.7-2.4 3.8l3.7 2.9c2.3-2.1 3.7-5.1 3.7-8.6z"/><path fill="#34A853" d="M12 24c3.2 0 5.9-1.1 7.9-2.9l-3.7-2.9c-1 .7-2.4 1.2-4.2 1.2-3.2 0-5.9-2.1-6.8-5l-3.9 3C3.3 21.3 7.3 24 12 24z"/><path fill="#FBBC05" d="M5.2 14.4c-.2-.7-.4-1.5-.4-2.4s.2-1.7.4-2.4l-3.9-3C.5 8.3 0 10.1 0 12s.5 3.7 1.3 5.4l3.9-3z"/><path fill="#EA4335" d="M12 4.7c1.8 0 3 .8 3.7 1.4l3.3-3.2C17.9 1.1 15.2 0 12 0 7.3 0 3.3 2.7 1.3 6.6l3.9 3c.9-2.9 3.6-4.9 6.8-4.9z"/></svg>
            Lanjut dengan Google
        </a>
    </div>
</section>
@endsection
