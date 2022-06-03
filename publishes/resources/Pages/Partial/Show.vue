<template>
    <Admin sidebar-secondary>
        <template v-slot:sidebar-secondary>
            <PartialSidebar :partials="partials" />
        </template>
        <template v-slot:topbar-left>
            <slot name="topbar-left" />
        </template>
        <Content>
            <ContentBody>
                <component :is="getComponent()" :form="form"> </component>
            </ContentBody>
        </Content>
    </Admin>
</template>

<script setup lang="ts">
import { Admin } from "@admin/layout";
import PartialSidebar from "./components/PartialSidebar.vue";
import { templates } from "./components/templates";
import { useForm } from "@macramejs/macrame-vue3";
import { saveQueue } from "@admin/modules/save-queue";
import { Content, ContentBody } from "@macramejs/admin-vue3";
import {
    PartialCollectionResource,
    PartialResource,
} from "@admin/types/resources";
import { PropType } from "vue";

const props = defineProps({
    partials: {
        type: Object as PropType<PartialCollectionResource>,
        required: true,
    },
    partial: {
        type: Object as PropType<PartialResource>,
        requried: true,
    },
});
type PartialContent = {};

const partialFormQueueKey = `partial.${props.partial.data.id}`;

const form = useForm<PartialContent>({
    route: `/admin/partial/${props.partial.data.id}`,
    method: "put",
    data: {
        attributes: Array.isArray(props.partial.data.attributes)
            ? {}
            : props.partial.data.attributes,
    },
    onDirty: (form) => {
        return saveQueue.add(partialFormQueueKey, async () => form.submit());
    },
    onClean: () => saveQueue.remove(partialFormQueueKey),
});

const getComponent = () => {
    if (!(props.partial.data.template in templates)) {
        console.error(`No template found for ${props.partial.data.template}`);
    }

    return templates[props.partial.data.template];
};
</script>
