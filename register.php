<html>
    <head>
        <title>Cadastro de Produtos - Registro</title>
    </head>
    <body>
        <?php require_once ("imports.php"); ?>

        <div class="jumbotron" id="registerVue">
            <div class="container">
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4"><h3>Registro de Usuario:</h3></div>
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
                    <div class="col-4"></div>
                    <div class="col-2">
                        <br>
                        <button class="btn btn-primary btn-block" @click="login">Entrar</button>
                    </div>
                    <div class="col-2">
                        <br>
                        <button class="btn btn-success btn-block" @click="register">Cadastrar</button>
                    </div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>

        <script>
            var registerVue = new Vue({
                el: '#registerVue',
                data(){
                    return {
                        name: '',
                        email: '',
                        password: ''
                    }
                },
                methods: {
                    login(){
                        window.location.replace("login.php");
                    },
                    register(){
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

                        $.ajax({
                             method: "POST",
                             url: config.server + "/auth/register",
                             data: data
                        }).done((result) => {
                            if(result.status){
                                swal("Sucesso", "Usuario cadastrado com sucesso, voce será redirecionado para realizar o login.", "success");
                                this.login();
                            }else{
                                swal("Oops", result.msg, "info");
                            }
                        }).fail(() => {
                            swal("Oops", "Verifique o servidor ou administrador do sistema", "info");
                        });

                    }
                }
            })
        </script>
    </body>
</html>
