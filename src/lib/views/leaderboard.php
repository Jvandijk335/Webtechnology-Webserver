<!doctype html>
<html lang="en">
  <head>
    <title>Leaderboard</title>
    <style>
      table {
        border-collapse: collapse;
        width: 100%;
        max-width: 600px;
        margin: auto;
      }
      th, td {
        border: 1px solid #ccc;
        padding: 8px 12px;
        text-align: left;
      }
      th {
        background-color: #f5f5f5;
      }
    </style>
  </head>
  <body>
    <h2 style="text-align:center">Top 10 Highscores</h2>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer">
    </script>
    <script>
      $(document).ready(function () {
        fetch('/api/highscores')
          .then(res => res.json())
          .then(data => {
            const tbody = $('#leaderboard tbody');
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
      });
    </script>
  </body>
</html>
