<template>
<!--    <span class="text-danger" v-if="error">{{error[0]}}</span>-->
    <div class="input-group mb-3">
        <span class="input-group-text" >{{inputName}}</span>
        <input type="text" v-bind="currentItem" class="form-control"  aria-describedby="inputGroup-sizing-default">
    </div>
</template>

<script setup>
    import { ref } from "vue";
    defineProps({
        error: Object,
        item: Object,
        inputName: String
    });
    const currentItem = ref(item);
</script>

<style scoped>

</style>
