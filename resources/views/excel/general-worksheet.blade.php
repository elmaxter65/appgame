<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  </head>
  <body>
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Country</th>
            <th>City</th>
            <th>Ocupation</th>
            <th>Hospital</th>
            <th>E-mail address</th>
            <th>Register date</th>
            <th>Last log-in date</th>
            <th>Score</th>
            <th># Sessions(total)</th>
            <th># Capsule played(total)</th>
            <th># Questions played(total)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->country }}</td>
                <td>{{ $user->city }}</td>
                <td>{{ $user->ocupation }}</td>
                <td>{{ $user->hospital }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->register }}</td>
                <td>{{ $user->logIn }}</td>
                <td>{{ $user->score }}</td>
                <td>{{ $user->sessions }}</td>
                <td>{{ $user->capsule }}</td>
                <td>{{ $user->questions }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>

