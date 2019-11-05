<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
            <div class="container" id="app">
            
        <div class="row">
            <div class="col-md-12">
                
                <div class="clearfix">
                    <h3>Laravel Vue Js CRUD Tutorial</h3>
                    <a class="btn btn-success btn-sm pull-right" @click="create()">Add New</a>
                    <!-- template for the modal component -->


                </div>
                
                <table class="table table-bordered table-condensed">
                    <tr>
                        <td>ID</td>
                        <td>NAME</td>
                        <td>EMAIL</td>
                        <td>PHONE</td>
                        <td>ACTION</td>
                    </tr>
                    <tr v-for="row in data">
                        <td>@{{ row.id }}</td>
                        <td>@{{ row.name }}</td>
                        <td>@{{ row.email }}</td>
                        <td>@{{ row.phone }}</td>
                        <td>
                            <button @click="edit(row)"
                                    type="button"
                                    class="btn btn-xs btn-warning"
                                    title="Edit Record">Edit</button>
                            <button @click="deleteRecord(row)"
                                    type="button"
                                    class="btn btn-xs btn-danger"
                                    title="Delete Record">Del</button>
                        </td>
                    </tr>
                </table>

                <div class="modal fade" id="modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">@{{ isInsert?'New Contact':'Edit Contact' }}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input class="form-control input-sm" type="text" v-model="Contact.name">
                                </div>
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <input class="form-control input-sm" type="email" v-model="Contact.email">
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input class="form-control input-sm" type="number" v-model="Contact.phone">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                <button v-if="isInsert" type="button" class="btn btn-primary"
                                        @click="store(Contact)">Save
                                </button>
                                <button v-if="!isInsert" type="button" class="btn btn-primary"
                                        @click="update(Contact)">Update
                                </button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.1"></script>

    <script>
        var csrtToken = '{{csrf_token()}}';
        var adminUrl='{{url('admin')}}';
       

        var app = new Vue({
            el:'#app',
            data:{
                data:[],
                isInsert:true,
                Contact:{name: null, email: null, phone: null}
            },
            created:function(){
                this.init()
            },
            methods:{
                init:function(){
                    this.$http.get(adminUrl + '/contacts/data')
                        .then(function (res) {
                            this.data = res.data
                        })
                },
                create:function(){
                    this.isInsert = true,
                        this.Contact = {}
                    $('#modal').modal()
                },
                store:function(data){
                    if (!confirm('Are you sure?')) return;
                    data._token = csrtToken;
                    this.$http.post(adminUrl + '/contacts/store', data)
                        .then(function (res) {
                            this.init();
                            $('#modal').modal('hide');
                            this.Contact = {}
                        })
                },
                edit:function(row){
                    this.isInsert = false,
                        this.Contact = row;
                    $('#modal').modal();
                },
                update:function(data){
                    if (!confirm('Are you sure?')) return;
                    data._token = csrtToken;
                    this.$http.post(adminUrl + '/contacts/update', data)
                        .then(function (res) {
                            this.init()
                            $('#modal').modal('hide');
                            this.Contact = {}
                        })
                },
                deleteRecord:function(row){
                    if (!confirm('Are you sure?')) return;
                    row._token = csrtToken;
                    this.$http.post(adminUrl + '/contacts/delete', row)
                        .then(function (res) {
                            this.init()
                        })
                }
            }
        })

    </script>
            </div>
        </div>
    </body>
</html>
