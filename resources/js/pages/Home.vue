<template>
    <h1>Каталог</h1>

    <el-row>
        <el-form
            ref="formRef"
            :model="queryValidateForm"
            class="demo-ruleForm"
            style="width: 100%"
        >
            <el-form-item prop="query" :rules="[{ required: true, message: 'Нужно ввести запрос'}]">
                <el-input
                    v-model.query="queryValidateForm.query"
                    type="text"
                    autocomplete="off"
                    placeholder="Поиск по автору и названию книги ..."
                    v-on:keydown.enter.prevent = "submitSearch( formRef )"
                />
            </el-form-item>
            <el-form-item>
                <el-button @click="resetSearch( formRef )">Сбросить поиск</el-button>
                <el-button @click="submitSearch( formRef )">Поиск</el-button>
            </el-form-item>
        </el-form>
    </el-row>

    <BookPagination :store="booksStore" :currentPage="currentPage" :paginatorActivity="paginatorActivity" :totalCount="totalCount"/>

    <BookList :books=books.data :currentBook=currentBook :handleCurrentBook="handleCurrentBook"/>
</template>

<script setup>
    import BookList from "../components/BookSingle.vue";
    import BookPagination from "../components/BookPagination.vue";
    import { ref, reactive, onMounted } from "vue";
    import { storeToRefs } from 'pinia';
    import { useBooksStore } from "../store/booksStore";

    const booksStore = useBooksStore();
    const { books, currentBook, currentPage, totalCount, paginatorActivity } = storeToRefs( booksStore );
    const formRef = ref();
    const queryValidateForm = reactive({
        query: '',
    });

    const submitSearch = (formEl) => {
        if (!formEl) return
        formEl.validate((valid) => {
            if (valid) {
                booksStore.updateSearchQuery( queryValidateForm.query );
                booksStore.getItems();
            } else {
                console.log('error submit!')
                return false
            }
        })
    }
    const resetSearch = (formEl) => {
        if (!formEl) return
        formEl.resetFields();
        booksStore.updateSearchQuery( '' );
        booksStore.getItems();
    }
    const handleCurrentBook = (id) => {
        booksStore.getBookById(id);
    }

    onMounted( async () => {
        booksStore.getItems();
    });

</script>

<style scoped>

</style>
