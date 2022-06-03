<template>
    <div class="my-6 space-y-4">
        <Input label="E-Mail" v-model="form.attributes.email" />
        <Input label="Telefon" v-model="form.attributes.phone" />
    </div>
    <slot />
</template>

<script lang="ts" setup>
import { Input } from "@macramejs/admin-vue3";
import { PartialForm } from "@admin/types/forms";
import { PropType } from "vue";

const props = defineProps({
    form: {
        type: Object as PropType<PartialForm>,
        required: true,
    },
});
</script>
