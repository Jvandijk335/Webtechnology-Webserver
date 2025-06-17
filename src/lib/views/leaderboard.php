<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Leaderboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 20px;
      color: #333;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
    }

    .sort-buttons {
      text-align: center;
      margin-bottom: 20px;
    }

    .sort-buttons button {
      background-color: #e9eff5;
      border: none;
      padding: 10px 16px;
      margin: 5px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s;
    }

    .sort-buttons button:hover {
      background-color: #cddff1;
    }

    .sort-buttons button.active {
      background-color: #3498db;
      color: white;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 14px 16px;
      text-align: left;
    }

    th {
      background-color: #f7f9fb;
      border-bottom: 2px solid #e0e0e0;
      text-transform: uppercase;
      font-size: 14px;
      color: #555;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #eef6ff;
    }

    td {
      border-bottom: 1px solid #e0e0e0;
      font-size: 15px;
    }

    @media (max-width: 600px) {
      th, td {
        font-size: 13px;
        padding: 10px;
      }

      h2 {
        font-size: 20px;
      }

      .sort-buttons button {
        font-size: 12px;
        padding: 8px 12px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Highscores</h2>
    <div class="sort-buttons">
      <button data-sort="score" data-order="desc" class="active">Score (DESC</button>
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
