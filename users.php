<?php require_once ("header.php"); ?>

<div class="jumbotron" id="userVue">
    <div class="container">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4"><h3>Usuarios:</h3></div>
            <div class="col-4"></div>
        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <label for="name">Nome:</label>
                <input type="text" class="form-control" id="name" v-model="name">
            </div>
            <div class="col-4"></div>
        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" v-model="email">
            </div>
            <div class="col-4"></div>
        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <label for="password">Senha:</label>
                <input type="password" class="form-control" id="password" v-model="password">
            </div>
            <div class="col-4"></div>
        </div>
        <div class="row">
            <div class="col-6"></div>
            <div class="col-2">
                <br>
                <button class="btn btn-success btn-block" @click="save">Salvar</button>
            </div>
            <div class="col-4"></div>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in listUsers">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td><button class="btn btn-primary" @click="edit(index)">Editar</button></td>
                    <td><button class="btn btn-danger" @click="deleteUser(index)">Deletar</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    var userVue = new Vue({
        el: '#userVue',
        data(){
            return {
                name: '',
                email: '',
                password: '',
                id: '',
                listUsers: []
            }
        },
        methods: {
            save(){
                if(this.name == ""){
                    swal("Oops", "Digite o seu nome", "info");
                    return;
                }

                if(this.email == ""){
                    swal("Oops", "Digite o seu email","info");
                    return;
                }

                if(this.password == ""){
                    swal("Oops", "Digite a sua senha", "info");
                    return;
                }

                var data = {
                    name: this.name,
                    email: this.email,
                    password: this.password
                };

                var url =  config.server + "/auth/register";
                var method = "POST";

                if(this.id != ''){
                    url = config.server + "/user/" + this.id;
                    method = "PUT";
                }

                $.ajax({
                     method: method,
                     url: url,
                     data: data,
                     headers: config.headers
                }).done((result) => {
                    if(result.status){
                        this.clear();
                        this.list();
                        swal("Sucesso", "Usuario salvo com sucesso.", "success");
                    }else{
                        swal("Oops", result.msg, "info");
                    }
                });

            },
            edit(i){
                this.clear();
                this.id = this.listUsers[i].id;
                this.name = this.listUsers[i].name;
                this.email = this.listUsers[i].email;
            },
            list(){
                this.listUsers = [];

                $.ajax({
                    method: "GET",
                    url: config.server + "/users",
                    headers: config.headers
                }).done((result) => {
                    if(result.status){
                        this.listUsers = result.data;
                    }
                });
            },
            clear(){
                this.name = "";
                this.email = "";
                this.password = "";
                this.id = "";
            },
            deleteUser(i){
                $.ajax({
                    method: "DELETE",
                    url: config.server + "/user/" + this.listUsers[i].id,
                    headers: config.headers
                }).done((result) => {
                    this.list();
                    if(result.status){
                        swal("Sucesso", "Usuario deletado com sucesso.", "success");
                    }else{
                        swal("Oops", result.msg, "info");
                    }
                });
            }
        },
        mounted(){
            this.list();
        }
    })
</script>
<?php require_once ("footer.php"); ?>