<template>
    <div class="my-6 space-y-4">
        <SelectImage v-model="form.attributes.header_image" />
        <Input label="Claim" v-model="form.attributes.test" />
    </div>
    <slot />
</template>

<script lang="ts" setup>
import { Input } from '@macramejs/admin-vue3';
import { PartialForm } from '@admin/types/forms';
import { PropType } from 'vue';
import SelectImage from '@admin/modules/content/sections/components/SelectImage.vue';

const props = defineProps({
    form: {
        type: Object as PropType<PartialForm>,
        required: true,
    },
});
</script>
