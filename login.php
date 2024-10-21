<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid">
        <div class="row p-2 bg-color-purple">
            <h2 class="col-12">ChatApp</h2>
            <h5>Best chatting experience</h5>
        </div>
        <div class="row p-2 bg-color-gray max-height-90">
            <div class="col-12 d-flex">
                <form method="post" action="authenticate.php">
                    <h3><center>Type login and password:</center></h3>
                    <br/>
                    <input type="text" name="username" class="form-control" placeholder="Username" required><br>
                    <input type="password" name="password" class="form-control" placeholder="Password" required><br>
                    <button type="submit" class="btn btn-light form-control mt-2">Submit</button>
                    <center>
                    <br/>
                    <?php
                        if (isset($_GET['error']) && $_GET['error'] == '1') {
                            echo '<p style="color: red;">Invalid username or password!</p>';
                        }

                        if (isset($_GET['error']) && $_GET['error'] == '2') {
                            echo '<p style="color: red;">Account already logged in!</p>';
                        }
                    ?>
                    </center>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>