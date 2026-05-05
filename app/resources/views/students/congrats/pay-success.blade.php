@extends('layouts.app')
@section('cascadingstyle')

  <style>
    @keyframes bounce {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-15px);
      }
    }

    .animate-bounce {
      animation: bounce 1s infinite;
    }

    /* 🎆 Confetti */
    .confetti-container {
      position: fixed;
      width: 100%;
      height: 100%;
      pointer-events: none;
      top: 0;
      left: 0;
      overflow: hidden;
      z-index: 9999;
    }

    .confetti {
      position: absolute;
      width: 8px;
      height: 8px;
      background: red;
      opacity: 0.7;
      animation: fall linear infinite;
    }

    @keyframes fall {
      to {
        transform: translateY(100vh) rotate(360deg);
      }
    }
  </style>
@endsection

@section('content')
    <div class="container py-5 text-center">
      <div class="card shadow-lg p-5 border-0 rounded-4">

        <div class="mb-4">
          <h1 class="display-3 animate-bounce">🎉🥳🎊</h1>
        </div>

        <h2 class="text-success fw-bold mb-3">
          Thank you!
        </h2>

        <p class="lead text-muted mb-4">
          Thank you for your payment! Your registration is being processed.
          <p><strong>Reference Id: </strong> {{ $clientReferenceId }}</p>
        </p>

      </div>
    </div>
@endsection
@section('javascript')
  <div class="confetti-container"></div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let container = document.querySelector('.confetti-container');
      for (let i = 0; i < 120; i++) {
        let confetti = document.createElement('div');
        confetti.classList.add('confetti');
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
        confetti.style.backgroundColor = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#4caf50', '#ff9800'][Math.floor(Math.random() * 8)];
        container.appendChild(confetti);
      }
    });
  </script>
@endsection