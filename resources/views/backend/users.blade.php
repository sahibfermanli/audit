@extends('parcel.app')
@section('title')
    Users
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="main-part-2">
                <div class="but-ser">
                    <div class="buttons">
                        <a onclick="add_user_modal();" style="padding: 5px 10px; cursor: pointer;">
                            <span class="glyphicon glyphicon-user"></span> Add new user
                        </a>
                    </div>
                    {{--<div class="search-but"><input type="text" placeholder="Search"></div>--}}
                </div>
                <div>
                    {!! $users->links(); !!}
                </div>
                <table class="references-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>E-mail</th>
                        <th>Authority</th>
                        <th>Created date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($row = 0)
                    @foreach($users as $user)
                        @php($row++)
                        <tr id="row_{{$user->id}}">
                            <td><span class="text-primary">{{$row}}</span></td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->surname}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->authority}}</td>
                            <td>{{$user->created_at}}</td>
                            <td class="td-actions">
                                <a class="glyphicon glyphicon-edit" onclick="get_update({{$user->id}});">
                                    <i class="la la-edit edit"></i>
                                </a>
                                <a class="glyphicon glyphicon-remove" onclick="del(this, '{{$user->id}}');">
                                    <i class="la la-close delete"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div>
                    {!! $users->links(); !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="users-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div style="clear: both;"></div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-heading">
                        <span class="masha_index masha_index1" rel="1"></span><span id="modal-title"></span> User
                    </div>
                </div>
                <form action="" method="post">
                    {{csrf_field()}}
                    <div id="type"></div>
                    <div id="user_id"></div>
                    <div class="modal-body">
                        <div class="form row">
                            <div class="col-md-6">
                                <p class="name">
                                    <label for="name">Name:<font color="red">*</font></label>
                                    <input type="text" name="name" id="name" required="">
                                </p>
                                <p class="phone email">
                                    <label for="email">Email :<font color="red">*</font></label>
                                    <input type="email" name="email" id="email" required="">
                                </p>
                                <p class="sec">
                                    <label for="authority_id">Authority :<font color="red">*</font></label>
                                    <select name="authority_id" id="authority_id">
                                        <option value="">Select</option>
                                        @foreach($authorities as $authority)
                                            <option value="{{$authority->id}}">{{$authority->authority}}</option>
                                        @endforeach
                                    </select>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="email surname">
                                    <label for="surname"> Surname <font color="red">*</font></label>
                                    <input type="text" name="surname" id="surname" required="">
                                </p>
                                <p class="kode">
                                    <label for="password">Password :<font color="red">*</font></label>
                                    <input type="password" name="password" id="password" required="">
                                </p>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="modal-footer">
                        <p class="submit">
                            <input type="button" value="Close" data-dismiss="modal"
                                   style=" margin-right: 25px;    background-color: #800029;">
                            <input type="submit" value="Add">
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="/css/sweetalert2.min.css">

    <style>
        td {
            white-space: nowrap;
        }
    </style>
@endsection

@section('js')
    <script src="/js/jquery.form.min.js"></script>
    <script src="/js/jquery.validate.min.js"></script>
    <script src="/js/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('form').validate();
            $('form').ajaxForm({
                beforeSubmit: function () {
                    //loading
                    swal({
                        title: '<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Please wait...</span>',
                        text: 'Loading, please wait...',
                        showConfirmButton: false
                    });
                },
                success: function (response) {
                    swal(
                        response.title,
                        response.content,
                        response.case
                    );
                    if (response.case === 'success') {
                        location.reload();
                    }
                }
            });
        });

        //add user modal
        function add_user_modal() {
            $('#modal-title').html('Add');
            $('#user_id').html('');
            $('#name').val('');
            $('#surname').val('');
            $('#email').val('');
            $('#authority').val('');
            $('#type').html('<input type="hidden" name="type" value="add">');

            $('#users-modal').modal('show');
        }

        function del(e, id) {
            swal({
                title: 'Are you sure you want to delete?',
                text: 'This process has no return...',
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete!'
            }).then(function (result) {
                if (result.value) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: "Post",
                        url: '',
                        data: {
                            'id': id,
                            'type': 'delete',
                            '_token': CSRF_TOKEN
                        },
                        beforeSubmit: function () {
                            //loading
                            swal({
                                title: '<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Please wait...</span>',
                                text: 'Loading, please wait..',
                                showConfirmButton: false
                            });
                        },
                        success: function (response) {
                            swal(
                                response.title,
                                response.content,
                                response.case
                            );
                            if (response.case === 'success') {
                                var elem = document.getElementById('row_' + response.id);
                                elem.parentNode.removeChild(elem);
                            }
                        }
                    });
                } else {
                    return false;
                }
            });
        }

        function get_update(id) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "Post",
                url: '',
                data: {
                    'id': id,
                    'type': 'get_update',
                    '_token': CSRF_TOKEN
                },
                beforeSubmit: function () {
                    //loading
                    swal({
                        title: '<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Please wait...</span>',
                        text: 'Loading, please wait..',
                        showConfirmButton: false
                    });
                },
                success: function (response) {
                    if (response.case === 'success') {
                        $('#modal-title').html('Edit');
                        $('#type').html('<input type="hidden" name="type" value="update">');

                        var user = response.user;

                        $('#user_id').html('<input type="hidden" name="id" value="' + id + '">');
                        $('#name').val(user['name']);
                        $('#surname').val(user['surname']);
                        $('#email').val(user['email']);
                        $('#authority_id').val(user['authority_id']);

                        $('#users-modal').modal('show');
                    } else {
                        swal(
                            response.title,
                            response.content,
                            response.case
                        );
                    }
                }
            });
        }
    </script>
@endsection