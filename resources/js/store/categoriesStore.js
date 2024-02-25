import { defineStore } from "pinia";
import axios from 'axios'
import { ref }  from "vue";
import { ElMessage } from "element-plus";
import { reactive } from "@vue/reactivity";
import { useRoute } from "vue-router";

export const useCategoriesStore = defineStore('categoriesStore',() => {
    const token = localStorage.getItem('token');
    const route = useRoute();
    const initialCategory = {
        title: '',
        description: '',
    };
    const category = reactive({ data: initialCategory });
    const categories = ref();
    const currentCategory = ref({});
    const errorsCategory = ref({});

    const addCategory = () => {
        try {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/categories/add', category.data)
                    .then(response => {
                        popupMessage(response.data);
                        category.data = { initialCategory };
                    })
                    .catch(function (error) {
                        errorsCategory.value = error.response.data.errors;
                    });
            })
        } catch (e) {
            error.value = e;
            console.log('error', e);
        } finally {}
    }
    const getCategoryBySlug = () => {
        try {
            axios.get('/api/categories/' + route.params.slug
            ).then((response) => {
                currentCategory.value = response.data[0];
            });
        } catch (e) {
            error.value = e;
            console.log('error',e);
        } finally {}
    }
    const getCategories = () => {
        try {
            axios.get('/api/categories/'
            ).then((response) => {
                categories.value = response.data;
            });
        } catch (e) {
            error.value = e;
            console.log('error',e);
        } finally {}
    }

    const getCategoryById = async (id) =>{
        try {
            axios.get('/api/categories/show/' + id
            ).then((response) => {
                currentCategory.value = response.data[0];
            });
        } catch (e) {
            error.value = e;
            console.log('error',e);
        } finally {}
    }
    const updateCategory = (updateCategory) => {
        try {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/categories/update', {
                    title: updateCategory.value.title,
                    description: updateCategory.value.description,
                    id: updateCategory.value.id,
                })
                    .then(response => {
                        getCategories();
                        popupMessage(response.data);
                    })
                    .catch(function (error) {
                        errorsCategory.value = error.response.data.errors;
                    });
            })
        } catch (e) {
            error.value = e;
            console.log('error', e);
        } finally {}
    }
    const removeCategory = async (id,index) => {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.delete('/api/categories/del',{ data: { id: id } })
                .then(response => {
                    categories.value.splice(index,1);
                })
                .catch(function (error) {
                    errorsWriter.value = error.response.data.errors;
                });
        })
    }
    const popupMessage = (data) => {
        if (data.success){
            ElMessage({
                type: 'success',
                message: data.success,
            });
        } else if(data.warning){
            console.log(data.warning);
            ElMessage({
                type: 'info',
                message: data.warning,
            });
        } else {
            ElMessage.error('Ошибка записи.')
        }
    }
    return {
        route,
        category,
        categories,
        errorsCategory,
        currentCategory,
        addCategory,
        getCategoryBySlug,
        getCategories,
        getCategoryById,
        updateCategory,
        removeCategory,
    }
});
