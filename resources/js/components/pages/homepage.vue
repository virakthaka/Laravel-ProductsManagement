<template>
    <div class="row my-5" v-if="!loading">
        <div class="col-md-12">
            <input type="text" class="form-control col-4 float-left" placeholder="search somethings by title" v-model="key_search" v-on:keyup="searchItems">
            <router-link :to="{name: 'addProduct'}" class="btn btn-secondary float-right mb-3">Add product</router-link>
            <button type="button" class="btn btn-primary float-right mr-2" data-toggle="modal" data-target="#addCategory">Add category</button>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Product ID</th>
                            <th scope="col">ProductImage</th>
                            <th scope="col">ProductName</th>
                            <th scope="col">ProductCategory</th>
                            <th scope="col">ProductQuantity</th>
                            <th scope="col">ProductPrice</th>
                            <th scope="col">Insert By</th>
                            <th scope="col" class=""> Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product,i) in products" :key="i">
                            <th scope="row" class="align-middle">PID{{('000' + product.id).slice(-5)}}</th>
                            <td><img :src="'storage/'+getImages(product.pro_images)[0]" width="60" height="60"></td>
                            <td class="align-middle">{{product.pro_name}}</td>
                            <td class="align-middle">{{product.get_category.category}}</td>
                            <td class="align-middle">{{product.pro_quantity}}</td>
                            <td class="align-middle">{{product.pro_price}}</td>
                            <td class="align-middle">{{product.get_user.name}}</td>
                            <td class="align-middle">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <router-link :to="{name: 'editProduct', params:{id:product.id}}" class="btn btn-secondary">edit</router-link>
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#showDetail" @click="showDetail(i)">show</button>
                                    <button type="button" class="btn btn-secondary" @click="deleteProduct(product.id,i)">delete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

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

            <div v-if="products.length!=0" class="modal fade" id="showDetail" tabindex="-1" role="dialog" aria-labelledby="ModalLabelShowDetail" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelShowDetail">Detail Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">Product title</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" :value="products[ShowIndex].pro_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">Product category</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" :value="products[ShowIndex].get_category.category">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">Product price</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" :value="products[ShowIndex].pro_price">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">Product quantity</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" :value="products[ShowIndex].pro_quantity">
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-4" v-for="(image,i) in getImages(products[ShowIndex].pro_images)" :key="i">
                                    <img :src="'storage/'+image" class="img-fluid rounded img-border">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
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
                products:[],
                ShowIndex:0,
                key_search:"",
                category:"",
                error:{
                    addCategory:"",
                },
            }
        },
        created(){
            this.getData();
        },
        methods:{
            getData(){
                axios.get('/api/v1/showProducts')
                .then(response => {
                        this.products = response.data;
                        this.loading=false;
                    });
            },
            searchItems(){
                this.ShowIndex=0;
                setTimeout(()=>{
                    axios.get('/api/v1/searchItems?key_search='+this.key_search)
                    .then(response => {
                            this.products = response.data;
                            this.loading=false;
                    });
                }, 200);
                                     
                                
            },
            addCategory(type) {
                axios.post('/api/v1/addCategory',{category:this.category})
                    .then(response => {
                        //insert success
                        if (type==0){
                            $("#addCategory").modal('hide')
                        }
                    })
                    .catch(response => {
                        this.error.addCategory = String(response.response.data.errors.category[0]);
                    })
            },
            showDetail(index){
                this.ShowIndex = index;
            },
            deleteProduct(id,index){
                axios.post('/api/v1/deleteProduct',{id:id})
                    .then(response => {
                        //delete success
                        this.products.splice(index,1)
                })
            },
            getImages(src){
                return src.includes(",")?src.split(","):[src];
            }
        }
    }
</script>
<style>
    .img-border{
        border: 1px #ccc solid;
    }
</style>