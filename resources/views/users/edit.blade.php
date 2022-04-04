@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row"> <!-- use class:justify-content-center to center card content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Update User</div>
    
                <div class="card-body">
                    <form id="form" method="post" action="{{route('users.update', $result)}}" enctype="multipart/form-data"  autocomplete="off">
                        @csrf 
                        @method('PUT')
    
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Username</label>
                            <div class="col-md-10">
                                <input id="username" name="username" value="{{ $result->username }}" type="text" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">User Number / Employe Number</label>
                            <div class="col-md-10">
                                <input id="user_number" name="user_number" value="{{ $result->user_number }}" type="text" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Status</label>
                            <div class="col-md-10">
                                <select id="status" name="status" data-placeholder="Select an option" class="form-control select2">  
                                    @php
                                        $array_select_option = ['Pending','Approved','Rejected','Resigned']
                                    @endphp
                                    <option></option>
                                    @foreach ($array_select_option as $data)
                                        @if($data==$result->status)
                                            <option value="{{ $data }}" selected="true">{{ $data }}</option>
                                        @else
                                            <option value="{{ $data }}">{{ $data }}</option>
                                        @endif 
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Name</label>
                            <div class="col-md-10">
                                <input id="name" name="name" value="{{ $result->name }}" type="text" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Email</label>
                            <div class="col-md-10">
                                <input id="email" name="email" value="{{ $result->email }}" type="text" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Designation</label>
                            <div class="col-md-10">
                                <input id="designation" name="designation" value="{{ $result->designation }}" type="text" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Telephone</label>
                            <div class="col-md-10">
                                <input id="telephone" name="telephone" value="{{ $result->telephone }}" type="text" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Supervisor #1</label>
                            <div class="col-md-10">
                                <select id="supervisor1_fk_users_id" name="supervisor1_fk_users_id" data-placeholder="Select a role" class="form-control select2" style="width: 100%;">  
                                    @foreach ($optionModel2 as $data)
                                        @if(in_array($data->name, $selected_roles))
                                            <option value="{{ $data->id }}" selected="true">{{ $data->name }}</option>
                                        @else
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endif 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Supervisor #2</label>
                            <div class="col-md-10">
                                <select id="supervisor2_fk_users_id" name="supervisor2_fk_users_id" data-placeholder="Select a role" class="form-control select2" style="width: 100%;">  
                                    @foreach ($optionModel2 as $data)
                                        @if(in_array($data->name, $selected_roles))
                                            <option value="{{ $data->id }}" selected="true">{{ $data->name }}</option>
                                        @else
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endif 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Roles</label>
                            <div class="col-md-10">
                                <select id="roles" name="roles[]" multiple="multiple" data-placeholder="Select a role" class="form-control select2" style="width: 100%;">  
                                    @foreach ($roles as $data)
                                        @if(in_array($data->name, $selected_roles))
                                            <option value="{{ $data->id }}" selected="true">{{ $data->name }}</option>
                                        @else
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endif 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button id="submit" type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Update</button>
                                <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fas fa-undo"></i> Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js') 
<script>
    $(function () {
        /****************************************************************************************************************************************/
        /* Initialize Select2 */ 
        //https://stackoverflow.com/questions/46148297/placeholder-not-working-in-select2
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
        
        /****************************************************************************************************************************************/
        /* Form submission */ 
        $('#form').submit(function (e) {
            e.preventDefault()                                  // prevent the form from 'submitting'
            var currentForm = this;                         
            var url = e.target.action                           // get the target
            var formData = new FormData(this)                   // get form data with file functionality, $(this).serialize() for non-file functionality
            $.ajax({                                            // $.post(url, formData, function (response){ }) is a shorthand
                url: url,
                type: 'POST',
                data: formData,
                beforeSend:function(){
                    $('#submit').attr('disabled','disabled');   //avoid user from submitting buttons simultaneously
                },
                success: function (response) {
                    $('#submit').attr('disabled', false);
                    $(":input").removeClass("is-invalid");                                                              //remove all input is-invalid class
                    $('.select2').closest('.form-group').removeClass("has-error"); 
                    if(response.errors){                                                                                // Checks for error
                        $.each(response.errors, function (i){                                                           //response.errors is JSON object, console.log(i);
                            $('#'+i).addClass("is-invalid");                                            //searh for input with name = i, then add class is-invalid
                            $('#'+i).closest('.form-group').addClass("has-error");                      //to put red border at select2 
                            $.each(response.errors[i], function (key, val) {
                                $('#'+i).closest('.form-group').find('.invalid-feedback').html(val);    //put validation error message
                            });
                        });
                    }
                    if(response.notification){                  
                        //if(response.notification.alert_type == 'success'){
                        //    currentForm.reset();              //for edit, no need to reset form
                        //}
                        /* Use generalized sweetalert2, see vendor/page.php */
                        toastr.fire({                           //always show toast
                            icon: response.notification.alert_type,
                            title: response.notification.message,
                            didDestroy: () => {
                                if(document.getElementsByClassName('is-invalid')[0]){
                                    /* Focus into first invalid field */
                                    document.getElementsByClassName('is-invalid')[0].scrollIntoView({ behavior: 'smooth' });
                                }
                            }
                        });
                    }
                },
                contentType: false,                             // false: to ensure that jQuery doesn’t insert its own Content-Type header
                processData: false,                             // false: Prevent jQuery from attempting to URL-encode the payload (to send a DOMDocument, or other non-processed data.)
            });
        });
    });
</script>
@endsection