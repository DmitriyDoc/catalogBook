<template>
    <div>
        <h1>Войти в личный кабинет</h1>
        <form @submit.prevent="login">
            <div>
                <span class="text-danger" v-if="errors.email">{{errors.email[0]}}</span>
                <div class="input-group mb-3">
                    <span class="input-group-text" >E-mail</span>
                    <input type="email" v-model="user.email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>

            </div>
            <div>
                <span class="text-danger" v-if="errors.email">{{errors.password[0]}}</span>
                <div class="input-group mb-3">
                    <span class="input-group-text" >Пароль</span>
                    <input type="password" v-model="user.password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>
            </div>
            <button @click.prevent="login()" class="btn btn-primary" type="submit">Войти</button>
        </form>
    </div>
</template>
<script>
    import axios from "axios";

    export default {
        data() {
            return {
                user: {
                    email: '',
                    password: '',
                },
                errors : {}
            };
        },
        methods: {
            login() {
                try{
                    this.axios.get('/sanctum/csrf-cookie').then(res => {
                        this.axios.post('/api/login', this.user).then(({data}) => {
                            localStorage.setItem('token', data.access_token);
                            localStorage.setItem('user', JSON.stringify(data.user));
                            this.$router.push('/dashboard');
                        }).catch((error) => {
                            this.errors = error.response.data.errors;
                        });
                    })

                } catch (e) {
                    error.value = e;
                    console.log('error',e);
                }
            }
        }
    }
</script>
