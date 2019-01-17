<template xmlns:v-on="http://www.w3.org/1999/xhtml">
    <div class="row justify-content-center my-4">
        <div class="col-8">
            <div v-if="hasNotify" class="alert alert-success float-right" role="alert">
                <strong>Well done!</strong> Data insert successfully.
            </div>
            <div v-else class="alert">
                <strong style="color: #fff;">hidden</strong>
            </div>
        </div>
        <!--title-->
        <div class="col-12">
                <h1 class="text-center">Add Product</h1>
        </div>
        <!--input form-->
        <form id="insertData" class="col-md-8 mb-5" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="_title">Product title</label>
                    <input type="text" class="form-control" id="_title" name="title" placeholder="title" v-model="product.title">
                    <small class="form-text text-danger float-right">{{error.title}}</small>
                </div>
                <div class="form-group">
                    <label for="_price">Product Price</label>
                    <input type="text" class="form-control" id="_price" name="price" v-on:keypress="isNumber(event)" placeholder="price" v-model="product.price">
                    <small class="form-text text-danger float-right">{{error.price}}</small>
                </div>
                <div class="form-group">
                    <label for="_quantity">Product Quantity</label>
                    <input type="text" class="form-control" id="_quantity" name="quantity" v-on:keypress="isNumber(event)" placeholder="quantity" v-model="product.quantity">
                    <small class="form-text text-danger float-right">{{error.quantity}}</small>
                </div>
                <div class="form-group">
                    <label for="_category">Product Category</label>
                    <div class="row">
                        <div class="col-9">
                            <select class="form-control " id="_category" name="category" v-model="product.category">
                                    <option value="" hidden>choose category</option>
                                <option v-for="cate in categories" :value="cate.id">{{cate.category}}</option>
                            </select>
                            <small class="form-text text-muted float-left">You can add more categories.</small>
                            <small class="form-text text-danger float-right">{{error.category}}</small>
                        </div>
                        <div class="col-3">
                            <button type="button" class="btn btn-primary px-4 float-right" data-toggle="modal" data-target="#addCategory">
                                Add category
                            </button>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <label for="_category">Image Product</label>
                    <div class="row">
                        <div class="col-4 my-2" v-for="img in imageBlob">
                            <img :src="img" class="img-fluid rounded img-border">
                        </div>
                        <div class="col-4 my-2">
                            <label for="_img_1">
                                <img src="http://icons.iconarchive.com/icons/iconsmind/outline/512/Add-icon.png" class="img-fluid rounded img-border">
                            </label>
                        </div>
                        <input id="_img_1" accept=".png, .jpeg, .bmp, .jpg" type="file" multiple @change="checkFile" hidden>
                    </div>
                    <small class="form-text text-muted">You can insert multiple image of products</small>
                </div>
                <button class="btn btn-primary btn-lg btn-block mt-5" @click="submitData">Add Product</button>
            </form>

        <!-- Modal -->
        <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <input type="email" class="form-control" id="category" placeholder="category name" v-model="category">
                                <small class="form-text text-danger float-right">{{error.addCategory}}</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="addCategory(0)">Add & Close</button>
                        <button type="button" class="btn btn-primary" @click="addCategory(1)">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
            return{
                loading:true,
                hasNotify:false,
                product:{
                    title:'',
                    category:'',
                    quantity:'',
                    price:'',
                    images:[],
                },
                categories:[],
                category:"",
                error:{
                    addCategory:"",
                    title:"",
                    category:"",
                    quantity:"",
                    price:"",
                },
                imageBlob:[],
            }
        },
        created(){
            this.getData();
        },
        methods: {
            getData(){
                axios.get('/api/v1/getCategories')
                .then(response => {
                        this.categories = response.data;
                        this.loading=false;
                    });
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            checkFile: function(evt){
                let objFiles = evt.target.files;
                let tmpImage = [];
                for(let i=0; i < objFiles.length;i++){
                    if 	(!objFiles[i].name.match(/.(jpg|png|bmp|jpeg)$/i)) {
                        alert('Opp! System does not support this file');
                    }else{
                        tmpImage[i] = URL.createObjectURL(objFiles[i]);
                        this.product.images.push(objFiles[i]);
                    }
                }
                this.imageBlob.push.apply(this.imageBlob,tmpImage);
            },
            addCategory(type) {
                axios.post('/api/v1/addCategory',{category:this.category})
                    .then(response => {
                        //insert success
                        this.categories.push(response.data);
                        this.category='';
                        if (type==0){
                            $("#addCategory").modal('hide')
                        }
                    })
                    .catch(response => {
                        this.error.addCategory = String(response.response.data.errors.category[0]);
                    })
            },
            submitData(e){
                e.preventDefault();

                let form = document.forms.namedItem('insertData');
                let formData = new FormData(form);
                for(let i=0; i<this.product.images.length; i++){
                    formData.append( 'images[]', this.product.images[i]);
                }
                axios.post('/api/v1/insertProduct',formData)
                .then(response => {
                        // insert success
                        this.resetData();
                        this.hasNotify=true;
                        setTimeout(()=>{this.hasNotify=false}, 2000);
                    })
                    .catch(response => {
                        let error = response.response.data.errors;
                        this.error.title = String(error.title[0]);
                        this.error.price = String(error.price[0]);
                        this.error.quantity = String(error.quantity[0]);
                        this.error.category = String(error.category[0]);
                    })
            },
            resetData(){
                this.error.title = '';
                this.error.price = '';
                this.error.quantity = '';
                this.error.category = '';
                this.error.addCategory = '';

                this.imageBlob = [];
                this.product.title = '';
                this.product.category = '';
                this.product.quantity = '';
                this.product.price = '';
                this.product.images = [];
            }
        }
    }
</script>
<style>
.img-border{
    border: 1px #ccc solid;
}
</style>