<template>
    <Admin sidebar-secondary>
        <template v-slot:sidebar-secondary>
            <PartialSidebar :partials="partials" />
        </template>
        <template v-slot:topbar-left>
            <slot name="topbar-left" />
        </template>
    </Admin>
</template>

<script setup lang="ts">
import { Admin } from "@admin/layout";
import { PartialCollectionResource } from "@admin/types/resources";
import PartialSidebar from "./components/PartialSidebar.vue";
import { PropType } from "vue";

const props = defineProps({
    partials: {
        type: Object as PropType<PartialCollectionResource>,
        required: true,
    },
});
</script>
