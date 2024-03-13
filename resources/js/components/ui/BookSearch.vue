<template>
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
</template>

<script setup>
    import {reactive, ref} from "vue";

    const formRef = ref();
    const queryValidateForm = reactive({
        query: '',
    });
    const props = defineProps({
        form: Object,
        store: Object,
    });
    const submitSearch = (formEl) => {
        if (!formEl) return
        formEl.validate((valid) => {
            if (valid) {
                props.store.updateSearchQuery( queryValidateForm.query );
                props.store.getItems();
            } else {
                console.log('error submit!')
                return false
            }
        })
    };
    const resetSearch = (formEl) => {
        if (!formEl) return
        formEl.resetFields();
        props.store.updateSearchQuery( '' );
        props.store.getItems();
    };
</script>

<style scoped>

</style>
