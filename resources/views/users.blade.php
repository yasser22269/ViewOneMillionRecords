<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
        </style>
    </head>
    <body class="antialiased">
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" id="users">
                    <thead>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Options</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <!-- App scripts -->

        <script>
            $(document).ready(function () {
                $('#users').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                        "url": "{{ url('allUsers') }}",
                        "dataType": "json",
                        "type": "Post",
                        "data":{ _token: "{{csrf_token()}}"}
                    },
                    "columns": [
                        { "data": "id" },
                        { "data": "name" },
                        { "data": "email" },
                        { "data": "created_at" },
                        { "data": "active" , searchable: false},
                        { "data": "options", orderable: false, searchable: false }
                    ]

                });

                $('#users').on('click', '.btn-delete[data-remote]', function (e) {
                    e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var url = $(this).data('remote');
                    // confirm then
                    if (confirm('Are you sure you want to delete this?')) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            dataType: 'json',
                            data: {method: '_DELETE', submit: true}
                        }).always(function (data) {
                            $('#users').DataTable().draw(false);
                        });
                    }else
                        alert("You have cancelled!");
                });

            });
        </script>
    </body>
</html>
