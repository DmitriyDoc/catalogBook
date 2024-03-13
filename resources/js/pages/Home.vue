<template>
    <h1>Каталог</h1>
    <BookSearch :store="booksStore"/>
    <BookPagination :store="booksStore" :currentPage="currentPage" :paginatorActivity="paginatorActivity" :totalCount="totalCount"/>
    <BookList :books=books.data :currentBook=currentBook :handleCurrentBook="handleCurrentBook"/>
</template>

<script setup>
    import BookList from "../components/ui/BookSingle.vue";
    import BookPagination from "../components/ui/BookPagination.vue";
    import BookSearch from "../components/ui/BookSearch.vue";
    import { ref, reactive, onMounted } from "vue";
    import { storeToRefs } from 'pinia';
    import { useBooksStore } from "../store/booksStore";

    const booksStore = useBooksStore();
    const { books, currentBook, currentPage, totalCount, paginatorActivity } = storeToRefs( booksStore );
    const handleCurrentBook = (id) => {
        booksStore.getBookById(id);
    };
    onMounted( async () => {
        booksStore.getItems();
    });

</script>

<style scoped>

</style>
