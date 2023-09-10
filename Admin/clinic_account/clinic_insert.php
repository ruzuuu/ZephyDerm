<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    </head>
    
    <body>
            <form method="post" action="insert_update_acc.php">
                    <!-- Add your input fields for the new account data here -->
                    <div class="mb-3">
                        <label for="fname" class="form-label">Firstname</label>
                        <input type="text" class="form-control" id="fname" name="fname" required>
                    </div>
                    <div class="mb-3">
                        <label for="fname" class="form-label">Lastname</label>
                        <input type="text" class="form-control" id="lname" name="lname" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                    <select name="role" id="" class="form-control" required>
                      <option selected="true" disabled>-- Select Role --</option>
                      <option value="Admin">Admin</option>
                      <option value="Derma">Derma</option>
                      <option value="Staff">Staff</option>
                    </select>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Insert</button>
                </form>
    </body>
</html>