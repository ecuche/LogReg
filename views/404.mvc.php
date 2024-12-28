<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 Error</title>
    <link href="<?= $_ENV['URL_ROOT'] ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="d-flex align-items-center justify-content-center vh-100 bg-dark">
        <div class="text-center">
            <h1 class="display-1 fw-bold">404</h1>
            <p class="fs-3">Page not found.</p>
            <p class="lead">
                The page you’re looking for doesn’t exist.
            </p>
            <a href="<?= $_ENV['URL_ROOT'] ?>" class="btn btn-primary">Go Home</a>
        </div>
    </div>

  </body>

</html>