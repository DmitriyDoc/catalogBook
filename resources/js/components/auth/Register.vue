<template>
    <div>
        <h1>Регистрация</h1>
        <form @submit.prevent="register">
            <div>
                <span class="text-danger" v-if="errors.name">{{errors.name[0]}}</span>
                <div class="input-group mb-3">
                    <span class="input-group-text" >Имя</span>
                    <input type="text" v-model="user.name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>
            </div>
            <div>
                <span class="text-danger" v-if="errors.email">{{errors.email[0]}}</span>
                <div class="input-group mb-3">
                    <span class="input-group-text" >E-mail</span>
                    <input type="email" v-model="user.email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>
            </div>
            <div>
                <span class="text-danger" v-if="errors.password">{{errors.password[0]}}</span>
                <div class="input-group mb-3">
                    <span class="input-group-text" >Пароль</span>
                    <input type="password" v-model="user.password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>
            </div>
            <div>
                <span class="text-danger" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                <div class="input-group mb-3">
                    <span class="input-group-text" >Повторите пароль</span>
                    <input type="password" v-model="user.password_confirmation" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                </div>
            </div>
            <button @click.prevent="register()" class="btn btn-primary" type="submit">Зарегистрироваться</button>
        </form>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                user: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                errors : {}
            };
        },
        methods: {
            register() {
                try{
                    this.axios.get('/sanctum/csrf-cookie').then(res => {
                        this.axios.post('/api/register', this.user)
                            .then(({data}) => {
                                localStorage.setItem('token', data.access_token);
                                localStorage.setItem('user', JSON.stringify(data.user));
                                this.$router.push('/dashboard');
                            })
                            .catch((error) => {
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
