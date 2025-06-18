<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Leaderboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/lib/global_style.css">

</head>
<body>
  <header>
  <h1>Leaderboard</h1>
  <nav class="navbar">
      <ul class="nav-links">
          <li><a href="/">Game</a></li>
          <li><a href="/leaderboard">Leaderboard</a></li>
          <li><a href="/about">About us</a></li>
    </nav>
  </header>
  <div class="container">
    <div class="sort-buttons">
      <button data-sort="score" data-order="desc" class="active">Score (DESC)</button>
      <button data-sort="score" data-order="asc">Score (ASC)</button>
      <button data-sort="username" data-order="asc">Name (A → Z)</button>
      <button data-sort="username" data-order="desc">Name (Z → A)</button>
      <button data-sort="created_at" data-order="desc">Newest</button>
      <button data-sort="created_at" data-order="asc">Oldest</button>
    </div>
    <table id="leaderboard">
      <thead>
        <tr>
          <th>#</th>
          <th>Username</th>
          <th>Score</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <!-- highscores komen hier -->
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
          integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer"></script>

  <script>
    function loadHighscores(sortBy = 'score', order = 'desc') {
      fetch(`/api/highscores?sort_by=${sortBy}&order=${order}`)
        .then(res => res.json())
        .then(data => {
          const tbody = $('#leaderboard tbody');
          tbody.empty();
          data.forEach((item, index) => {
            const row = `
              <tr>
                <td>${index + 1}</td>
                <td>${item.username}</td>
                <td>${item.score}</td>
                <td>${new Date(item.created_at).toLocaleString()}</td>
              </tr>`;
            tbody.append(row);
          });
        })
        .catch(err => {
          console.error('Fout bij ophalen highscores:', err);
        });
    }

    $(document).ready(function () {
      loadHighscores(); // standaard

      $('.sort-buttons button').click(function () {
        $('.sort-buttons button').removeClass('active');
        $(this).addClass('active');

        const sortBy = $(this).data('sort');
        const order = $(this).data('order');
        loadHighscores(sortBy, order);
      });
    });
  </script>
</body>
</html>
