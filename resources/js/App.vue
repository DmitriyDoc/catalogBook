<template>
    <div class="container">
        <el-menu
            :default-active="activeIndex"
            class="el-menu-demo"
            mode="horizontal"
            :ellipsis="false"
        >
            <el-menu-item index="0">
                <a href="/"><img
                    style="width: 50px"
                    :src="logoSrc"
                    alt="Логотип"
                /></a>
            </el-menu-item>
            <div class="flex-grow" />
            <el-menu-item index="1"><router-link :to="{ name: 'Home' }"  class="nav-item nav-link">Главная</router-link></el-menu-item>
            <template v-if="!auth"><el-menu-item index="2-1"><router-link  :to="{ name: 'Login' }" class="nav-item nav-link">Вход</router-link></el-menu-item></template>
            <template v-if="!auth"><el-menu-item index="2-2"><router-link :to="{ name: 'Register' }" class="nav-item nav-link">Регистрация</router-link></el-menu-item></template>
            <template v-if="auth"><el-menu-item index="2-3"><router-link  v-if="auth":to="{ name: 'Dashboard' }"class="nav-item nav-link">Личный кабинет</router-link></el-menu-item></template>
        </el-menu>
        <nav class="nav mb-3">
            <template v-for="(i, k) in categories" :key="k">
                <RouterLink class="nav-link" :to="{ name: 'CategoryPage', params: { slug: i.slug }}">
                    {{i.title}}
                </RouterLink>
            </template>
        </nav>
        <router-view> </router-view>
    </div>
</template>

<script setup>
    import {ref, computed, onMounted, onUpdated} from 'vue'
    import { RouterLink } from 'vue-router'
    import logoSrc from "@/images/logo.jpg";
    import { storeToRefs } from 'pinia';
    import { useCategoriesStore } from "./store/categoriesStore";

    const categoriesStore = useCategoriesStore();
    const { categories } = storeToRefs(categoriesStore);

    const loggedIn = ref(false);
    const activeIndex = ref('1');
    const auth = computed(() => loggedIn.value);

    // const handleSelect = (key, keyPath) => {
    //     console.log(key, keyPath)
    // }
    const checkUser = () => {
        const user = localStorage.getItem('user');
        if (user) {
            loggedIn.value = true;
        }
    }

    onMounted(  () => {
        checkUser();
        categoriesStore.getCategories();
    });

</script>
<style>
    .flex-grow {
        flex-grow: 1;
    }
</style>
