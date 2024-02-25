<template>
    <el-row>
        <el-col class="text-center">
            <h2>Все книги в жанре {{currentCategory.title}}</h2>
        </el-col>
        <el-col :span="24" class="mb-4">
            <el-card shadow="always"> {{currentCategory.description}} </el-card>
        </el-col>
        <el-col>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col-lg-3" v-for="(item, index) in currentCategory.books" :key="index">
                    <div class="card h-100">
                        <img :src=item.cover class="card-img-top" :alt=item.title>
                        <div class="card-body">
                            <h5 class="card-title">{{item.title??''}}</h5>
                            <p class="card-text" v-for="(i, k) in item.writers" :key="k"><em>{{i.name??''}}</em></p>
                        </div>
                        <div class="card-footer" >
                            <el-button plain  @click="dialogTableVisible = true,handleCurrentBook(item.id)">Подробнее</el-button>
                        </div>
                    </div>
                </div>
            </div>
        </el-col>
    </el-row>
    <el-dialog v-model="dialogTableVisible" width="800">
        <div class="container text-center">
            <h4>{{currentBook.title}}</h4>
            <div class="row mb-4">
                <div class="col-sm-4">
                    <img :src=currentBook.cover class="img-fluid" :alt=currentBook.title>
                </div>
                <div class="col-sm-8 text-start">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Год издания:</span> <div>{{currentBook.year}}</div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Категория:</span> <p><span v-for="(cat, k) in currentBook.categories" :key="k">{{cat.title}}</span></p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Автор:</span> <p><span v-for="(writer, k) in currentBook.writers" :key="k">{{writer.name}}</span></p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col text-start">
                    <div><h6>Краткое описание книги:</h6></div>
                    <p>{{currentBook.description}}</p>
                </div>
            </div>
        </div>
    </el-dialog>
</template>

<script setup>
    import { ref, onMounted, watch } from "vue";
    import { storeToRefs } from 'pinia';
    import { useCategoriesStore } from "../store/categoriesStore";
    import { useBooksStore } from "../store/booksStore";

    const categoriesStore = useCategoriesStore();
    const booksStore = useBooksStore();
    const { currentCategory, route } = storeToRefs(categoriesStore);
    const { currentBook } = storeToRefs(booksStore);
    const dialogTableVisible = ref(false);

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
