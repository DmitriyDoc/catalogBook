import { defineStore } from "pinia";
import axios from 'axios'
import { ref }  from "vue";
import { ElMessage } from "element-plus";
import { reactive } from "@vue/reactivity";

export const useBooksStore = defineStore('BooksStore',() => {
    const token = localStorage.getItem('token');
    const books = ref({});
    const currentBook = ref({});
    const searchQuery = ref('');
    const paginatorActivity = ref(false);
    const totalCount = ref(0);
    const state = ref({
        page: 1,
    });
    const currentPage = ref(state.value.page);
    const initialBook = {
        title: '',
        year: '',
        description: '',
        image: '',
    };
    const book = reactive({ data: initialBook });
    const errorsBook = ref({});
    const config = {
        headers: {
            'content-type': 'multipart/form-data'
        }
    }

    const addBook = () => {
        try {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/books/add', book.data, config)
                    .then(response => {
                        popupMessage(response.data.success);
                        book.data = { initialBook };
                    })
                    .catch(function (error) {
                        errorsBook.value = error.response.data.errors;
                    });
            })
        } catch (e) {
            error.value = e;
            console.log('error', e);
        } finally {}
    }

    const getBooks = async () =>{
        try {
            axios.get('/api/books/'
                +'?page=' + state.value.page
                +'&search=' + searchQuery.value
            ).then((response) => {
                books.value = response.data;
                totalCount.value = response.data.total;
                if (!response.data.data.length){
                    paginatorActivity.value = true;
                }
            });
        } catch (e) {
            error.value = e;
            console.log('error',e);
        } finally {}
    }
    const getBookById = async (id) =>{
        try {
            axios.get('/api/books/show/' + id
            ).then((response) => {
                currentBook.value = response.data;
            });
        } catch (e) {
            error.value = e;
            console.log('error',e);
        } finally {}
    }
    const updateBook = (updatedBook) => {
        try {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/books/update', {
                    title: updatedBook.value.title,
                    year:  updatedBook.value.year,
                    description:  updatedBook.value.description,
                    image:  updatedBook.value.image,
                    cover:  updatedBook.value.cover,
                    id: updatedBook.value.id,
                    }, config)
                    .then(response => {
                        getBooks();
                        popupMessage(response.data.success);
                    })
                    .catch(function (error) {
                        errorsBook.value = error.response.data.errors;
                    });
            })
        } catch (e) {
            error.value = e;
            console.log('error', e);
        } finally {}
    }
    const removeBook = async (id,index) => {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.delete('/api/books/del',{ data: { id: id } })
                .then(response => {
                    books.value.data.splice(index,1);
                })
                .catch(function (error) {
                    errorsBook.value = error.response.data.errors;
                });
        })
    }
    const updateSearchQuery = (q) => {
        searchQuery.value = q;
    }
    const updateCurrentPage = (page) => {
        state.value.page = page;
    }
    const popupMessage = (message) => {
        ElMessage({
            type: 'success',
            message: message,
        });
    }
    return {
        book,
        books,
        errorsBook,
        currentPage,
        totalCount,
        paginatorActivity,
        currentBook,
        updateBook,
        removeBook,
        updateSearchQuery,
        updateCurrentPage,
        getBooks,
        addBook,
        getBookById,
    }
});
