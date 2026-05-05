@extends('layouts.app')

@section('cascadingstyle')
<style>
  body,
  .main-content {
    font-family: 'IBM Plex Sans', Arial, sans-serif;
    background: #FDFDF9;
    color: #0F2C3E;
  }

  h1,
  h2,
  h3,
  h4 {
    font-family: 'Barlow', Arial, sans-serif;
  }

  .force-login-shell {
    min-height: 100vh;
    background-color: #FDFDF9;
  }

  .force-login-brand {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 96px;
    border-top: 1px solid #0F2C3E;
    border-bottom: 1px solid #0F2C3E;
    background: #FFFFFF;
  }

  .force-login-brand img {
    width: 150px;
    height: auto;
    display: block;
  }

  .force-login-panel {
    width: min(100% - 48px, 600px);
    margin: 88px auto 0;
  }

  .force-login-title {
    margin: 0 0 24px;
    color: #222222;
    font-family: 'IBM Plex Sans', Arial, sans-serif;
    font-size: 56px;
    font-weight: 400;
    line-height: 1.05;
  }

  .force-login-copy {
    margin: 0 0 32px;
    color: #3E3E3E;
    font-size: 19px;
    line-height: 1.5;
  }

  .force-login-field {
    margin-bottom: 18px;
  }

  .force-login-field label {
    display: block;
    margin-bottom: 10px;
    color: #0F2C3E;
    font-size: 18px;
    font-weight: 700;
  }

  .force-login-field .form-control {
    width: 100%;
    height: 56px;
    padding: 14px 16px;
    border: 0;
    border-radius: 6px;
    background: #F3F3F3;
    color: #0F2C3E;
    font-size: 17px;
    box-shadow: none;
  }

  .force-login-field .form-control:focus {
    background: #FFFFFF;
    outline: 2px solid #4297A0;
    outline-offset: 0;
    box-shadow: none;
  }

  .force-login-hint {
    margin: -4px 0 28px;
    color: #6D6D6D;
    font-size: 16px;
    line-height: 1.45;
  }

  .force-login-submit {
    width: 100%;
    min-height: 56px;
    border: 0;
    border-radius: 8px;
    background: #FE646F !important;
    background-image: none !important;
    color: #FFFFFF;
    box-shadow: none;
    font-size: 18px;
    font-weight: 700;
  }

  .force-login-divider {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 24px;
    margin: 40px 0;
    color: #6D6D6D;
    font-size: 18px;
  }

  .force-login-divider::before,
  .force-login-divider::after {
    content: "";
    height: 1px;
    background: #0F2C3E;
  }

  .force-login-secondary {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 56px;
    border: 1px solid #0F2C3E;
    border-radius: 8px;
    background-image: none !important;
    background-clip: initial !important;
    -webkit-background-clip: initial !important;
    color: #0F2C3E !important;
    -webkit-text-fill-color: #0F2C3E !important;
    font-size: 18px;
    font-weight: 700;
    text-decoration: none;
  }

  .force-login-footer-note {
    margin: 72px 0 0;
    text-align: center;
    color: #3E3E3E;
    font-size: 18px;
  }

  .force-login-footer-note a {
    display: inline-block;
    margin-top: 12px;
    color: #FE646F !important;
    -webkit-text-fill-color: #FE646F !important;
    font-weight: 700;
    text-decoration: none;
  }

  @media (max-width: 640px) {
    .force-login-brand {
      min-height: 80px;
    }

    .force-login-panel {
      width: min(100% - 32px, 600px);
      margin-top: 56px;
    }

    .force-login-title {
      font-size: 44px;
    }
  }
</style>
@endsection

@section('content')
<main class="main-content mt-0 force-login-shell">
  <header class="force-login-brand">
    <a href="https://forcescholar.com/" aria-label="FORCE home">
      <img src="{{ asset('cohortregistration/2026 FORCE Logo_V6 1 1-Photoroom.png') }}" alt="FORCE">
    </a>
  </header>

  <section class="force-login-panel" aria-labelledby="force-login-title">
    <h1 id="force-login-title" class="force-login-title">Sign in</h1>
    <p class="force-login-copy">Enter your email or mobile number and password to access your FORCE account.</p>

    @include('includes/errors/validation-errors')

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="force-login-field">
        <label for="email">Email or Mobile No</label>
        <input id="email" type="text" name="email" class="form-control" autocomplete="username" required>
      </div>

      <div class="force-login-field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" class="form-control" autocomplete="current-password" required>
      </div>

      <p class="force-login-hint">Use the email or mobile number connected to your FORCE registration.</p>

      <button type="submit" class="force-login-submit">Sign in</button>

      <div class="force-login-divider" aria-hidden="true">or</div>

      <a href="{{ route('register') }}" class="force-login-secondary">Sign up for FORCE</a>
    </form>

    <p class="force-login-footer-note">
      New to FORCE?
      <br>
      <a href="{{ route('register') }}">Choose your FORCE pathway</a>
    </p>
  </section>
</main>
@endsection
