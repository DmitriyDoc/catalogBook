<template>
    <el-row>
        <el-col class="text-center">
            <h2>Все книги в жанре {{currentCategory.title}}</h2>
        </el-col>
        <el-col :span="24" class="mb-4">
            <el-card shadow="always"> {{currentCategory.description}} </el-card>
        </el-col>
        <el-col>
            <BookSingle :books=currentCategory.books :currentBook=currentBook :handleCurrentBook="handleCurrentBook"/>
        </el-col>
    </el-row>
</template>

<script setup>
    import BookSingle from "../components/BookSingle.vue";
    import { ref, onMounted, watch } from "vue";
    import { storeToRefs } from 'pinia';
    import { useCategoriesStore } from "../store/categoriesStore";
    import { useBooksStore } from "../store/booksStore";

    const categoriesStore = useCategoriesStore();
    const booksStore = useBooksStore();
    const { currentCategory, route } = storeToRefs(categoriesStore);
    const { currentBook } = storeToRefs(booksStore);

    watch(() => route,  categoriesStore.getCategoryBySlug,{deep: true, immediate: true,});

    const handleCurrentBook = (id) => {
        booksStore.getBookById(id);
    }
    onMounted( async () => {
        categoriesStore.getCategoryBySlug();
    });
</script>

<style scoped>

</style>
