<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
            form{
                padding: 0% 30%;
            }
            .show-data{
                padding-top: 5%;
            }
            .error {
                color: red;
                font-weight: bold;
            }
            #user-table{
                display: none;
            }
            img{
                width: 150px;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="show-data">
            <table id="user-table" class="table">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                        <td>Role ID</td>
                        <td>Profile Image</td>
                        <td>Description</td>
                    </tr>
                </thead>
                <tbody id="user-table-tbody">

                </tbody>
            </table>
        </div>
        <form name="user-form" id="user-form" enctype='multipart/form-data' method="post" action="javascript:void(0)">
            <div id="show-error display-none"><p id="show-error-p" class="error"></p></div>
            @csrf
            <div class="form-group">
                <label>Name</label>

                <input type="text" id="name" name="name" class="@error('name') is-invalid @enderror form-control">
                @error('name')
                <div class="text text-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>        
            <div class="form-group">
                <label>Email</label>
                <input type="text" id="email" name="email" class="@error('email') is-invalid @enderror form-control">
                @error('email')
                <div class="text text-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>     
            <div class="form-group">
                <label>Phone</label>
                <input type="text" id="phone" name="phone" class="@error('phone') is-invalid @enderror form-control">
                @error('phone')
                <div class="text text-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Role ID</label>
                <input type="text" id="role_id" name="role_id" class="@error('role_id') is-invalid @enderror form-control">
                @error('role_id')
                <div class="text text-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Profile Image</label>
                <input type="file" id="image" name="image" class="@error('image') is-invalid @enderror form-control">
                @error('image')
                <div class="text text-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="description" class="@error('description') is-invalid @enderror form-control"></textarea>
                @error('description')
                <div class="text text-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" id="user-submit" class="btn btn-primary">Submit</button>
        </form>  
        <script>
            
        $(document).ready(function() {

            $("#user-form").validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength:10,
                        maxlength:10
                    },
                    description: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                    },
                    email: {
                        required: "Please enter email",
                        email: "Please enter valid email address",
                    },
                    phone: {
                        required: "Please enter phone",
                        minlength: "Please enter indian phone",
                        maxlength: "Please enter indian phone",
                    },
                    description: {
                        required: "Please enter description",
                    },
                },
                
                submitHandler: function(form) {
                    $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $('#user-submit').html('Please Wait...');
                    $("#user-submit"). attr("disabled", true);

                    var image = $('#image')[0].files;
                    var name = $('#name').val();
                    var email = $('#email').val();
                    var phone = $('#phone').val();
                    var role_id = $('#role_id').val();
                    var description = $('#description').val();
                    
                    var formData = new FormData();

                    // Append data 
                    formData.append('name',name);
                    formData.append('email',email);
                    formData.append('phone',phone);
                    formData.append('role_id',role_id);
                    formData.append('description',description);
                    formData.append('image',image[0]);


                    $.ajax({
                        url: "{{url('save')}}",
                        type: "POST",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function( response ) {
                            
                            if(response.success==true){

                                $("#show-error").hide();
                                $("#show-error-p").hide();

                                // console.log('response==>>',response)
                                // console.log('response.data==>>',response.data)

                                $('#user-submit').html('Submit');
                                $("#user-submit"). attr("disabled", false);
                                
                                alert('Form has been submitted successfully');

                                document.getElementById("user-form").reset();
                                                       
                                var tboby = $('#user-table tbody');
                                tboby.empty(); //clear the data 

                                $.each(response.data, function (i, obj) {
                                    $("#user-table").find('tbody').append("<tr><td>" + obj.name + "</td><td>" + obj.email + "</td><td>" + obj.phone + "</td><td>" + obj.role_id + "</td><td><img src='upload/profile/" + obj.image + "'></td><td>" + obj.description + "</td></tr>");
                                });    
                                
                                $("#user-table").show();         

                            }
                            else{

                                $("#show-error").show();
                                $('#show-error-p').html(response.message);
                            }
                        }
                    });
                }
            })
        }); 
        </script>
    </body>
</html>
