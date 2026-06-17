<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error - Pages | 403</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <!-- Error -->
    <div class="container" style="margin-top: 120px;">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="mb-2 mx-2">Unauthorized 🙁</h2>
                <p class="mb-4 mx-2">Oops! 😖 You are not authorized to this action.</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back to home</a>
                <div class="mt-3">
                  <img
                    src="{{ asset('errors') }}/403.png" alt="man-with-laptop-light" class="img-fluid"
                  />
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
