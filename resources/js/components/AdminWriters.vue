<template>
    <div class="common-layout">
        <h1 class="text-center">Редактирование авторов</h1>
        <el-container>
            <AsideAdmin/>
            <el-main>
                <div class="row">
                    <div class="mb-4">
                        <el-pagination
                            v-model:current-page="currentPage"
                            :disabled="disabled"
                            :background="background"
                            layout=" prev, pager, next"
                            :total="totalCount"
                            @current-change="handleCurrentChange"
                        />
                    </div>
                    <el-table :data="writers.data" style="width: 100%">
                        <el-table-column label="Индекс" type="index" width="80"/>
                        <el-table-column prop="id" label="Id" width="80" />
                        <el-table-column prop="name" label="Имя автора" width="800" />
                        <el-table-column prop="id" fixed="right" label="Операции" width="120">
                            <template v-slot:default="scope">
                                <el-button link type="primary" size="small" @click="dialogTableVisible = true,handleEdit(scope.row.id)">Изменить</el-button>
                                <el-button link type="danger" @click="handleRemove(scope.row.id,scope.$index)" :icon="Delete"  />
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-dialog v-model="dialogTableVisible" width="800">
                        <div class="container text-center">
                            <h4><strong>Редактировать автора:</strong></h4>
                            <form @submit.prevent="submit" class="text-center">
                                <span class="text-danger" v-if="errorsWriter.name">{{errorsWriter.name[0]}}</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" >Имя автора</span>
                                    <input type="text" v-model="currentWriter.name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                                <span class="text-danger" v-if="errorsWriter.country">{{errorsWriter.country[0]}}</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" >Страна рождения</span>
                                    <input type="text" v-model="currentWriter.country" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                                <span class="text-danger" v-if="errorsWriter.comment">{{errorsWriter.comment[0]}}</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" >Описание</span>
                                    <textarea type="text" v-model="currentWriter.comment" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"></textarea>
                                </div>
                                <button @click="handleUpdate()" class="btn btn-primary" type="submit">Обновить автора</button>
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
    import { useWritersStore } from "../store/writersStore";
    import { onMounted } from "vue";
    import { ElMessage } from "element-plus";

    const writersStore = useWritersStore();
    const { writers, currentWriter, errorsWriter, currentPage, totalCount, paginatorActivity } = storeToRefs( writersStore );
    const dialogTableVisible = ref(false);
    const background = ref(true);
    const disabled = ref(paginatorActivity);
    const handleRemove = (id,index) => {
        popupWarning(id,index)
    }
    const handleUpdate = () => {
        writersStore.updateWriter(currentWriter);
        dialogTableVisible.value = false;
    }
    const handleEdit = (id) => {
        writersStore.getWriterById(id);
    }
    const handleCurrentChange = (val) => {
        writersStore.updateCurrentPage(val);
        writersStore.getWriters();
    }
    const popupWarning = (id,index) => {
        ElMessageBox.confirm(`Вы действительно хотите удалить автора под ID ${id} навсегда? `, 'ПРЕДУПРЕЖДЕНИЕ', {
            confirmButtonText: 'OK',
            cancelButtonText: 'Отмена',
            type: 'warning',
        }).then(() => {
            writersStore.removeWriter(id,index);
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
        writersStore.getWriters();
    });
</script>
<style>
</style>

