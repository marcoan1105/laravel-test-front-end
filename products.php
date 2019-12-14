<?php require_once ("header.php"); ?>
<div class="jumbotron" id="vueProducts">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <label for="description">Descrição:</label>
                <input type="text" name="description" id="description" class="form-control" v-model="description">
            </div>
            <div class="col-2">
                <label for="description">Preço:</label>
                <input type="text" name="description" id="price" class="form-control" v-model="price">
            </div>
            <div class="col-10">
                <div class="row">
                    <div class="col-2" v-for="(color, index) in colorsList">
                        <input type="checkbox" v-model="color.check" name="colors" v-bind:id="'colors'+color.id" v-bind:value="color.id" style="margin-top: 45px;"> <label v-bind:for="'colors'+color.id">{{ color.description }}</label>
                    </div>
                </div>
            </div>
            <div class="col-8"></div>
            <div class="col-2">
                <br>
                <button class="btn btn-warning btn-block" @click="clear">Cancelar</button>
            </div>
            <div class="col-2">
                <br>
                <button class="btn btn-primary btn-block" @click="save()">Salvar</button>
            </div>
        </div>
        <br>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(product, i) in productsList">
                    <td>{{ product.id }}</td>
                    <td>{{ product.description }}</td>
                    <td>{{ product.price.replace(".", ",") }}</td>
                    <td><button class="btn btn-primary" @click="editProduct(i)">Editar</button></td>
                    <td><button class="btn btn-danger" @click="deleteProduct(i)">Deletar</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
    <script>
        var vueProducts = new Vue({
            el: "#vueProducts",
            data(){
                return {
                    colorsList: [],
                    productsList: [],
                    description: '',
                    price: '',
                    id: ''
                }
            },
            methods: {
                colors(){
                    $.ajax({
                        method: "GET",
                        url: config.server + "/colors",
                        headers: config.headers
                    }).done((result) => {
                        if(result.status){
                            for (let i = 0; i < result.data.length; i++){
                                result.data[i].check = false;
                            }
                            this.colorsList = result.data;
                        }
                    });
                },
                clear(){
                    this.description = "";
                    this.price = "";
                    this.id = "";
                    for(let i = 0; i < this.colorsList.length; i++){
                        this.colorsList[i].check = false;
                    }
                },
                save(){
                    if(this.description == ""){
                        swal("Oops", "Digite a descrição do Produto", "info");
                        return;
                    }

                    if(this.price == ""){
                        swal("Oops", "Digite o preço do produto", "info");
                        return;
                    }

                    var colors = [];

                    for(let i = 0; i < this.colorsList.length; i++){
                        if(this.colorsList[i].check){
                            colors.push(this.colorsList[i].id);
                        }
                    }

                    if(colors.length == 0){
                        swal("Oops", "Selecione ao menos uma cor", "info");
                        return;
                    }

                    var method = "POST";
                    var url = config.server + "/product";

                    if(this.id != ""){
                        method = "PUT";
                        url = config.server + "/product/" + this.id;
                    }

                    var data = {
                        id: this.id,
                        description: this.description,
                        price: this.price.replace(",", "."),
                        colors: colors
                    };

                    $.ajax({
                        method: method,
                        url: url,
                        data: data,
                        headers: config.headers
                    }).done((result) => {
                        if(result.status){
                            this.listProducts();
                            this.clear();
                            swal("Sucesso", "Produto salvo com sucesso.", "success");
                        }else{
                            swal("Oops", result.msg, "info");
                        }
                    });
                },
                listProducts(){
                    $.ajax({
                        method: "GET",
                        url: config.server + "/products",
                        headers: config.headers
                    }).done((result) => {
                        if(result.status){
                            this.productsList = result.data;
                        }
                    });
                },
                editProduct(i){
                    this.clear();
                    $.ajax({
                        method: "GET",
                        url: config.server + "/product/"+this.productsList[i].id,
                        headers: config.headers
                    }).done((result) => {
                        if(result.status){
                            this.description = result.data.description;
                            this.price = result.data.price.replace(".", ",");
                            this.id = result.data.id;

                            for(let i = 0; i < this.colorsList.length; i++){
                                for(let j = 0; j < result.data.colors.length; j++){
                                    if(this.colorsList[i].id == result.data.colors[j].id){
                                        this.colorsList[i].check = true;
                                    }
                                }
                            }
                        }else{
                            swal("Oops", result.msg, "info");
                        }
                    });
                },
                deleteProduct(i){
                    $.ajax({
                        method: "DELETE",
                        url: config.server + "/product/"+this.productsList[i].id,
                        headers: config.headers
                    }).done((result) => {
                        if(result.status){
                            this.listProducts();
                            swal("Sucesso", "Produto excluido com sucesso", "success");
                        }else{
                            swal("Oops", result.msg, "info");
                        }
                    });
                },
            },
            mounted(){
                this.colors();
                this.listProducts();
            },
            watch: {
                price(nV, oV){
                    let value = nV.replace(/\D/g, "");
                    value = value.replace(/(\d)(\d{2})$/, "$1,$2");
                    this.price = value;
                }
            }
        });
    </script>
<?php require_once ("footer.php"); ?>