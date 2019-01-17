<template>
    <div class="row justify-content-center my-4">
        <div class="col-8">
            <div v-if="hasNotify" class="alert alert-success float-right" role="alert">
                <strong>Well done!</strong> Data Edit successfully.
            </div>
            <div v-else class="alert">
                <strong style="color: #fff;">hidden</strong>
            </div>
        </div>
        <!--title-->
        <div class="col-12">
            <h1 class="text-center">Edit Product</h1>
        </div>
        <!--input form-->
        <form v-if="!loading" id="insertData" class="col-md-8 mb-5" enctype="multipart/form-data">
            <div class="form-group">
                <label for="_title">Product title</label>
                <input type="text" class="form-control" id="_title" name="title" placeholder="title" v-model="product.pro_name">
                <small class="form-text text-danger float-right">{{error.title}}</small>
            </div>
            <div class="form-group">
                <label for="_price">Product Price</label>
                <input type="text" class="form-control" id="_price" name="price" v-on:keypress="isNumber(event)" placeholder="price" v-model="product.pro_price">
                <small class="form-text text-danger float-right">{{error.price}}</small>
            </div>
            <div class="form-group">
                <label for="_quantity">Product Quantity</label>
                <input type="text" class="form-control" id="_quantity" name="quantity" v-on:keypress="isNumber(event)" placeholder="quantity" v-model="product.pro_quantity">
                <small class="form-text text-danger float-right">{{error.quantity}}</small>
            </div>
            <div class="form-group">
                <label for="_category">Product Category</label>
                <div class="row">
                    <div class="col-9">
                        <select class="form-control " id="_category" name="category" v-model="product.get_category.id">
                            <option value="" hidden>choose category</option>
                            <option v-for="cate in categories" :value="cate.id" :key="cate.id">{{cate.category}}</option>
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
                    <div class="col-4" v-for="(image,i) in product.pro_images.split(',')" :key="i">
                        <img :src="'/storage/'+image" class="img-fluid rounded img-border">
                    </div>
                </div>
                <small class="form-text text-muted">You can insert multiple image of products</small>
            </div>
            <button class="btn btn-primary btn-lg btn-block mt-5" @click="submitData">Edit Product</button>
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
                product:'',
                categories:[],
                category:"",
                error:{
                    addCategory:"",
                    title:"",
                    category:"",
                    quantity:"",
                    price:"",
                },
            }
        },
        created(){
            this.getProduct(this.$route.params.id);
        },
        methods:{
            getProduct(id){
                axios.get('/api/v1/getPageEditProduct/'+id)
                    .then(response => {
                        this.product = response.data.product;
                        this.categories = response.data.categories;
                        this.loading=false;
                    });
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
                formData.append( 'id', this.product.id);

                axios.post('/api/v1/editProduct',formData)
                    .then(response => {
                        // insert success
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
        }
    }
</script>
<style>
    .img-border{
        border: 1px #ccc solid;
    }
</style>