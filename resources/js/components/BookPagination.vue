<template>

    <div class="mb-4">
        <el-pagination
            v-model:current-page="currentPage"
            v-model:page-size="pageSize"
            :page-sizes="[5, 10]"
            :disabled="paginatorActivity"
            :background="background"
            layout="sizes, prev, pager, next"
            :total="totalCount"
            @current-change="handleCurrentChange"
            @size-change="handleSizeChange"
        />
    </div>
</template>

<script setup>
    import {ref} from "vue";
    import { useBooksStore } from "../store/booksStore";
    import { storeToRefs } from 'pinia';

    const booksStore = useBooksStore();
    const { currentPage, totalCount, paginatorActivity } = storeToRefs( booksStore );
    const background = ref(true);
    const pageSize = ref(5);

    const handleCurrentChange = (val) => {
        booksStore.updateCurrentPage(val);
        booksStore.getBooks();
    }
    const handleSizeChange = (page) => {
        pageSize.value = page;
        booksStore.updatePageSize(page);
    }
</script>

<style scoped>

</style>
