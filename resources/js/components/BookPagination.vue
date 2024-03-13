<template>
    <div class="mb-4">
        <el-pagination
            v-on:current-page="currentPage"
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

    const background = ref(true);
    const pageSize = ref(5);
    const props = defineProps({
        store: Object,
        currentPage: Number,
        paginatorActivity: Boolean,
        totalCount: Number
    })


    const handleCurrentChange = (val) => {
        props.store.updateCurrentPage(val);
        props.store.getItems();
    }
    const handleSizeChange = (page) => {
        pageSize.value = page;
        props.store.updatePageSize(page);
        props.store.getItems();
    }
</script>

<style scoped>

</style>
