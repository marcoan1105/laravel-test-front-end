<html>
    <head>
        <title>Cadastro de Produtos</title>
    </head>
    <body>
        <?php require_once ("imports.php"); ?>
        <div class="jumbotron" id="loginVue">
            <div class="container">
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4"><h3>Entrar:</h3></div>
                    <div class="col-4"></div>
                </div>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <label for="email">Email:</label>
                        <input type="text" class="form-control" id="email" v-model="email" ref="email" @keyup="keyUpEmail">
                    </div>
                    <div class="col-4"></div>
                </div>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" v-model="password" ref="password" @keyup="keyupPassword">
                    </div>
                    <div class="col-4"></div>
                </div>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-2">
                        <br>
                        <button class="btn btn-primary btn-block" @click="register">Cadastrar</button>
                    </div>
                    <div class="col-2">
                        <br>
                        <button class="btn btn-success btn-block" @click="login">Entrar</button>
                    </div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>

        <script>
            var loginVue = new Vue({
                el: '#loginVue',
                data(){
                    return {
                        email: '',
                        password: ''
                    }
                },
                methods: {
                    register(){
                        window.location.replace("register.php");
                    },
                    index(){
                        window.location.replace("index.php");
                    },
                    login(){

                        if(this.email == ""){
                            swal("Oops", "Digite o seu email","info");
                            return;
                        }

                        if(this.password == ""){
                            swal("Oops", "Digite a sua senha", "info");
                            return;
                        }

                        var data = {
                            email: this.email,
                            password: this.password
                        };

                        $.ajax({
                            method: "POST",
                            url: config.server + "/auth/login",
                            data: data
                        }).done((result) => {
                            if(typeof result.error != "undefined"){
                                swal("Opss", "Usuário ou senha inválida.", "error");
                            }else{
                                if(typeof result.access_token != "undefined"){
                                    localStorage.setItem("access_token", result.access_token);
                                    this.index();
                                }else{
                                    swal("Opss", "Usuário ou senha inválida.", "error");
                                }
                            }
                        }).fail(function(jqXHR, textStatus, msg) {
                            swal("Opss", "Usuário ou senha inválida.", "error");
                        });
                    },
                    keyUpEmail(e){
                        if(e.keyCode == 13) {
                            this.$refs.password.focus();
                        }
                    },
                    keyupPassword(e){
                        if(e.keyCode == 13) {
                            this.login();
                        }
                    }
                }
            })
        </script>

    </body>
</html>
