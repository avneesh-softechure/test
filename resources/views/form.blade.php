<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


</head>

<body>
    <div class="container mt-5">

        <h2 class="mb-2">User List</h2>
        <div class="card mb-4">
            <div class="card-body">

                <table class="table " id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Description</th>
                            <th>Role</th>
                            <th>Profile Picture</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-3" id="responseMessage" style="display: none;">
            <div class="alert" role="alert"></div>
        </div>

        <div class="card">

            <div class="card-body">
                <form id="myForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="desc" class="form-label">Description</label>
                        <textarea class="form-control" id="desc" name="desc" rows="3" placeholder="Enter description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="1">Admin</option>
                            <option value="2">User</option>
                            <option value="3">Guest</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="profile_pic" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept="image/*"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function() {

            var table = $('#usersTable').DataTable();

            function loadUsers() {
                $.ajax({
                    url: '/api/users',
                    method: 'GET',
                    success: function(response) {
                        table.clear();

                        if (response.success) {
                            var users = response.data;

                            if (users.length === 0) {
                                table.row.add(['', '', '', 'No records found', '', '']).draw();
                            } else {
                                users.forEach(function(user) {
                                    var profilePic = user.profile_pic ?
                                        `<img src="/storage/${user.profile_pic}" width="50" height="50" />` :
                                        'N/A';
                                    var row = [
                                        user.id,
                                        user.name,
                                        user.email,
                                        user.desc,
                                        user.role,
                                        profilePic
                                    ];
                                    table.row.add(row).draw();
                                });
                            }
                        } else {
                            alert('Failed to fetch users data.');
                        }
                    },
                    error: function() {
                        alert('Error fetching users data.');
                    }
                });
            }

            loadUsers();

            $('#myForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: '/api/save-data',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#responseMessage').show();
                        $('#responseMessage .alert').addClass('alert-success').text(response
                            .message);

                        $('#myForm')[0].reset();

                        loadUsers();
                    },
                    error: function(xhr, status, error) {
                        $('#responseMessage').show();
                        $('#responseMessage .alert').addClass('alert-danger').text(
                            'Error saving data!');
                    }
                });
            });

        });
    </script>

</body>

</html>
