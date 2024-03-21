<template>
    <div class="common-layout">
        <h1 class="text-center">Редактирование книг</h1>
        <el-container>

            <AsideAdmin/>

            <el-main>
                <div class="row">

                    <BookPagination :store="booksStore" :currentPage="currentPage" :paginatorActivity="paginatorActivity" :totalCount="totalCount"/>

                    <el-table :data="books.data" style="width: 100%">
                        <el-table-column label="Индекс" type="index" width="80"/>
                        <el-table-column prop="cover" label="Обложка" width="90" >
                            <template v-slot:default="scope">
                                <el-image :src="scope.row.cover" />
                            </template>
                        </el-table-column>
                        <el-table-column prop="id" label="Id" width="80" />
                        <el-table-column prop="title" label="Название книги" width="800" />
                        <el-table-column prop="id" fixed="right" label="Операции" width="120">
                            <template v-slot:default="scope">
                                <el-button link type="primary" size="small" @click="dialogTableVisible = true,handleEdit(scope.row.id)">Изменить</el-button>
                                <el-button link type="danger" @click="handleRemove(scope.row.id,scope.$index)" :icon="Delete"  />
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-dialog v-model="dialogTableVisible" width="800">
                        <div class="container text-center">
                            <h4><strong>Редактировать книгу:</strong></h4>
                                <form @submit.prevent="submit" enctype="multipart/form-data" class="text-center">
                                    <span class="text-danger" v-if="errorsBook.title">{{errorsBook.title[0]}}</span>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Год издания</span>
                                        <input type="text" v-model="currentBook.title" class="form-control"  aria-describedby="inputGroup-sizing-default">
                                    </div>
                                    <span class="text-danger" v-if="errorsBook.title">{{errorsBook.title[0]}}</span>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Год издания</span>
                                        <input type="text" v-model="currentBook.year" class="form-control"  aria-describedby="inputGroup-sizing-default">
                                    </div>
                                    <span class="text-danger" v-if="errorsBook.description">{{errorsBook.description[0]}}</span>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Описание</span>
                                        <textarea type="text" v-model="currentBook.description" class="form-control" aria-describedby="inputGroup-sizing-default"></textarea>
                                    </div>
                                    <span class="text-danger" v-if="errorsBook.image">{{errorsBook.image[0]}}</span>
                                    <div class="input-group mb-3">
                                        <input type="file" @input="currentBook.image = $event.target.files[0]" class="form-control" aria-describedby="inputGroup-sizing-default">
                                    </div>
                                    <button @click="handleUpdate()" class="btn btn-primary" type="submit">Обновить книгу</button>
                                </form>
                        </div>
                    </el-dialog>
                </div>
            </el-main>
        </el-container>
    </div>
</template>

<script setup>
    import AsideAdmin from "./AsideAdmin.vue";
    import BookPagination from "./ui/BookPagination.vue";
    import { Delete } from '@element-plus/icons-vue';
    import { ref } from "@vue/reactivity";

    import { storeToRefs } from 'pinia';
    import { useBooksStore } from "../store/booksStore";
    import { onMounted} from "vue";
    import { ElMessage } from "element-plus";

    const booksStore = useBooksStore();
    const { books, currentBook, errorsBook, currentPage, paginatorActivity, totalCount } = storeToRefs( booksStore );
    const dialogTableVisible = ref(false);

    const handleRemove = (id,index) => {
        popupWarning(id,index)
    }
    const handleUpdate = () => {
        booksStore.updateBook(currentBook);
        dialogTableVisible.value = false;
    }
    const handleEdit = (id) => {
        booksStore.getBookById(id);
    }

    const popupWarning = (id,index) => {
        ElMessageBox.confirm(`Вы действительно хотите удалить книгу навсегда? ID ${id}`, 'ПРЕДУПРЕЖДЕНИЕ', {
            confirmButtonText: 'OK',
            cancelButtonText: 'Отмена',
            type: 'warning',
        }).then(() => {
            booksStore.removeBook(id,index);
            ElMessage({
                type: 'success',
                message: 'Удаление прошло успешно!',
            })
        }).catch(() => {
            ElMessage({
                type: 'info',
                message: 'Удаление отменено',
            })
        })
    }
    onMounted( async () => {
        booksStore.getItems();
    });
</script>
<style>
</style>
