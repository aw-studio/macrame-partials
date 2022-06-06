<template>
    <div class="my-6 space-y-4">
        <Input label="Claim" v-model="form.attributes.test" />
    </div>
    <slot />
</template>

<script lang="ts" setup>
import { Input } from '@macramejs/admin-vue3';
import { PartialForm } from '@admin/types/forms';
import { PropType } from 'vue';

defineProps({
    form: {
        type: Object as PropType<PartialForm>,
        required: true,
    },
});
</script>
