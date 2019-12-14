<?php require_once ("header.php"); ?>
<div class="jumbotron" id="vueColors">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <label for="description">Descrição:</label>
                <input type="text" name="description" id="description" class="form-control" v-model="description">
            </div>
            <div class="col-10"></div>
            <div class="col-2">
                <br>
                <button class="btn btn-primary btn-block" @click="save">Salvar</button>
            </div>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(color, i) in colorsList">
                    <td>{{ color.id }}</td>
                    <td>{{ color.description }}</td>
                    <td><button class="btn btn-primary" @click="edit(i)">Editar</button></td>
                    <td><button class="btn btn-danger" @click="deleteColor(i)">Excluir</button></td>

                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    var vueColors = new Vue({
        el: '#vueColors',
        data() {
            return {
                description: '',
                id: '',
                colorsList: []
            }
        },
        methods: {
            save(){
                if(this.description == ""){
                    swal("Oops", "Digite a descrição da cor", "info");
                    return;
                }

                var method = "POST";
                var url = config.server + "/color";

                if(this.id != ""){
                    method = "PUT";
                    url = config.server + "/color/" + this.id;
                }

                var data = {
                    id: this.id,
                    description: this.description
                };

                $.ajax({
                    method: method,
                    url: url,
                    data: data,
                    headers: config.headers
                }).done((result) => {
                    if(result.status){
                        this.list();
                        this.clear();
                        swal("Sucesso", "Cor salva com sucesso.", "success");
                    }else{
                        swal("Oops", result.msg, "info");
                    }
                });
            },
            list(){
                $.ajax({
                    method: "GET",
                    url: config.server + "/colors",
                    headers: config.headers
                }).done((result) => {
                    if(result.status){
                        this.colorsList = result.data;
                    }
                });
            },
            edit(i){
                this.description = this.colorsList[i].description;
                this.id = this.colorsList[i].id;
            },
            clear(){
                this.description = "";
                this.id = "";
            },
            deleteColor(i){
                var id = this.colorsList[i].id;

                $.ajax({
                    method: "DELETE",
                    url: config.server + "/color/"+id,
                    headers: config.headers
                }).done((result) => {
                    if(result.status){
                        this.list();
                        swal("Sucesso", "Cor deletada com sucesso.", "success");
                    }else{
                        swal("Oops", result.msg, "info");
                    }
                });
            }
        },
        mounted(){
            this.list();
        }
    });
</script>
<?php require_once ("footer.php"); ?>
