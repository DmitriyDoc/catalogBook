<template>
    <div class="common-layout">
        <h1 class="text-center">Редактирование категорий</h1>
        <el-container>
            <AsideAdmin/>
            <el-main>
                <div class="row">
                    <el-table :data="categories" style="width: 100%">
                        <el-table-column label="Индекс" type="index" width="80"/>
                        <el-table-column prop="id" label="Id" width="80" />
                        <el-table-column prop="title" label="Название категории" width="800" />
                        <el-table-column prop="id" fixed="right" label="Операции" width="120">
                            <template v-slot:default="scope">
                                <el-button link type="primary" size="small" @click="dialogTableVisible = true,handleEdit(scope.row.id)">Изменить</el-button>
                                <el-button link type="danger" @click="handleRemove(scope.row.id,scope.$index)" :icon="Delete"  />
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-dialog v-model="dialogTableVisible" width="800">
                        <div class="container text-center">
                            <h4><strong>Редактировать категорию:</strong></h4>
                            <form @submit.prevent="submit" class="text-center">
                                <span class="text-danger" v-if="errorsCategory.title">{{errorsCategory.title[0]}}</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" >Название категории</span>
                                    <input type="text" v-model="currentCategory.title" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                                <span class="text-danger" v-if="errorsCategory.description">{{errorsCategory.description[0]}}</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" >Описание</span>
                                    <textarea type="text" v-model="currentCategory.description" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></textarea>
                                </div>
                                <button @click="handleUpdate()" class="btn btn-primary" type="submit">Обновить категорию</button>
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
    import { Delete } from '@element-plus/icons-vue';
    import { ref } from "@vue/reactivity";

    import { storeToRefs } from 'pinia';
    import { useCategoriesStore } from "../store/categoriesStore";
    import { onMounted } from "vue";
    import { ElMessage } from "element-plus";

    const categoriesStore = useCategoriesStore();
    const { categories, currentCategory, errorsCategory } = storeToRefs( categoriesStore );
    const dialogTableVisible = ref(false);

    const handleRemove = (id,index) => {
        popupWarning(id,index)
    }
    const handleUpdate = () => {
        categoriesStore.updateCategory(currentCategory);
        dialogTableVisible.value = false;
    }
    const handleEdit = (id) => {
        categoriesStore.getCategoryById(id);
    }
    const popupWarning = (id,index) => {
        ElMessageBox.confirm(`Вы действительно хотите удалить категорию под ID ${id} навсегда? `, 'ПРЕДУПРЕЖДЕНИЕ', {
            confirmButtonText: 'OK',
            cancelButtonText: 'Отмена',
            type: 'warning',
        }).then(() => {
            categoriesStore.removeCategory(id,index);
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
        categoriesStore.getCategories();
    });
</script>
<style>
</style>

