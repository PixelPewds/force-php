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
    display: flex;
    flex-direction: column;
    height: 100vh;
    background-color: #FDFDF9;
  }

  .force-login-brand {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 72px;
    border-bottom: 1px solid #E0E0E0;
    background: #FFFFFF;
    flex-shrink: 0;
  }

  .force-login-brand img {
    width: 120px;
    height: auto;
    display: block;
  }

  .force-login-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow-y: auto;
    padding: 32px 24px;
  }

  .force-login-panel {
    width: 100%;
    max-width: 480px;
  }

  .force-login-title {
    margin: 0 0 16px;
    text-align: center;
    color: #222222;
    font-family: 'IBM Plex Sans', Arial, sans-serif;
    font-size: 40px;
    font-weight: 600;
    line-height: 1.1;
  }

  .force-login-copy {
    margin: 0 0 24px;
    text-align: center;
    color: #3E3E3E;
    font-size: 16px;
    line-height: 1.5;
  }

  .force-login-field {
    margin-bottom: 16px;
  }

  .force-login-field label {
    display: block;
    margin-bottom: 8px;
    color: #0F2C3E;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.3px;
  }

  .force-login-field .form-control {
    width: 100%;
    height: 48px;
    padding: 12px 14px;
    border: 1px solid #E0E0E0;
    border-radius: 6px;
    background: #FFFFFF;
    color: #0F2C3E;
    font-size: 15px;
    box-shadow: none;
    transition: all 0.2s ease;
  }

  .force-login-field .form-control::placeholder {
    color: #999999;
  }

  .force-login-field .form-control:focus {
    background: #FFFFFF;
    border-color: #FE646F;
    outline: none;
    box-shadow: 0 0 0 3px rgba(254, 100, 111, 0.1);
  }

  .force-login-hint {
    margin: 8px 0 20px;
    text-align: center;
    color: #6D6D6D;
    font-size: 14px;
    line-height: 1.4;
  }

  .force-login-submit {
    width: 100%;
    height: 48px;
    border: 0;
    border-radius: 6px;
    background: #FE646F !important;
    background-image: none !important;
    color: #FFFFFF;
    box-shadow: none;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: background 0.2s ease;
  }

  .force-login-submit:hover {
    background: #E8525A !important;
  }

  .force-login-submit:active {
    background: #D6434B !important;
  }

  .force-login-divider {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 16px;
    margin: 24px 0;
    color: #999999;
    font-size: 14px;
    font-weight: 500;
  }

  .force-login-divider::before,
  .force-login-divider::after {
    content: "";
    height: 1px;
    background: #E0E0E0;
  }

  .force-login-secondary {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 48px;
    border: 1px solid #0F2C3E;
    border-radius: 6px;
    background: #FFFFFF;
    color: #0F2C3E !important;
    -webkit-text-fill-color: #0F2C3E !important;
    font-size: 16px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .force-login-secondary:hover {
    background: #F5F5F5;
    border-color: #0F2C3E;
  }

  .force-login-footer-note {
    margin: 20px 0 0;
    text-align: center;
    color: #3E3E3E;
    font-size: 14px;
    line-height: 1.5;
  }

  .force-login-footer-note a {
    color: #FE646F !important;
    -webkit-text-fill-color: #FE646F !important;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s ease;
  }

  .force-login-footer-note a:hover {
    color: #E8525A !important;
    -webkit-text-fill-color: #E8525A !important;
  }

  @media (max-width: 640px) {
    .force-login-brand {
      height: 64px;
    }

    .force-login-brand img {
      width: 100px;
    }

    .force-login-container {
      padding: 24px 16px;
    }

    .force-login-panel {
      max-width: 100%;
    }

    .force-login-title {
      font-size: 32px;
      margin-bottom: 12px;
    }

    .force-login-copy {
      font-size: 14px;
      margin-bottom: 20px;
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

  <div class="force-login-container">
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

        <p class="force-login-footer-note">
          New to FORCE?<br>
          <a href="{{ route('register') }}">Choose your FORCE pathway</a>
        </p>

        <a href="{{ route('register') }}" class="force-login-secondary">Sign up for FORCE</a>
      </form>
    </section>
  </div>
</main>
@endsection
