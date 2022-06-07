<template>
    <SidebarSecondary>
        <template v-slot:header>
            <SidebarSecondaryHeader title="Bereiche">
                <!-- // -->
            </SidebarSecondaryHeader>
        </template>
        <template v-slot:default>
            <SidebarSecondarySection>
                <SidebarLink
                    v-for="partial in partials.data"
                    secondary
                    class="pr-4"
                    :href="`/admin/partial/${partial.id}`"
                >
                    <div class="flex justify-between">
                        <span class="text-gray-300"> {{ partial.name }}</span>
                    </div>
                </SidebarLink>
            </SidebarSecondarySection>
        </template>
    </SidebarSecondary>
</template>

<script lang="ts" setup>
import { PartialCollectionResource } from '@admin/types/resources';
import {
    SidebarSecondary,
    SidebarSecondarySection,
    SidebarSecondaryHeader,
    SidebarLink,
} from '@macramejs/admin-vue3';
import { PropType } from 'vue';

defineProps({
    partials: {
        type: Object as PropType<PartialCollectionResource>,
        requried: true,
    },
});
</script>
