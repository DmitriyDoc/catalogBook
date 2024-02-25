<template>
    <div class="common-layout">
        <h1 class="text-center">Кабинет администратора</h1>
        <el-container>
            <AsideAdmin/>
            <el-main>
                <div class="row">
                    <el-tabs :tab-position="tabPosition" class="demo-tabs">
                        <el-tab-pane label="Добавить книгу">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form @submit.prevent="submit" enctype="multipart/form-data">
                                            <span class="text-danger" v-if="errorsBook.title">{{errorsBook.title[0]}}</span>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" >Название</span>
                                                <input type="text" v-model="book.data.title" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                            <span class="text-danger" v-if="errorsBook.year">{{errorsBook.year[0]}}</span>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" >Год издания</span>
                                                <input type="text" v-model="book.data.year" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                            <span class="text-danger" v-if="errorsBook.description">{{errorsBook.description[0]}}</span>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" >Описание</span>
                                                <textarea type="text" v-model="book.data.description" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></textarea>
                                            </div>
                                            <span class="text-danger" v-if="errorsBook.image">{{errorsBook.image[0]}}</span>
                                            <div class="input-group mb-3">
                                                <input type="file" @input="book.data.image = $event.target.files[0]" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                            <button @click="booksStore.addBook()" class="btn btn-primary" type="submit">Добавить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="Добавить Автора">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form @submit.prevent="submit">
                                            <span class="text-danger" v-if="errorsWriter.name">{{errorsWriter.name[0]}}</span>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" >Автор</span>
                                                <input type="text" v-model="writer.data.name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                            <span class="text-danger" v-if="errorsWriter.country">{{errorsWriter.country[0]}}</span>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" >Страна рождения</span>
                                                <input type="text" v-model="writer.data.country" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                            <span class="text-danger" v-if="errorsWriter.comment">{{errorsWriter.comment[0]}}</span>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" >Комментарий</span>
                                                <input type="text" v-model="writer.data.comment" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                            <button @click="writersStore.addWriter()" class="btn btn-primary" type="submit">Добавить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="Добавить Категорию">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form @submit.prevent="submit">
                                            <span class="text-danger" v-if="errorsCategory.title">{{errorsCategory.title[0]}}</span>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" >Название</span>
                                                <input type="text" v-model="category.data.title" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                            <span class="text-danger" v-if="errorsCategory.description">{{errorsCategory.description[0]}}</span>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" >Описание</span>
                                                <textarea type="text" v-model="category.data.description" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></textarea>
                                            </div>
                                            <button @click="categoriesStore.addCategory()" class="btn btn-primary" type="submit">Добавить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </el-main>
        </el-container>
    </div>
</template>

<script setup>
    import AsideAdmin from "./AsideAdmin.vue";
    import { ref } from "@vue/reactivity";
    import { useRouter } from "vue-router";
    import { storeToRefs } from 'pinia';
    import { useWritersStore } from "../store/writersStore";
    import { useBooksStore } from "../store/booksStore";
    import { useCategoriesStore } from "../store/categoriesStore";

    const writersStore = useWritersStore();
    const booksStore = useBooksStore();
    const categoriesStore = useCategoriesStore();

    const { writer, errorsWriter } = storeToRefs(writersStore);
    const { book, errorsBook } = storeToRefs(booksStore);
    const { category, errorsCategory } = storeToRefs(categoriesStore);



    const tabPosition = ref('top')


</script>
<style>
    .demo-tabs > .el-tabs__content {
        padding: 32px;
        color: #6b778c;
        font-size: 32px;
        font-weight: 600;
    }

    .el-tabs--right .el-tabs__content,
    .el-tabs--left .el-tabs__content {
        height: 100%;
    }
</style>
