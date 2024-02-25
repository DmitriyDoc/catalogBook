<template>
    <el-aside width="200px">
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <RouterLink class="nav-link" :to="{ name: 'AdminBooks' }">
                    Книги
                </RouterLink>
            </div>
            <div class="container-fluid">
                <RouterLink class="nav-link" :to="{ name: 'AdminWriters' }">
                    Авторы
                </RouterLink>
            </div>
            <div class="container-fluid">
                <RouterLink class="nav-link" :to="{ name: 'AdminCategories' }">
                    Категории
                </RouterLink>
            </div>
        </nav>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">Ваше имя:</div>
                    {{user.name}}
                </div>
             </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">Ваша почта:</div>
                    {{user.email}}
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div style="flex-grow: 1;display: flex; flex-direction: column;">
                    <button @click="logout()" type="button" class="btn btn-danger">Выйти</button>
                </div>
            </li>
        </ul>
    </el-aside>
</template>

<script setup>
    import {ref} from "@vue/reactivity";
    import {useRouter} from "vue-router";

    const user = ref({});
    const router = useRouter();

    user.value = JSON.parse(localStorage.getItem('user'));
    const logout = () => {
        window.localStorage.clear();
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.clear();
        router.push({ path: '/login' });
    };
</script>

<style scoped>

</style>
