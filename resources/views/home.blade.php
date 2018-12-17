@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div id="errors" class="alert alert-danger alert-dismissible fade show" style="display: none;" role="alert">
            <span id="alert-message"></span>
            <button type="button" class="close" id="alert-dismiss">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form action="#" method="post">
            <div class="input-group mb-3 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
                <input
                    type="text"
                    name="year"
                    class="form-control"
                    value="{{ old('year') }}"
                    placeholder="Enter an year"
                    required
                >

                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Year</th>
                    <th>Christmas Day</th>
                </tr>
            </thead>
            <tbody id="prime-years"></tbody>
        </table>
    </div>

    <script>
        var csrf_token = $('meta[name="csrf-token"]').attr('content')

        function getData() {
            $.ajax({
                url: "{{ route('prime-years.index') }}",
                type: "get",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                },
                success: function (response) {
                    $('#prime-years').html('');

                    $.each(response, function(key, prime_year) {
                        $('#prime-years').append('<tr>');
                        $('#prime-years').append(`<td>${prime_year.id}</td>`);
                        $('#prime-years').append(`<td>${prime_year.year}</td>`);
                        $('#prime-years').append(`<td>${prime_year.day}</td>`);
                        $('#prime-years').append('</tr>');
                    });
                }
            })
        }

        $(document).ready(function () {
            getData();

            // Store data
            $('form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('prime-years.store') }}",
                    data: $(this).serialize(),
                    type: "post",
                    beforeSend: function(xhr) {
                        $('#errors').hide();
                        $('#alert-message').html('');
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                    },
                    success: function (response) {
                        getData();
                        $('form')[0].reset();
                    },
                    error: function (response) {
                        if (response.status === 422) {
                            var error = response.responseJSON;
                            $('#errors').show();
                            $('#alert-message').html(error.errors.year[0]);
                        }
                    }
                })
            });

            // to dismiss the alert
            $('#alert-dismiss').on('click', function (e) {
                e.preventDefault();
                $('#errors').hide();
            });
        });
    </script>
@endsection
