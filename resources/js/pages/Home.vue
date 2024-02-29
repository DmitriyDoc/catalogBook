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
                    v-on:keydown.enter.prevent = "submitSearch(formRef)"
                />
            </el-form-item>
            <el-form-item>
                <el-button @click="resetSearch(formRef)">Сбросить поиск</el-button>
                <el-button @click="submitSearch(formRef)">Поиск</el-button>
            </el-form-item>
        </el-form>
    </el-row>
    <div class="mb-4">
        <el-pagination
            v-model:current-page="currentPage"
            v-model:page-size="pageSize"
            :page-sizes="[5, 10]"
            :disabled="disabled"
            :background="background"
            layout="sizes, prev, pager, next"
            :total="totalCount"
            @current-change="handleCurrentChange"
            @size-change="handleSizeChange"
        />
    </div>
        <div class="row row-cols-1 row-cols-8 g-4">
            <div class="col-lg-3" v-for="(item, index) in books.data" :key="index">
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
    import { ref, reactive, onMounted, onUpdated } from "vue";
    import { RouterLink } from 'vue-router'
    import { storeToRefs } from 'pinia';
    import { useBooksStore } from "../store/booksStore";

    const booksStore = useBooksStore();
    const { books, currentBook, currentPage, totalCount, paginatorActivity } = storeToRefs( booksStore );
    const formRef = ref();
    const background = ref(true);
    const disabled = ref(paginatorActivity);
    const pageSize = ref(5);
    const dialogTableVisible = ref(false)
    const queryValidateForm = reactive({
        query: '',
    });

    const submitSearch = (formEl) => {
        if (!formEl) return
        formEl.validate((valid) => {
            if (valid) {
                booksStore.updateSearchQuery( queryValidateForm.query );
                booksStore.getBooks();
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
        booksStore.getBooks();
        disabled.value = false;
    }
    const handleCurrentChange = (val) => {
        booksStore.updateCurrentPage(val);
        booksStore.getBooks();
    }
    const handleCurrentBook = (id) => {
        booksStore.getBookById(id);
    }
    const handleSizeChange = (page) => {
        pageSize.value = page;
        booksStore.updatePageSize(page);

    }
    onMounted( async () => {
        booksStore.getBooks();
    });
    // onUpdated( async () => {
    //     booksStore.getBooks();
    // });
</script>

<style scoped>

</style>
