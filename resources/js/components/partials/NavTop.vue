<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <router-link :to="{name: 'homepage'}" class="navbar-brand">ProductsManagement </router-link> 
            <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">
                <span class="navbar-toggler-icon"></span>
            </button> 
            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <ul v-if="!loading" class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="#" class="nav-link">{{auth.email}}</a></li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="localStorage.clear();event.preventDefault(); document.getElementById('doLogout').submit();">
                        Logout
                        <form id="doLogout" action="/logout" method="post" hidden>
                            <input type="hidden" name="_token" :value="token">
                        </form>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        mounted() {
            console.log('top mounted.')
        },
        created(){
            this.getData();
        },
        data(){
            return{
                loading:true,
                auth:'',
                token:$('meta[name="csrf-token"]').attr('content'),
            }
        },
        methods:{
            getData(){
                axios.get('/api/v1/getAuthorized')
                .then(response => {
                        this.auth = response.data;
                        this.loading=false;
                    });
            }
        }
    }
</script>