import { defineStore } from "pinia";
import axios from 'axios'
import { ref }  from "vue";
import { ElMessage } from "element-plus";
import { reactive } from "@vue/reactivity";

export const useWritersStore = defineStore('writersStore',() => {
    const token = localStorage.getItem('token');
    const writers = ref({});
    const currentWriter = ref({});
    const totalCount = ref(0);
    const paginatorActivity = ref(false);
    const state = ref({
        page: 1,
    });
    const currentPage = ref(state.value.page);
    const initialWriter = {
        name: '',
        country: '',
        comment: '',
    };
    const writer = reactive({ data: initialWriter });
    const errorsWriter = ref({});

    const addWriter = () => {
        try {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/writers/add', writer.data)
                    .then(response => {
                        popupMessage(response.data.success);
                        writer.data = { initialWriter };
                    })
                    .catch(function (error) {
                        errorsWriter.value = error.response.data.errors;
                    });
            })
        } catch (e) {
            error.value = e;
            console.log('error', e);
        } finally {}
    }
    const getWriters = async () =>{
        try {
            axios.get('/api/writers/'
                +'?page=' + state.value.page
            ).then((response) => {
                writers.value = response.data;
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
    const getWriterById = async (id) =>{
        try {
            axios.get('/api/writers/show/' + id
            ).then((response) => {
                currentWriter.value = response.data;
            });
        } catch (e) {
            error.value = e;
            console.log('error',e);
        } finally {}
    }
    const updateWriter = (updatedWriter) => {
        try {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/writers/update', {
                    name: updatedWriter.value.name,
                    country: updatedWriter.value.country,
                    comment: updatedWriter.value.comment,
                    id: updatedWriter.value.id,
                })
                    .then(response => {
                        getWriters();
                        popupMessage(response.data.success);
                    })
                    .catch(function (error) {
                        errorsWriter.value = error.response.data.errors;
                    });
            })
        } catch (e) {
            error.value = e;
            console.log('error', e);
        } finally {}
    }
    const removeWriter = async (id,index) => {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.delete('/api/writers/del',{ data: { id: id } })
                .then(response => {
                    writers.value.data.splice(index,1);
                })
                .catch(function (error) {
                    errorsWriter.value = error.response.data.errors;
                });
        })
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
        writer,
        writers,
        currentWriter,
        errorsWriter,
        currentPage,
        totalCount,
        paginatorActivity,
        addWriter,
        getWriters,
        getWriterById,
        updateWriter,
        removeWriter,
        updateCurrentPage,
    }
});
